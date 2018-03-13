<?php

namespace frontend\models\dataModels;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $user_name 用户名
 * @property string $user_pass 密码
 * @property int $login_time 最后一次登入时间
 * @property int $create_time 创建时间
 * @property string $auth_key auth_key
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
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
            [['user_name', 'user_pass', 'login_time', 'create_time','auth_key'], 'required'],
            [['login_time', 'create_time'], 'integer'],
        	[['user_name', 'user_pass','auth_key'], 'string', 'max' => 45],
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
        	'auth_key' =>"auth_key"
        ];
    }
    
   /**
    * Finds an identity by the given ID.
    * @param string|int $id the ID to be looked for
    * @return IdentityInterface the identity object that matches the given ID.
    * Null should be returned if such an identity cannot be found
    * or the identity is not in an active state (disabled, deleted, etc.)
    */
    public static function findIdentity($id){
    	return static::findOne(['id' => $id]);
    }
    
    
    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null){
    	throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId(){
    	return $this->getPrimaryKey();
    }
    
    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey(){
    	return $this->auth_key;
    }
    
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
    	$this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey){
    	return $this->auth_key;
    }
    
    public static function findByUsername($username){
    	return static::findOne(['user_name' => $username]);
    }
}
