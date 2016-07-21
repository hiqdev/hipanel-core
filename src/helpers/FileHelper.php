<?php

namespace hipanel\helpers;

use Yii;
use yii\base\InvalidParamException;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * Returns the MIME-type of content in string
     * @param string $content
     * @return string Content mime-type
     */
    public static function getContentMimeType($content)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $content);
        finfo_close($finfo);

        return $mimeType;
    }

    /**
     * Builds path to the file $filename under the $directory with additional sub-directories.
     * For example, the $directory is `/var/log/yii`, the $filename is `f0527.jpg`.
     * Calling this method without passing optional parameters, will generate the following path:
     * `/var/log/yii/f0/f0527.jpg`.
     * Setting $nests to `2`, the path will look like `/var/log/yii/f0/52/f0527.jpg`
     * Setting $nests to `3` and $length to `1` you will get `/var/log/yii/f/0/5/f0527.jpg`.
     *
     * It is strongly recommended to pass only hashes as $filename in order be sure that length is enough.
     *
     * @param string $directory Path to the base directory
     * @param string $filename The file name
     * @param int $nests Number of nested directories
     * @param int $length Length of the nested directory name
     * @return string
     * @throws InvalidParamException filename does not contain enough characters for directory nesting
     */
    public static function getPrefixedPath($directory, $filename, $nests = 1, $length = 2)
    {
        if (strlen(basename($filename)) < $nests * $length) {
            throw new InvalidParamException('Filename does not contain enough characters for directory nesting');
        }

        for ($start = 0; $start < $nests * $length; $start += $length) {
            $level = substr($filename, $start, $length);
            $directory .= DIRECTORY_SEPARATOR . $level;
        }

        $directory .= DIRECTORY_SEPARATOR . $filename;

        return Yii::getAlias($directory);
    }
}
