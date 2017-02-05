<?php

namespace api\modules\v1\models;

use api\common\models\User as BaseUser;

class User extends BaseUser
{
    /**
     * @return boolean
     */
    public function beforeValidate()
    {
        if ($this->getIsNewRecord()) {
            $this->auth_key = $this->generateAuthKey();
        }
        return parent::beforeValidate();
    }

    /**
     * @return string
     */
    protected function generateAuthKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
