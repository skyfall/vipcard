<?php
namespace api\controllers;

use api\common\ApiController;
use api\models\CardPayLog;
use api\models\Card;
use frontend\models\fromModels\CardLogin;

class MoneyController extends ApiController{
	
	//查询消费日志
	public function actionGetExpenseLog(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		
		$cardId = isset($this->sendData['cardId']) ? $this->sendData['cardId'] : '';
		$page= isset($this->sendData['page']) ? $this->sendData['page'] : 1;
		$pageSize= isset($this->sendData['pageSize']) ? $this->sendData['pageSize'] : 15;
		$err = [];
		empty($cardId) ? $err['cardId'][] = 'cardId不能为空' : '';
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		$CardPayLogModle = CardPayLog::find()->where(['card_id'=>$cardId])->andWhere(['user_id'=>$this->userController->getUserId()])->andWhere(['type'=>2])
		->limit($pageSize)->offset(($page-1)*$pageSize)
		->orderBy('id desc');
		
		$count = $CardPayLogModle->count();
		
		return $this->requestData->setState(0)->setMessage('success')->setData([
				'list'=>$CardPayLogModle->all(),
				'page'=>$page,
				'pageSize'=>$pageSize,
				'total'=>$count,
				'totalPage'=>ceil($count/$pageSize)
		])->getArray();
		
	}
	
	
	/**
	 * 添加一条消费记录
	 */
	public function actionAddExpenseLog(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		$cardId = isset($this->sendData['cardId']) ? $this->sendData['cardId'] : '';
		$money = isset($this->sendData['money']) ? $this->sendData['money'] : '';
		$err = [];
		empty($cardId) ? $err['cardId'][] = 'cardId不能为空' : '';
		empty($money) ? $err['money'][] = 'money不能为空' : '';
		!is_int($money*1) ? $err['money'][] = 'money必须是数字' : '';
		$money <= 0 ? $err['money'][] = 'money 必须是正数' : '';
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		$Card = Card::findOne(['id'=>$cardId]);
		if (empty($Card)){
			return $this->requestData->setState(100)->setMessage('卡不存在')->setData($err)->getArray();
		}
		if ($Card->user_id != $this->userController->getUserId()){
			return $this->requestData->setState(100)->setMessage('卡不存在')->setData($err)->getArray();
		}
		
		$Transaction = \Yii::$app->db->beginTransaction();
		
		if (!Card::updateAllCounters(['card_money'=>$money*-1],['id'=>$Card->id])){
			$Transaction->rollBack();
			\Yii::error('更新用户金额失败');
			return $this->requestData->setState(100)->setMessage('添加记录失败')->setData([])->getArray();
		}
		
		$Card = Card::findOne(['id'=>$cardId]);
		
		$CardPayLog = new CardPayLog();
		$CardPayLog->create_time = time();
		$CardPayLog->money= $money;
		$CardPayLog->after_mone= $Card->card_money;
		$CardPayLog->card_id= $cardId;
		$CardPayLog->type= 2;
		$CardPayLog->user_id = $this->userController->getUserId();
		if (!$CardPayLog->save()){
			$Transaction->rollBack();
			\Yii::error('添加记录失败 res:'.json_encode($CardPayLog->errors));
			return $this->requestData->setState(100)->setMessage('添加记录失败')->setData($CardPayLog->errors)->getArray();
		}
		$Transaction->commit();
		return $this->requestData->setState(0)->setMessage('success')->setData([])->getArray();
	}

	// 添加重置记录
	public function actionAddPayLog(){
		if (!$this->userController->getUserId()){
			return $this->requestData->setState(1000)->setMessage('请重新登入')->getArray();
		}
		$cardId = isset($this->sendData['cardId']) ? $this->sendData['cardId'] : '';
		$money = isset($this->sendData['money']) ? $this->sendData['money'] : '';
		$err = [];
		empty($cardId) ? $err['cardId'][] = 'cardId不能为空' : '';
		empty($money) ? $err['money'][] = 'money不能为空' : '';
		!is_int($money*1) ? $err['money'][] = 'money必须是数字' : '';
		$money <= 0 ? $err['money'][] = 'money 必须是正数' : '';
		if (!empty($err)){
			return $this->requestData->setState(100)->setMessage('参数错误')->setData($err)->getArray();
		}
		
		$Card = Card::findOne(['id'=>$cardId]);
		if (empty($Card)){
			return $this->requestData->setState(100)->setMessage('卡不存在')->setData($err)->getArray();
		}
		if ($Card->user_id != $this->userController->getUserId()){
			return $this->requestData->setState(100)->setMessage('卡不存在')->setData($err)->getArray();
		}
		
		$Transaction = \Yii::$app->db->beginTransaction();
		
		if (!Card::updateAllCounters(['card_money'=>$money],['id'=>$Card->id])){
			$Transaction->rollBack();
			\Yii::error('更新用户金额失败');
			return $this->requestData->setState(100)->setMessage('添加记录失败')->setData([])->getArray();
		}
		
		$Card = Card::findOne(['id'=>$cardId]);
		
		$CardPayLog = new CardPayLog();
		$CardPayLog->create_time = time();
		$CardPayLog->money= $money;
		$CardPayLog->after_mone= $Card->card_money;
		$CardPayLog->card_id= $cardId;
		$CardPayLog->type= 1;
		$CardPayLog->user_id = $this->userController->getUserId();
		if (!$CardPayLog->save()){
			$Transaction->rollBack();
			\Yii::error('添加记录失败 res:'.json_encode($CardPayLog->errors));
			return $this->requestData->setState(100)->setMessage('添加记录失败')->setData($CardPayLog->errors)->getArray();
		}
		$Transaction->commit();
		return $this->requestData->setState(0)->setMessage('success')->setData([])->getArray();
	}
}