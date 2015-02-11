<?php
namespace common\models;

use common\components\Err as err;
use yii\helpers\Url;
use Yii;

class File extends \frontend\components\hiresource\ActiveRecord
{
    const FILE_MD5 = '76303fjsq06mcr234nc379z32x48';

    public $request;

    public static function currentPrjDir() {
        return Yii::getAlias('@webroot/uploads/');
//        return Yii::getAlias('@app/uploads');
    }

    public function attributes() {
        return [
            'id',
            'ids',
            'object_id',
            'object_ids',
            'seller',
            'sellers',
            'url',
            'filename',
            'author_id',
            'filepath',
            'extension',
        ];
    }

    public static function makeThreadFileUrl($tmp_filename) {
        $key = md5(self::FILE_MD5 . $tmp_filename . 'salt');
        return Url::to(['/thread/thread/getfile', 'tmp_file' => $tmp_filename, 'key' => $key], true);
    }

    private static function put_file_from_site($file, $file_id = null, $ticket_id = null) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file);
        finfo_close($finfo);
        $data = file_get_contents($file);
        if (err::is($data)) return self::put_file_from_api($file_id, $ticket_id);
        if ($mime_type == 'text/plain') {
            $encoded = json_decode($data, true);
            if (err::is($encoded)) return self::put_file_from_api($file_id, $ticket_id);
        }
        header('Content-type: ' . $mime_type);
        echo $data;
        return true;
    }

    private static function put_file_from_api($file_id, $ticket_id) {
        $data = g::base()->ticketGetFile(['file_id' => $file_id, 'id' => $ticket_id]);
//        dlog($data);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $data);
        finfo_close($finfo);
        $md5 = md5($file_id);
        $dir = g::base()->ticketDir($md5);
        $d = $GLOBALS['PRJ_DIR'] . '/var/files';
        if (!file_exists($d)) mkdir($d, 0777, true);
        $d = $GLOBALS['PRJ_DIR'] . '/var/files/tickets';
        if (!file_exists($d)) mkdir($d, 0777, true);
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $file = file_put_contents(g::base()->ticketPath($md5), $data);
        if (!$file) return err::set([], 'no file found');
        header('Content-type: ' . $mime_type);
        echo $data;
        return true;
    }

    public static function putFile($file_id, $ticket_id) {
        if (in_array($file_id, $_SESSION['ticket_files_allow_access']) && !$_REQUEST['source'] == 'api') {
            $res = self::put_file_from_site(g::base()->ticketPath(md5($file_id)), $file_id, $ticket_id);
        }
        else {
            $res = self::put_file_from_api($file_id, $ticket_id);
        }
        return $res;
    }

    /**
     * tmp_file
     * key
     *
     * @param $request
     * @return string
     * @throws \yii\base\ExitException
     */
    public static function getFile($request) {
        if (err::not($request)) {
            if (err::not($request['tmp_file']) && $request['key'] == md5(self::FILE_MD5 . $request['tmp_file'] . "salt")) {
//                $request['tmp_file'] = str_replace(['../', '/'], '', $request['tmp_file']);
                $file = self::currentPrjDir() . $request['tmp_file'];
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
}