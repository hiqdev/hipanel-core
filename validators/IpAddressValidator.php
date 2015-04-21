<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * IpAddressValidator checks attribute value to be an IP address, IP subnet, or list of them.
 */
class IpAddressValidator extends Validator
{
    public $enableClientValidation = true;

    /**
     * @var string the user-defined error message.
     */
    public $message;

    /**
     * @var bool whether support of IPv6 addresses is enabled
     */
    public $v6 = true;

    /**
     * @var bool whether support of IPv4 addresses is enabled
     */
    public $v4 = true;

    /**
     * @var bool|string whether user is allowed to use subnet
     * string value 'only' determinate to validate only subnets
     */
    public $subnet = true;

    public $exclusion = false;

    public $ignoreArray = false;

    public $expandV6 = false;

    public $normalize = false;

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        if ($this->message === null) {
            if ($this->subnet) {
                $this->message = Yii::t('app', '{ip} is not a valid IP subnet');
            } else {
                $this->message = Yii::t('app', '{ip} is not a valid IP address');
            }
        }

        if (!$this->v4 && !$this->v6) throw new InvalidConfigException('You can not disable both IPv4 and IPv6 checks');
    }

    /**
     * @inheritdoc
     */
    protected function validateValue ($value) {
        $result = $this->validateSubnet($value);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute ($model, $attribute) {
        $value = $model->$attribute;

        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as &$item) {
            $result = $this->validateValue($item);
            if (empty($result)) {
                $this->addError($model, $attribute, $this->message, ['ip' => $item]);
                break;
            }
            $item = $result;
        }

        $model->$attribute = $value;
    }

    protected function validateSubnet ($ip) {
        if (preg_match("/^(!?)(.+?)(\/(\d+))?$/", $ip, $matches)) {
            $exclude = $matches[1];
            $ip      = $matches[2];
            $prefix  = $matches[4];
        }

        if (!$this->subnet && isset($prefix)) return false;
        if (!$this->exclusion && !empty($exclude)) return false;

        if (preg_match('/:/', $ip)) {
            if (isset($prefix)) {
                if (($prefix > 128) || ($prefix < 0)) return false;
            } elseif ($this->normalize) {
                $prefix = 128;
            }

            $ip = $this->validate6($ip);
            if (!$ip) return false;

            if ($this->expandV6) {
                $hex = unpack("H*hex", inet_pton($ip));
                $ip  = substr(preg_replace("/([a-f0-9]{4})/i", "$1:", $hex['hex']), 0, -1);
            }
        } else {
            if (isset($prefix)) {
                if (($prefix > 32) || ($prefix < 0)) return false;
            } elseif ($this->normalize) {
                $prefix = 32;
            }

            $ip = $this->validate4($ip);
        }

        if (empty($ip)) return false;

        $result = $exclude . $ip;

        if ($this->subnet) {
            $result .= '/' . $prefix;
        }

        return $result;
    }

    protected function validate4 ($value) {
        if (!$this->v4) return false;

        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ?: false;
    }

    protected function validate6 ($value) {
        if (!$this->v6) return false;

        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ?: false;
    }
}
