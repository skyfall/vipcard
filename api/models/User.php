<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $user_name 用户名
 * @property string $user_pass 密码
 * @property int $login_time 最后一次登入时间
 * @property int $create_time 创建时间
 * @property string $auth_key
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'user_pass', 'login_time', 'create_time', 'auth_key'], 'required'],
            [['login_time', 'create_time'], 'integer'],
            [['user_name', 'user_pass', 'auth_key'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'user_pass' => 'User Pass',
            'login_time' => 'Login Time',
            'create_time' => 'Create Time',
            'auth_key' => 'Auth Key',
        ];
    }
}
