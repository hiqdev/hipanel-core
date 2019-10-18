<?php


namespace hipanel\components;


use hiqdev\hiart\Exception;
use Yii;

/**
 * Class I18nResponseErrorFormatter
 * @package hipanel\components
 */
class I18nResponseErrorFormatter implements I18nResponseErrorFormatterInterface
{
    /**
     * @var string
     */
    protected $dictionary;

    /**
     * I18nResponseErrorFormatter constructor.
     * @param string $dictionary
     */
    public function __construct(string $dictionary = 'hipanel')
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(Exception $exception): string
    {
        $responseData = $exception->getResponseData();
        $message = $exception->getMessage();
        if (!empty($responseData['_error_ops'])) {
            $message = Yii::t($this->dictionary, $message, $responseData['_error_ops']);
        } else {
            $message = Yii::t($this->dictionary, $message);
        }
        return $message;
    }
}
