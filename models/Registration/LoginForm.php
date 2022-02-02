<?php

namespace app\models\Registration;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $username;
    public $password;


    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['password', 'required'],
            ['password', 'validatePassword']
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = User::findByUsername($this->username);
            return Yii::$app->user->login($user);
        }
        return false;
    }

    public function validatePassword($attribute, $params)
    {
        $user = User::findByUsername($this->username);
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError($attribute, 'Incorrect password');

        }
    }
}