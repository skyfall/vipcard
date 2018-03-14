<?php

namespace frontend\models\fromModels;

use yii\base\Model;
use frontend\models\dataModels\User;


class CardLogin  extends Model{
	
	public $username;
	
	public $password;
	
	private $_user;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
				['username', 'trim','message'=>'用户名不能为空'],
				['username', 'required','message'=>'用户名不能为空'],
// 				['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
				['username', 'string', 'length' => [4, 24],'message'=>'用户名必须在2到255位之间'],
				
// 				['email', 'trim'],
// 				['email', 'required'],
// 				['email', 'email'],
// 				['email', 'string', 'max' => 255],
// 				['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
				
				['password', 'required','message'=>'密码不能为空'],
				['password', 'string', 'length' => [4, 24],'message'=>'密码必须大于6位'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'username' => '用户名',
				'password' => '密码',
		];
	}
	
	// 登入
	public function login(){
		if (!$this->validate()){
			return false;
		}
		
		$user = $this->getUser();
		if ($user->id) {
			return \Yii::$app->user->login($user, 3600 * 24 * 30 );
		}
		$this->addError('password','用户名或者密码错误');
		return false;
	}
	
	
	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	protected function getUser()
	{
		if ($this->_user === null) {
			$userInf = User::findByUsername($this->username);
			$this->_user = $userInf->user_pass == $this->password ? $userInf : new User();
		}
		
		return $this->_user;
	}
}

