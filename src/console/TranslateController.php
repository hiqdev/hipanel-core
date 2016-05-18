<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\console;

use Stichoza\GoogleTranslate\TranslateClient;
use Yii;
use yii\console\controllers\MessageController;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * Usage: ./yii translate/google_extract /home/tofid/www/hipanel.dev/config/i18n.php
 * Class TranslateController.
 */
class TranslateController extends MessageController
{
    public $configFile = '@hipanel/config/i18n.php';

    public function actionIndex($message = 'hello {world} apple beta {gamma {horse, cat}} this {mysite} this is a draw {apple {beta,{cat,dog} dog} house} farm')
    {
        //        echo GoogleTranslate::staticTranslate('hello world', "en", "ru") . "\n";
        echo TranslateClient::translate('en', 'ru', 'Hello again') . "\n";
        $message = 'The image "{file}" is too large. The height cannot be larger than {limit, number} {limit, plural, one{pixel} other{pixels}}.';
        print_r($this->parse_safe_translate($message));
        /*
        $message='{nFormatted} {n, plural, =1{kibibyte} other{kibibytes}}';
       print_r($this->parse_safe_translate($message));
       $message='The "apple" {is} good.';
       print_r($this->parse_safe_translate($message));
       $message='The image "{file}" is too large. The height cannot be larger than {limit, number} {limit, plural, one{pixel} other{pixels}}.';
       print_r($this->parse_safe_translate($message));
       var_dump($this->parse_safe_translate('Are you sure you want to delete this item?'));
       var_dump($this->parse_safe_translate('Create {modelClass}'));
       */
    }

    /**
     * Extracts messages to be translated from source code.
     *
     * This command will search through source code files and extract
     * messages that need to be translated in different languages.
     *
     * @param string $configFile the path or alias of the configuration file.
     * You may use the "yii message/config" command to generate
     * this file and then customize it for your needs.
     * @throws Exception on failure.
     */
    public function actionGoogleExtract($configFile = null)
    {
        $configFile = Yii::getAlias($configFile ?: $this->configFile);
        if (!is_file($configFile)) {
            throw new Exception("The configuration file does not exist: $configFile");
        }
        $config = array_merge([
            'translator' => 'Yii::t',
            'overwrite' => false,
            'removeUnused' => false,
            'sort' => false,
            'format' => 'php',
        ], require_once($configFile));
        if (!isset($config['sourcePath'], $config['languages'])) {
            throw new Exception('The configuration file must specify "sourcePath" and "languages".');
        }
        if (!is_dir($config['sourcePath'])) {
            throw new Exception("The source path {$config['sourcePath']} is not a valid directory.");
        }
        if (empty($config['format']) || !in_array($config['format'], ['php', 'po', 'db'], true)) {
            throw new Exception('Format should be either "php", "po" or "db".');
        }
        if (in_array($config['format'], ['php', 'po'], true)) {
            if (!isset($config['messagePath'])) {
                throw new Exception('The configuration file must specify "messagePath".');
            } elseif (!is_dir($config['messagePath'])) {
                throw new Exception("The message path {$config['messagePath']} is not a valid directory.");
            }
        }
        if (empty($config['languages'])) {
            throw new Exception('Languages cannot be empty.');
        }
        $files = FileHelper::findFiles(realpath($config['sourcePath']), $config);
        $messages = [];
        foreach ($files as $file) {
            $messages = array_merge_recursive($messages, $this->extractMessages($file, $config['translator']));
        }
        if (in_array($config['format'], ['php', 'po'], true)) {
            foreach ($config['languages'] as $language) {
                $dir = $config['messagePath'] . DIRECTORY_SEPARATOR . $language;
                if (!is_dir($dir)) {
                    @mkdir($dir);
                }
                if ($config['format'] === 'po') {
                    $catalog = isset($config['catalog']) ? $config['catalog'] : 'messages';
                    $this->saveMessagesToPO($messages, $dir, $config['overwrite'], $config['removeUnused'], $config['sort'], $catalog);
                } else {
                    $this->saveMessagesToPHPEnhanced($messages, $dir, $config['overwrite'], $config['removeUnused'], $config['sort'], $language);
                }
            }
        } elseif ($config['format'] === 'db') {
            $db = \Yii::$app->get(isset($config['db']) ? $config['db'] : 'db');
            if (!$db instanceof \yii\db\Connection) {
                throw new Exception('The "db" option must refer to a valid database application component.');
            }
            $sourceMessageTable = isset($config['sourceMessageTable']) ? $config['sourceMessageTable'] : '{{%source_message}}';
            $messageTable = isset($config['messageTable']) ? $config['messageTable'] : '{{%message}}';
            $this->saveMessagesToDb(
                $messages,
                $db,
                $sourceMessageTable,
                $messageTable,
                $config['removeUnused'],
                $config['languages']
            );
        }
    }

    public function parse_safe_translate($s)
    {
        $debug = false;
        $result = [];
        $start = 0;
        $nest = 0;
        $ptr_first_curly = 0;
        $total_len = strlen($s);
        for ($i = 0; $i < $total_len; ++$i) {
            if ($s[$i] === '{') {
                // found left curly
                if ($nest === 0) {
                    // it was the first one, nothing is nested yet
                    $ptr_first_curly = $i;
                }
                // increment nesting
                $nest += 1;
            } elseif ($s[$i] === '}') {
                // found right curly
                // reduce nesting
                $nest -= 1;
                if ($nest === 0) {
                    // end of nesting
                    if ($ptr_first_curly - $start >= 0) {
                        // push string leading up to first left curly
                        $prefix = substr($s, $start, $ptr_first_curly - $start);
                        if (strlen($prefix) > 0) {
                            array_push($result, $prefix);
                        }
                    }
                    // push (possibly nested) curly string
                    $suffix = substr($s, $ptr_first_curly, $i - $ptr_first_curly + 1);
                    if (strlen($suffix) > 0) {
                        array_push($result, $suffix);
                    }
                    if ($debug) {
                        echo '|' . substr($s, $start, $ptr_first_curly - $start - 1) . "|\n";
                        echo '|' . substr($s, $ptr_first_curly, $i - $ptr_first_curly + 1) . "|\n";
                    }
                    $start = $i + 1;
                    $ptr_first_curly = 0;
                    if ($debug) {
                        echo 'next start: ' . $start . "\n";
                    }
                }
            }
        }
        $suffix = substr($s, $start, $total_len - $start);
        if ($debug) {
            echo 'Start:' . $start . "\n";
            echo 'Pfc:' . $ptr_first_curly . "\n";
            echo $suffix . "\n";
        }
        if (strlen($suffix) > 0) {
            array_push($result, substr($s, $start, $total_len - $start));
        }
        return $result;
    }

    public function getGoogleTranslation($message, $language)
    {
        $arr_parts = $this->parse_safe_translate($message);
        $translation = '';
        foreach ($arr_parts as $str) {
            if (!stristr($str, '{')) {
                if (strlen($translation) > 0 and substr($translation, -1) === '}') {
                    $translation .= ' ';
                }
                $translation .= TranslateClient::translate(Yii::$app->language, $language, $str); // GoogleTranslate::staticTranslate($str, Yii::$app->language, $language);
            } else {
                // add space prefix unless it's first
                if (strlen($translation) > 0) {
                    $translation .= ' ' . $str;
                } else {
                    $translation .= $str;
                }
            }
        }
        print_r($translation);
        return $translation;
    }

    protected function saveMessagesToPHPEnhanced($messages, $dirName, $overwrite, $removeUnused, $sort, $language)
    {
        foreach ($messages as $category => $msgs) {
            $file = str_replace('\\', '/', "$dirName/$category.php");
            $path = dirname($file);
            FileHelper::createDirectory($path);
            $msgs = array_values(array_unique($msgs));
            $coloredFileName = Console::ansiFormat($file, [Console::FG_CYAN]);
            $this->stdout("Saving messages to $coloredFileName...\n");
            $this->saveMessagesCategoryToPHPEnhanced($msgs, $file, $overwrite, $removeUnused, $sort, $category, $language);
        }
    }

    protected function saveMessagesCategoryToPHPEnhanced($messages, $fileName, $overwrite, $removeUnused, $sort, $category, $language, $force = true)
    {
        if (is_file($fileName)) {
            $existingMessages = require $fileName;
            sort($messages);
            ksort($existingMessages);
            if (!$force) {
                if (array_keys($existingMessages) === $messages) {
                    $this->stdout("Nothing new in \"$category\" category... Nothing to save.\n\n", Console::FG_GREEN);
                    return;
                }
            }
            $merged = [];
            $untranslated = [];
            foreach ($messages as $message) {
                if (array_key_exists($message, $existingMessages) && strlen($existingMessages[$message]) > 0) {
                    $merged[$message] = $existingMessages[$message];
                } else {
                    $untranslated[] = $message;
                }
            }
            ksort($merged);
            sort($untranslated);
            $todo = [];
            foreach ($untranslated as $message) {
                $todo[$message] = $this->getGoogleTranslation($message, $language);
            }
            ksort($existingMessages);
            foreach ($existingMessages as $message => $translation) {
                if (!isset($merged[$message]) && !isset($todo[$message]) && !$removeUnused) {
                    if (!empty($translation) && strncmp($translation, '@@', 2) === 0 && substr_compare($translation, '@@', -2, 2) === 0) {
                        $todo[$message] = $translation;
                    } else {
                        $todo[$message] = '@@' . $translation . '@@';
                    }
                }
            }

            $merged = array_merge($todo, $merged);
            if ($sort) {
                ksort($merged);
            }
            if (false === $overwrite) {
                $fileName .= '.merged';
            }
            $this->stdout("Translation merged.\n");
        } else {
            $merged = [];
            foreach ($messages as $message) {
                $merged[$message] = '';
            }
            ksort($merged);
        }

        $array = VarDumper::export($merged);
        $content = <<<EOD
<?php
/**
* Message translations.
*
* This file is automatically generated by 'yii {$this->id}' command.
* It contains the localizable messages extracted from source code.
* You may modify this file by translating the extracted messages.
*
* Each array element represents the translation (value) of a message (key).
* If the value is empty, the message is considered as not translated.
* Messages that no longer need translation will have their translations
* enclosed between a pair of '@@' marks.
*
* Message string can be used with plural forms format. Check i18n section
* of the guide for details.
*
* NOTE: this file must be saved in UTF-8 encoding.
*/
return $array;
EOD;

        file_put_contents($fileName, $content);
        $this->stdout("Translation saved.\n\n", Console::FG_GREEN);
    }
}
