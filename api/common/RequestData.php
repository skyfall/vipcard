<?php

namespace api\common;

use yii\base\BaseObject;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class RequestData extends BaseObject {
	
	// 0成功  100表示参数错误   1000表示未登入
	public $state = 0;
	
	public $message = '';
	
	public $data = [];
	
	public function setState(int $state){
		$this->state = $state;
		return $this;
	}
	
	public function setMessage(string $message){
		$this->message = $message;
		return $this;
	}
	
	public function setData(array $data){
		$this->data = $data;
		return $this;
	}
	
	public function getArray(){
		return [
				'state'=>$this->state,
				'message'=>$this->message,
				'data'=>$this->data
		];
	}
}

