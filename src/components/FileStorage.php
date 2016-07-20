<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

use hipanel\helpers\FileHelper;
use hipanel\models\File;
use hiqdev\hiart\ErrorResponseException;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class FileStorage provides interface to save uploaded files and view saved files
 * using integration with HiArt.
 *
 * @package hipanel\components
 */
class FileStorage extends Component
{
    /**
     * @var string Secret string used to create hashes of files.
     * Required property, must be configured in config
     */
    public $secret;

    /**
     * @var string Path to the directory for temporary files storage.
     * Used to save file after upload until API downloads it.
     * Defaults to `@runtime/tmp`
     */
    public $tempDirectory = '@runtime/tmp';

    /**
     * @var string Path to the directory for files permanent storing.
     * Defaults to `@runtime/upload`.
     */
    public $directory = '@runtime/upload';

    /**
     * @var string The route that will be passed to API in order to download uploaded file.
     * The action must accept 2 GET parameters: `filename` and `key`, then
     * call [[FileStorage::readTemporary($filename, $key)]] and send file contents in its body.
     * Defaults to `@file/temp-view`.
     */
    public $temporaryViewRoute = '@file/temp-view';

    /**
     * @var string Namespace of the class that represents File.
     * Defaults to [[File]].
     */
    public $fileModelClass = File::class;

    /** @inheritdoc */
    public function init()
    {
        $this->tempDirectory = Yii::getAlias($this->tempDirectory);
        FileHelper::createDirectory($this->tempDirectory);

        $this->directory = Yii::getAlias($this->directory);
        FileHelper::createDirectory($this->directory);

        if ($this->secret === null) {
            throw new InvalidConfigException("Please, set the \"secret\" property for the FileStorage component");
        }

    }

    /**
     * Saves uploaded file under the [[tempDirectory]] with random file name
     *
     * @param UploadedFile $file
     * @return string randomly generated file name
     * @throws ErrorException when file is not saved
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        do {
            $filename = Yii::$app->security->generateRandomString(16) . '.' . $file->getExtension();
            $path = $this->getTemporaryPath($filename);
        } while (is_file($path));

        if (!$file->saveAs($path)) {
            throw new ErrorException('Failed to save uploaded file');
        }

        return $filename;
    }

    /**
     * Builds path to the temporary location of $filename under the [[tempDirectory]]
     *
     * @param string $filename
     * @return string full path to the temporary file
     */
    protected function getTemporaryPath($filename = '')
    {
        return $this->tempDirectory . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Puts file $filename to the API.
     *
     * File must be previously saved to the [[tempDirectory]] using [[saveUploadedFile]] method,
     * otherwise exception will be thrown.
     *
     * @param string $filename The temporary file name
     * @param string $originalName Original (as file was uploaded) file name. Optional, defaults to $filename
     * @return File The file model
     * @throws Exception when file $filename does not exist
     */
    public function put($filename, $originalName = null)
    {
        if (!is_file($this->getTemporaryPath($filename))) {
            throw new Exception('File you are trying to upload does not exist');
        }

        if ($originalName === null) {
            $originalName = basename($filename);
        }

        $model = Yii::createObject([
            'class' => $this->fileModelClass,
            'scenario' => 'put',
            'filename' => $originalName,
            'url' => $this->getTemporaryViewUrl($filename)
        ]);

        $model->save();

        unlink($this->getTemporaryPath($filename));

        return $model;
    }

    /**
     * Builds key identifying the [[File]] model to be cached.
     * @param integer $fileId
     * @return array
     * @see get
     * @see getFileModel
     */
    protected function buildCacheKey($fileId)
    {
        return [static::class, 'file', $fileId, Yii::$app->user->isGuest ? true : Yii::$app->user->id];
    }

    /**
     * Gets the path of the file with $id
     *
     * Method downloads the requested file from the API and saves it to the local machine.
     * Method respects authentication and access rules.
     *
     * @param integer $id the ID of the file
     * @param bool $overrideCache whether the cache must be invalidated
     * @return string full path to the file. File is located under the [[directory]]
     * @throws Exception when fails to save file locally
     * @throws ForbiddenHttpException when file is not available to client due to policies
     */
    public function get($id, $overrideCache = false)
    {
        $file = $this->getFileModel($id, $overrideCache);

        $path = FileHelper::getPrefixedPath($this->directory, static::buildHash($file->md5));
        if (!is_file($path) || $overrideCache) {
            $content = $file::perform('Get', ['id' => $id]);

            if (!FileHelper::createDirectory(dirname($path))) {
                throw new \yii\base\Exception("Failed to create directory");
            }

            if (!file_put_contents($path, $content)) {
                throw new \yii\base\Exception("Failed to create local file");
            }
        }

        $cache = $this->getCache();
        $key = $this->buildCacheKey($id);
        $cache->set($key, $file, 0);

        return $path;
    }

    /**
     * @return \yii\caching\Cache
     */
    protected function getCache()
    {
        return Yii::$app->cache;
    }

    /**
     * Retrieves [[File]] model for the $id.
     * Uses cache and can get model from it.
     *
     * @param integer $id the ID of the file
     * @param bool $overrideCache whether the cache must be invalidated
     * @return File
     * @throws ForbiddenHttpException when file is not available to client due to policies
     */
    public function getFileModel($id, $overrideCache = false)
    {
        $cache = $this->getCache();
        $key = $this->buildCacheKey($id);

        /** @var File $file */
        $file = $cache->get($key);

        if ($file !== false && !$overrideCache) {
            return $file;
        }

        /** @var File $model */
        $model = $this->fileModelClass;
        try {
            $file = $model::find()->where(['id' => $id])->one();
        } catch (ErrorResponseException $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }

        return $file;
    }

    /**
     * Return URL to the route that provides access to the temporary file.
     * @param string $filename the file name
     * @return string URL
     * @see temporaryViewRoute
     */
    protected function getTemporaryViewUrl($filename)
    {
        return Url::to([$this->temporaryViewRoute, 'filename' => $filename, 'key' => $this->buildHash($filename)], true);
    }

    /**
     * Builds MD5 hash using [[secret]] and $sting
     *
     * @param $string
     * @return string MD5 hash
     */
    protected function buildHash($string)
    {
        return md5(sha1($this->secret) . $string);
    }

    /**
     * Gets path to the temporary file $filename located under the [[tempDirectory]]
     *
     * @param string $filename the file name
     * @param string $key secret key that was previously generated by [[buildHash]] method unauthorized access
     * @return string path to the temporary file
     * @throws ForbiddenHttpException when failed to verify secret $key
     * @throws NotFoundHttpException when the requested files does not exist
     */
    public function getTemporary($filename, $key)
    {
        if (!Yii::$app->security->compareString($this->buildHash($filename), $key)) {
            throw new ForbiddenHttpException('The provided key is invalid');
        }

        $path = $this->getTemporaryPath($filename);
        if (!is_file($path)) {
            throw new NotFoundHttpException('The requested file does not exist');
        }

        return $path;
    }
}
