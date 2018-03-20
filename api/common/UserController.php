<?php

namespace api\common;

use yii\base\BaseObject;

class UserController extends BaseObject{
	
	private $user_id;
	
	public function init(){
		$this->user_id = \Yii::$app->request->cookies->getValue('_userId','');
	}
	
	public function setUserId(int $UserId){
// 		\Yii::$app->request->cookies->readOnly = false;
		\Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => '_userId',
				'value' => $UserId
		]));
		
		$this->user_id = \Yii::$app->request->cookies->getValue('_userId','');
	}
	
	public function getUserId(){
		return $this->user_id;
	}
}

