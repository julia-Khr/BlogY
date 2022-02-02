<?php

namespace app\models\Registration;

use Yii;
use yii\base\Model;
use app\models\User;
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username', 'email'], 'trim'],

            ['username', 'string', 'min'=> 2, 'max' => 45],
            [['username'], 'unique', 'targetClass' => User::className()],

            ['email', 'string', 'min'=> 10, 'max'=> 45],
            [['email'], 'unique', 'targetClass' => User::className()],

            ['password', 'string', 'min' => 6],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->username = $this->username;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);

            if ($user->save()) {
                return $user;
            }

        }
    }
}