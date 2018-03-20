<?php

namespace api\controllers;

use api\common\ApiController;
use api\models\User;

class UserController extends ApiController {
	
	/**
	 * 登入接口
	 * @return number[]|\api\common\int[]|string[]|\api\common\string[]|array[]|unknown[]
	 */
	public function actionLogin(){
		if ($this->userController->getUserId()){
			return $this->requestData->setState(101)->setMessage('已经登入')->getArray();
		}
		$userName = isset($this->sendData['userName']) ? $this->sendData['userName'] : '';
		$userPass = isset($this->sendData['userPass']) ? $this->sendData['userPass'] : '';
		$err = [];
		empty($userName) ? $err['userName'][] = 'userName 不能为空' : '';
		empty($userPass) ? $err['userPass'][] = 'userPass 不能为空' : '';
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		if (!$User = User::findOne(['user_name'=>$userName])){
			return $this->requestData->setState(101)->setMessage('用户名或者密码错误')->setData($err)->getArray();
		}
		if ($User->user_pass != $userPass){
			return $this->requestData->setState(101)->setMessage('用户名或者密码错误')->setData($err)->getArray();
		}
		
		
		
		$this->userController->setUserId($User->id);
		return $this->requestData->setState(0)->setMessage('success')->setData([])->getArray();
	}
	
	
	
	
}

