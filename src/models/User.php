<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * User model.
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $login
 * @property string $roles
 * @property string $status
 * @property string $auth_key
 * @property string $password write-only password
 * @property string $password_hash
 * @property string $password_reset_token
 */
class User extends Model implements IdentityInterface
{
    public $id;
    public $email;
    public $username;
    public $type;
    public $roles;
    public $seller;
    public $seller_id;
    public $last_name;
    public $first_name;

    public $auth_key;
    public $password_hash;

    private static $_users = [];

    const TYPE_CLIENT   = 'client';
    const TYPE_ADMIN    = 'admin';
    const TYPE_MANAGER  = 'manager';
    const TYPE_RESELLER = 'reseller';
    const TYPE_OWNER    = 'owner';

    public function save()
    {
        static::$_users[$this->id] = $this;
        Yii::$app->session->set('identity:' . $this->id, $this);
    }

    public static function findOne($id)
    {
        if (isset(static::$_users[$id])) {
            return static::$_users[$id];
        }

        return Yii::$app->session->get('identity:' . $id);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],
        ];
    }

    /** {@inheritdoc} */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /** {@inheritdoc} */
    public function getAccessToken()
    {
        $client = Yii::$app->authClientCollection->getClient();
        $token = $client->getAccessToken();

        return $token ? $token->getParam('access_token') : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid.
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function is($key)
    {
        return (int)$this->id === (int)$key || (string)$this->username === (string)$key;
    }

    public function not($key)
    {
        return (int)$this->id !== (int)$key && (string)$this->username !== (string)$key;
    }

    public function getLogin()
    {
        return $this->username;
    }

    public function getName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return 'DUMMY';
        //return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = 'DUMMY';
        //$this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
