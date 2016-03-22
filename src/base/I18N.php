<?php

namespace hipanel\base;

class I18N extends \yii\i18n\I18N
{
    /**
     * Removes wrapper `{Lang:text}` that is returned by API
     * {@inheritdoc}
     */
    public function translate($category, $message, $params, $language)
    {
        if (stripos($message, '{lang:') !== false) {
            $message = $this->removeLegacyLangTags($message);
        }

        return parent::translate($category, $message, $params, $language);
    }

    /**
     * Unwraps `{Lang:message}`
     * @param $message
     * @return string
     */
    protected function removeLegacyLangTags($message)
    {
        return preg_replace('/{lang:([^}]*)}/i', '$1', $message);
    }
}
