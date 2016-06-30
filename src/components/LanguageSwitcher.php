<?php

namespace hipanel\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\web\Cookie;

class LanguageSwitcher extends Component implements BootstrapInterface
{
    /**
     * @var string Name of the cookie.
     */
    public $cookieName = 'language';

    /**
     * @var array list of available language codes. More specific patterns should come first, e.g. 'en_us' before 'en'.
     * Key is a language code, value is a name of language. For example:
     *
     * ```php
     * [
     *     'ru' => 'Русский',
     *     'en' => 'English',
     * ]
     * ```
     *
     */
    public $languages = [];

    /**
     * @var integer expiration date of the cookie storing the language of the site.
     */
    public $expireDays = 30;

    public function bootstrap($app)
    {
        $this->initLanguage();
    }

    /**
     * Saving language into cookie and database.
     * @param string $language - The language to save.
     * @return static
     */
    public function saveLanguage($language)
    {
        $this->applyLanguage($language);
        $this->saveLanguageIntoCookie($language);
    }

    private function initLanguage()
    {
        if ($this->isValidLanguage(Yii::$app->request->cookies->getValue($this->cookieName))) {
            $this->applyLanguage(Yii::$app->request->cookies->getValue($this->cookieName));
        } else {
            Yii::$app->response->cookies->remove($this->cookieName);

            if (($language = $this->detectLanguage()) !== false) {
                $this->saveLanguage($language);
            }
        }
    }

    /**
     * Determine language based on UserAgent.
     */
    public function detectLanguage()
    {
        $acceptableLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
        foreach ($acceptableLanguages as $language) {
            if ($this->isValidLanguage($language)) {
                return $language;
            }
        }
        foreach ($acceptableLanguages as $language) {
            $pattern = preg_quote(substr($language, 0, 2), '/');
            foreach ($this->languages as $key => $value) {
                if (preg_match('/^' . $pattern . '/', $value) || preg_match('/^' . $pattern . '/', $key)) {
                    return $this->isValidLanguage($key) ? $key : $value;
                }
            }
        }

        return false;
    }

    /**
     * Save language into cookie.
     * @param string $language
     */
    private function saveLanguageIntoCookie($language)
    {
        $cookie = new Cookie([
            'name' => $this->cookieName,
            'value' => $language,
            'expire' => time() + 86400 * $this->expireDays,
        ]);
        Yii::$app->response->cookies->add($cookie);
    }

    /**
     * Determines whether the language received as a parameter can be processed.
     * @param string $language
     * @return boolean
     */
    public function isValidLanguage($language)
    {
        return is_string($language) && isset($this->languages[$language]);
    }

    /**
     * @param $language
     * @return bool whether the language is correct and saved
     */
    public function setLanguage($language)
    {
        if ($this->isValidLanguage($language)) {
            $this->saveLanguage($language);
            return true;
        }

        return false;
    }

    /**
     * @param string $language
     */
    private function applyLanguage($language)
    {
        Yii::$app->language = $language;
        Yii::$app->getFormatter()->locale = $language;
    }
}
