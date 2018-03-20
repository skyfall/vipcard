<?php
namespace api\controllers;

use api\common\ApiController;
use api\models\Card;
use api\models\User;

class CardController extends ApiController{
	// 添加卡
	public function actionAddCard(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		
		$cardNo = isset($this->sendData['cardNo']) ? $this->sendData['cardNo'] : '';
		$cardUserName= isset($this->sendData['cardUserName']) ? $this->sendData['cardUserName'] : '';
		$cardTel = isset($this->sendData['cardTel']) ? $this->sendData['cardTel'] : '';
		$cardMoney= isset($this->sendData['cardMoney']) ? $this->sendData['cardMoney'] : '';
		
		$err = [];
		empty($cardNo) ? $err['cardNo'][] = 'cardNo不能为空' : '';
		empty($cardUserName) ? $err['cardUserName'][] = 'cardUserName不能为空' : '';
		empty($cardTel) ? $err['cardTel'][] = 'cardTel不能为空' : '';
		empty($cardMoney) ? $err['cardMoney'][] = 'cardMoney不能为空' : '';
		!is_int($cardMoney*1) ? $err['cardMoney'][] = 'cardMoney 必须是数字' : '';
		
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		if (!User::findOne(['id'=>$this->userController->getUserId()])){
			return $this->requestData->setState(101)->setMessage('用户异常')->setData($err)->getArray();
		}
		
		if (Card::findOne(['card_no'=>$cardNo,'user_id'=>$this->userController->getUserId()])){
			return $this->requestData->setState(101)->setMessage('该卡号已经存在不能重复创建')->setData($err)->getArray();
		}
		
		$Card = new Card();
		$Card->user_id = $this->userController->getUserId();
		$Card->card_no= $cardNo;
		$Card->card_user_name= $cardUserName;
		$Card->card_user_tel= $cardTel;
		$Card->card_money= $cardMoney;
		$Card->create_time= time();
		$Card->update_time= time();
		if (!$Card->save()){
			\Yii::error('添加卡失败 res:'.json_encode($Card->errors));
			return $this->requestData->setState(101)->setMessage('添加卡号失败')->setData($Card->errors)->getArray();
		}
		return $this->requestData->setState(0)->setMessage('success')->setData([])->getArray();
		
	}

	// 获取卡列表
	public function actionCardList(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		
		$page= isset($this->sendData['page']) ? $this->sendData['page'] : 1;
		$pageSize= isset($this->sendData['pageSize']) ? $this->sendData['pageSize'] : 15;
		
		$offset = ($page-1)*$pageSize;
		$cardModle = Card::find()->where(['user_id'=>$this->userController->getUserId()]);
		$count = $cardModle->count();
		
		$cardList =$cardModle->orderBy('id desc')->limit($pageSize)->offset($offset)->asArray()->all();
		
		
		return $this->requestData->setState(0)->setMessage('success')->setData(['list'=>$cardList,'page'=>$page,'pageSize'=>$pageSize,'total'=>$count,'totalPage'=>ceil($count/$pageSize)])->getArray();
		
	}
	
	//获取卡详情
	public function actionCardInf(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		$cardId = isset($this->sendData['cardId']) ? $this->sendData['cardId'] : '';
		$err = [];
		empty($cardId) ? $err['cardId'][] = 'cardId不能为空' : '';
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		$card = Card::findOne(['id'=>$cardId]);
		if (empty($card)){
			return $this->requestData->setState(101)->setMessage('卡不存在')->setData([])->getArray();
		}
		
		if ($card->user_id != $this->userController->getUserId()){
			return $this->requestData->setState(101)->setMessage('卡不存在')->setData([])->getArray();
		}
		
		return $this->requestData->setState(0)->setMessage('success')->setData($card->toArray())->getArray();
		
	}
}