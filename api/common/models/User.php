<?php

namespace api\common\models;

use yii\redis\ActiveRecord;
use yii\web\IdentityInterface;
use api\common\models\Task;
use yii\base\NotSupportedException;


class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @return array
     */
	public function attributes()
    {
        return ['id', 'registration_date', 'auth_key'];
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['user_id' => 'id']);
    }

    /**
     * @param  string $token
     * @param  string $type
     * @return self
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @param  integer $id
     * @throws NotSupportedException
     */
    public static function findIdentity($id)
    {
        throw new NotSupportedException('"findIdentity" is not implemented111.');
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param  string
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
