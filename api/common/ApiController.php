<?php

namespace api\common;

use Yii;
use yii\web\Controller;

class ApiController extends Controller {
	
	public $enableCsrfValidation = FALSE;
	
	/**
	 * @var UserController $userController
	 */
	public $userController;
	
	public $sendData = [];
	
	
	/**
	 * @var RequestData $requestData
	 */
	public $requestData ;
	
	public function init(){
		$str  = file_get_contents('php://input');
		$this->sendData = json_decode($str,true);
		
		$this->userController = \Yii::$app->userCheck;
		
		$this->requestData= new RequestData();
		
		$headers = Yii::$app->response->headers;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		// 增加一个 Pragma 头，已存在的Pragma 头不会被覆盖。
		$headers->add('Access-Control-Allow-Methods', 'POST');
		$headers->add('Access-Control-Allow-Headers', 'accept, content-type');
		$Origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
		$headers->add('Access-Control-Allow-Origin', $Origin);
		$headers->add('Access-Control-Allow-Credentials', 'true');
	}
	
}

