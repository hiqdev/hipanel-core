<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\UrlManager;

class LanguageUrlManager extends UrlManager
{
    /**
     * @var array list of available language codes. More specific patterns should come first, e.g. 'en_us'
     * before 'en'. This can also contain mapping of <url_value> => <language>, e.g. 'english' => 'en'.
     */
    public $languages = [];

    /**
     * @var string the name of the session key that is used to store the language. Default is '_language'.
     */
    public $languageSessionKey = '_language';

    /**
     * @var string the name of the language cookie. Default is '_language'.
     */
    public $languageCookieName = '_language';

    /**
     * @var string the language that was initially set in the application configuration
     */
    protected $_defaultLanguage;

    /**
     * @var int number of seconds how long the language information should be stored in cookie,
     * if `$enableLanguagePersistence` is true. Set to `false` to disable the language cookie completely.
     * Default is 30 days.
     */
    public $languageCookieDuration = 2592000;

    /**
     * @var integer expiration date of the cookie storing the language of the site.
     */
    public $expireDays = 30;

    /**
     * @var string if a parameter with this name is passed to any `createUrl()` method, the created URL
     * will use the language specified there. URLs created this way can be used to switch to a different
     * language. If no such parameter is used, the currently detected application language is used.
     */
    public $languageParam = 'language';

    /**
     * @var \Closure function to execute after changing the language of the site.
     */
    public $callback;

    /**
     * @var string Name of the cookie.
     */
    public $cookieName = 'language';

    /**
     * @var \yii\web\Request
     */
    protected $_request;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->initLanguage();
        return parent::init();
    }

    /**
     * Setting the language of the site.
     */
    public function initLanguage()
    {
        $language = Yii::$app->request->get($this->languageParam);
        if ($language) {
            if (isset($this->languages[$language])) {
                $language = $this->languages[$language];
            }
            if ($this->_isValidLanguage($language)) {
                return $this->saveLanguage($language);
            } elseif (!Yii::$app->request->isAjax) {
                return $this->_redirect();
            }
        } elseif (Yii::$app->request->cookies->has($this->cookieName)) {
            if ($this->_isValidLanguage(Yii::$app->request->cookies->getValue($this->cookieName))) {
                //                Yii::$app->language = Yii::$app->request->cookies->getValue($this->cookieName);
                $this->applyLanguage(Yii::$app->request->cookies->getValue($this->cookieName));
                return;
            } else {
                Yii::$app->response->cookies->remove($this->cookieName);
            }
        }
        $this->detectLanguage();
    }

    /**
     * Saving language into cookie and database.
     * @param string $language - The language to save.
     * @return static
     */
    public function saveLanguage($language)
    {
        //        Yii::$app->language = $language;
        $this->applyLanguage($language);
        $this->saveLanguageIntoCookie($language);
        if (is_callable($this->callback)) {
            call_user_func($this->callback);
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->end();
        }
        return $this->_redirect();
    }

    /**
     * Determine language based on UserAgent.
     */
    public function detectLanguage()
    {
        $acceptableLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
        foreach ($acceptableLanguages as $language) {
            if ($this->_isValidLanguage($language)) {
                //                Yii::$app->language = $language;
                $this->applyLanguage($language);
                $this->saveLanguageIntoCookie($language);
                return;
            }
        }
        foreach ($acceptableLanguages as $language) {
            $pattern = preg_quote(substr($language, 0, 2), '/');
            foreach ($this->languages as $key => $value) {
                if (preg_match('/^' . $pattern . '/', $value) || preg_match('/^' . $pattern . '/', $key)) {
                    //                    Yii::$app->language = $this->_isValidLanguage($key) ? $key : $value;
                    $this->applyLanguage($this->_isValidLanguage($key) ? $key : $value);
                    $this->saveLanguageIntoCookie(Yii::$app->language);
                    return;
                }
            }
        }
    }

    /**
     * Save language into cookie.
     * @param string $language
     */
    public function saveLanguageIntoCookie($language)
    {
        $cookie = new \yii\web\Cookie([
            'name' => $this->cookieName,
            'value' => $language,
            'expire' => time() + 86400 * $this->expireDays,
        ]);
        Yii::$app->response->cookies->add($cookie);
    }
    /**
     * Redirects the browser to the referer URL.
     * @return static
     */
    private function _redirect()
    {
        $redirect = Yii::$app->request->absoluteUrl === Yii::$app->request->referrer ? '/' : Yii::$app->request->referrer;
        return Yii::$app->response->redirect($redirect);
    }
    /**
     * Determines whether the language received as a parameter can be processed.
     * @param string $language
     * @return boolean
     */
    private function _isValidLanguage($language)
    {
        return is_string($language) && (isset($this->languages[$language]) || in_array($language, $this->languages, true));
    }

    protected function applyLanguage($language)
    {
        Yii::$app->language = $language;
        Yii::$app->formatter->locale = $language;
    }

//
//
//
//    /**
//     * @inheritdoc
//     */
//    public function parseRequest($request)
//    {
//
//        $this->detectLanguage($request);
//
//        return parent::parseRequest($request);
//    }
//
//    protected function setUpLanguage($language)
//    {
//        Yii::$app->language = $language;
//    }
//
//    /**
//     * Checks for a language or locale parameter in the URL and rewrites the pathInfo if found.
//     * If no parameter is found it will try to detect the language from persistent storage (session /
//     * cookie) or from browser settings.
//     *
//     * @var \yii\web\Request $request
//     */
//    protected function detectLanguage($request)
//    {
//        $this->_request = $request;
//        $pathInfo = $request->getPathInfo();
//        $parts = [];
//        foreach ($this->languages as $k => $v) {
//            $value = is_string($k) ? $k : $v;
//            if (substr($value, -2)==='-*') {
//                $lng = substr($value, 0, -2);
//                $parts[] = "$lng\-[a-z]{2,3}";
//                $parts[] = $lng;
//            } else {
//                $parts[] = $value;
//            }
//        }
//        $pattern = implode('|', $parts);
//        if ($request->get($this->languageParam) && preg_match("#^($pattern)\b(/?)#i", $request->get($this->languageParam), $m)) {
//            $request->setPathInfo(mb_substr($pathInfo, mb_strlen($m[1].$m[2])));
//            $code = $m[1];
//            if (isset($this->languages[$code])) {
//                // Replace alias with language code
//                $language = $this->languages[$code];
//            } else {
//                list($language,$country) = $this->matchCode($code);
//                if ($country!==null) {
//                    if ($code==="$language-$country") {
//                        $this->redirectToLanguage('');
//                    } else {
//                        $language = "$language-$country";
//                    }
//                }
//                if ($language===null) {
//                    $language = $code;
//                }
//            }
//            Yii::$app->language = $language;
//            Yii::$app->session[$this->languageSessionKey] = $language;
//            if ($this->languageCookieDuration) {
//                $cookie = new Cookie([
//                    'name' => $this->languageCookieName,
//                    'httpOnly' => true
//                ]);
//                $cookie->value = $language;
//                $cookie->expire = time() + (int) $this->languageCookieDuration;
//                Yii::$app->getResponse()->getCookies()->add($cookie);
//            }
//            $this->redirectToLanguage('');
//        } else {
//            $language = null;
//            $language = Yii::$app->session->get($this->languageSessionKey);
//            if ($language===null) {
//                $language = $request->getCookies()->getValue($this->languageCookieName);
//            }
//            if ($language===null) {
//                foreach ($request->getAcceptableLanguages() as $acceptable) {
//                    list($language,$country) = $this->matchCode($acceptable);
//                    if ($language!==null) {
//                        $language = $country===null ? $language : "$language-$country";
//                        break;
//                    }
//                }
//            }
//            if ($language===null || $language===$this->_defaultLanguage) {
//                return;
//            }
//            // #35: Only redirect if a valid language was found
//            if ($this->matchCode($language)===[null, null]) {
//                return;
//            }
//
//            $key = array_search($language, $this->languages);
//            if ($key && is_string($key)) {
//                $language = $key;
//            }
//
//            $this->setUpLanguage($language);
//            $this->redirectToLanguage('');
//        }
//    }
//
//    /**
//     * Tests whether the given code matches any of the configured languages.
//     *
//     * If the code is a single language code, and matches either
//     *
//     *  - an exact language as configured (ll)
//     *  - a language with a country wildcard (ll-*)
//     *
//     * this language code is returned.
//     *
//     * If the code also contains a country code, and matches either
//     *
//     *  - an exact language/country code as configured (ll-CC)
//     *  - a language with a country wildcard (ll-*)
//     *
//     * the code with uppercase country is returned. If only the language part matches
//     * a configured language, that language is returned.
//     *
//     * @param string $code the code to match
//     * @return array of [language, country] where both can be null if no match
//     */
//    protected function matchCode($code)
//    {
//        $language = $code;
//        $country = null;
//        $parts = explode('-', $code);
//        if (count($parts)===2) {
//            $language = $parts[0];
//            $country = strtoupper($parts[1]);
//        }
//
//        if (in_array($code, $this->languages)) {
//            return [$language, $country];
//        } elseif (
//            $country && in_array("$language-$country", $this->languages) ||
//            in_array("$language-*", $this->languages)
//        ) {
//            return [$language, $country];
//        } elseif (in_array($language, $this->languages)) {
//            return [$language, null];
//        } else {
//            return [null, null];
//        }
//    }
//
//    /**
//     * Redirect to the current URL with given language code applied
//     *
//     * @param string $language the language code to add. Can also be empty to not add any language code.
//     */
//    protected function redirectToLanguage($language)
//    {
//        // Examples:
//        // 1) /baseurl/index.php/some/page?q=foo
//        // 2) /baseurl/some/page?q=foo
//        // 3)
//        // 4) /some/page?q=foo
//        if ($this->showScriptName) {
//            // 1) /baseurl/index.php
//            // 2) /baseurl/index.php
//            // 3) /index.php
//            // 4) /index.php
//            $redirectUrl = $this->_request->getScriptUrl();
//        } else {
//            // 1) /baseurl
//            // 2) /baseurl
//            // 3)
//            // 4)
//            $redirectUrl = $this->_request->getBaseUrl();
//        }
//        if ($language) {
//            $redirectUrl .= '/'.$language;
//        }
//        // 1) some/page
//        // 2) some/page
//        // 3)
//        // 4) some/page
//        $pathInfo = $this->_request->getPathInfo();
//        if ($pathInfo) {
//            $redirectUrl .= '/'.$pathInfo;
//        }
//        if ($redirectUrl === '') {
//            $redirectUrl = '/';
//        }
//        // 1) q=foo
//        // 2) q=foo
//        // 3)
//        // 4) q=foo
//        $queryString = $this->_request->getQueryString();
//        if ($queryString) {
//            $redirectUrl .= '?'.$queryString;
//        }
//        Yii::$app->getResponse()->redirect($redirectUrl);
//        if (YII_ENV_TEST) {
//            // Response::redirect($url) above will call `Url::to()` internally. So to really
//            // test for the same final redirect URL here, we need to call Url::to(), too.
//            throw new \yii\base\Exception(\yii\helpers\Url::to($redirectUrl));
//        } else {
//            Yii::$app->end();
//        }
//    }
}
