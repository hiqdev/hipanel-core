<?php
namespace common\models;

use common\components\Err as err;
use yii\helpers\Url;
use Yii;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class File extends \frontend\components\hiresource\ActiveRecord
{
    const MD5 = '76303fjsq06mcr234nc379z32x48';
    const SALT = 'salt';

    /**
     * @var string the directory to store uploaded files. You may use path alias here.
     * If not set, it will use the "upload" subdirectory under the application runtime path.
     */
    public $path = '@runtime/upload';

    /**
     * @param $key
     * @return bool|string
     */
    public static function uploadPath($key) {
        return Yii::getAlias('@runtime/upload/');
    }

    /**
     * @return array
     */
    public function attributes() {
        return [
            'id',
            'ids',
            'object_id',
            'object_ids',
            'object',
            'seller',
            'sellers',
            'url',
            'filename',
            'author_id',
            'filepath',
            'extension',
        ];
    }

    /**
     * @param $name
     * @return string
     */
    public static function getHash($name) {
        return md5(self::MD5 . $name . self::SALT);
    }

    public static function filePath($file_id) {
        return Yii::getAlias('@runtime/upload/' . substr($file_id, -2, 2) . DIRECTORY_SEPARATOR . $file_id);
//        return implode('/', [self::fileDir($md5), $md5]);
    }

    public static function fileDir($md5) {
        return implode('/', ['var/files/tickets', substr($md5, 0, 2)]); // $GLOBALS['PRJ_DIR'],
    }

    /**
     * @return string
     */
    public static function getTempFolder() {
        return Yii::getAlias('@runtime/tmp');
    }

    /**
     * @param $temp_name
     * @return string
     */
    public static function getTmpUrl($temp_name) {
        $key = self::getHash($temp_name);
        return Url::to(['/file/temp-view', 'temp_file' => $temp_name, 'key' => $key], true);
    }

    protected function fileGet($rows, $params = []) {
        $input = $this->_prepareData($rows);
        $response = http::fetchPost($this->api_url . 'fileGet', $input);
        return $response;
    }

    /**
     * @param $file
     * @param null $file_id
     * @param null $object_id
     * @return array|bool
     */
    public static function get_file_from_site($file, $file_id = null, $object_id = null, $object_name = null, $render = true) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file);
        finfo_close($finfo);
//        $data = file_get_contents($file);
//        if (err::is($data))
//            return self::put_file_from_api($file_id, $object_id, $object_name, $render);
        if ($mime_type == 'text/plain') {
            $encoded = json_decode(file_get_contents($file), true);
            if (err::is($encoded))
                return self::get_file_from_api($file_id, $object_id, $object_name, $render);
        }
        if ($render)
            return self::responseFile($file, $mime_type);
        else
            return $file;
    }

    private static function responseFile($path, $content_type) {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->getHeaders()->add('content-type', $content_type);
        return file_get_contents($path);
    }

    public static function get_file_from_api($file_id, $object_id = null, $object_name = null, $render = true) {
        $data = self::perform('Get', ['id' => $file_id, 'object_id' => $object_id, 'object' => $object_name]);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $data);
        finfo_close($finfo);
        $finalDestination = self::filePath($file_id);
        FileHelper::createDirectory(dirname($finalDestination));
        $file = file_put_contents($finalDestination, $data);
        if (!$file)
            throw new NotFoundHttpException('File not found!');
        if ($render)
            return self::responseFile($finalDestination, $mime_type);
        else
            return $finalDestination;
    }

    public static function renderFile($file_id, $object_id = null, $object_name = null, $render = true) {
        if (is_file(self::filePath($file_id)))
            $res = self::get_file_from_site(self::filePath($file_id), $file_id, $object_id, $object_name, $render);
        else
            $res = self::get_file_from_api($file_id, $object_id, $object_name, $render);
        return $res;
    }

    public static function fileSave(array $files) {
        $arr_ids = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                // Move to temporary destination
                $tempDestination = self::getTempFolder() . DIRECTORY_SEPARATOR . uniqid() . '.' . $file->extension;
                FileHelper::createDirectory(dirname($tempDestination));
                $file->saveAs($tempDestination);
                // Prepare to final destination
                $url = self::getTmpUrl(basename($tempDestination));
                $response =  self::perform('Put', [
                    'url' => $url,
                    'filename' => basename($tempDestination)
                ]);
                $file_id = $arr_ids[] = $response['id'];
                $finalDestination = self::filePath($file_id);
                FileHelper::createDirectory(dirname($finalDestination));
                if (!rename($tempDestination, $finalDestination))
                    throw new \LogicException('rename function is not work');
                if (is_file($tempDestination))
                    unlink($tempDestination);
            }
        }
        return $arr_ids;
    }

    /*
    public static function getFile($request) {
        if (err::not($request)) {
            if (err::not($request['tmp_file']) && $request['key'] == md5(self::MD5 . $request['tmp_file'] . "salt")) {
//                $request['tmp_file'] = str_replace(['../', '/'], '', $request['tmp_file']);
                $file = self::uploadPath() . $request['tmp_file'];
                if (file_exists($file)) {
                    if (err::is(self::put_file_from_site($file))) return json_encode(['_error' => 'file not found']);
                    \Yii::$app->end();
                }
            }
//            if (tpl::get('auth.id')) {
//                $file_id = check::id($request['file_id'] ? : $URLPARTS[3]);
//                $ticket_id = check::id($request['ticket_id'] ? : $URLPARTS[4]);
//                if ($file_id) {
//                    $res = putFile($file_id, $ticket_id);
//                    if (err::is($res)) echo json_encode($res);
//                    exit;
//                }
//                if (isset($_SESSION['ticket_create_files_tmp'][$request['tmp_file']])) {
//                    $file = "{".self::PRJ_DIR."}/var/tmp/{$request['tmp_file']}";
//                    if (file_exists($file)) {
//                        if (err::is(self::put_file_from_site($file))) echo json_encode(['_error' => 'file not found']);;
//                    }
//                }
//            }
        }
    }
    */
}