<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 18.02.15
 * Time: 17:40
 */

namespace frontend\components;

class Response extends \yii\web\Response
{
    /**
     * Sends the specified content as a file to the browser.
     *
     * Note that this method only prepares the response for file sending. The file is not sent
     * until [[send()]] is called explicitly or implicitly. The latter is done after you return from a controller action.
     *
     * @param string $content the content to be sent. The existing [[content]] will be discarded.
     * @param string $attachmentName the file name shown to the user.
     * @param array $options additional options for sending the file. The following options are supported:
     *
     *  - `mimeType`: the MIME type of the content. Defaults to 'application/octet-stream'.
     *  - `inline`: boolean, whether the browser should open the file within the browser window. Defaults to false,
     *     meaning a download dialog will pop up.
     *
     * @return static the response object itself
     * @throws HttpException if the requested range is not satisfiable
     */
    public function sendContentAsFile($content, $attachmentName, $options = [])
    {
        $headers = $this->getHeaders();

        $contentLength = StringHelper::byteLength($content);
        $range = $this->getHttpRange($contentLength);

        if ($range === false) {
            $headers->set('Content-Range', "bytes */$contentLength");
            throw new HttpException(416, 'Requested range not satisfiable');
        }

        $mimeType = isset($options['mimeType']) ? $options['mimeType'] : 'application/octet-stream';
        if ($options['display']) {
            $stream = fopen('php://temp','r+');
            fwrite($stream, $content);
            rewind($stream);
            $this->setRawHeaders($stream, $mimeType, $contentLength);
            fclose($stream);
        } else {
            $this->setDownloadHeaders($attachmentName, $mimeType, !empty($options['inline']), $contentLength);
        }

        list($begin, $end) = $range;
        if ($begin != 0 || $end != $contentLength - 1) {
            $this->setStatusCode(206);
            $headers->set('Content-Range', "bytes $begin-$end/$contentLength");
            $this->content = StringHelper::byteSubstr($content, $begin, $end - $begin + 1);
        } else {
            $this->setStatusCode(200);
            $this->content = $content;
        }

        $this->format = self::FORMAT_RAW;

        return $this;
    }

    public function setRawHeaders($stream, $mimeType = null, $contentLength = null)
    {
        $headers = $this->getHeaders();

        if (!$mimeType) $mimeType = FileHelper::getMimeType($stream);

        $headers->setDefault('Pragma', 'public')
            ->setDefault('Accept-Ranges', 'bytes')
            ->setDefault('Expires', '0')
            ->setDefault('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');

        if ($mimeType !== null) {
            $headers->setDefault('Content-Type', $mimeType);
        }

        if ($contentLength !== null) {
            $headers->setDefault('Content-Length', $contentLength);
        }

        return $this;
    }
}