<?php

namespace frontend\models\fromModels;

use yii\base\Model;
use frontend\models\dataModels\CardPayList;
use frontend\models\dataModels\Card;
use frontend\models\dataModels\CardPayLog;

class CardPay extends Model {
	public $card_id;
	
	public $money;
	
	public $card_no;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
				['card_id', 'trim','message'=>'编号不能为空'],
				['card_id', 'required','message'=>'编号不能为空'],
				['card_id', 'integer', 'message'=>'编号必须是数字'],
				
				
				['card_no', 'trim','message'=>'卡号不能为空'],
				['card_no', 'required','message'=>'卡号不能为空'],
				['card_no', 'integer', 'message'=>'卡号必须是数字'],
				['card_no', 'string', 'length' => [4, 24]],
				
				
				['money' , 'checkmoney' , 'skipOnEmpty' => false],
// 				['money', 'integer',  'message'=>'金额必须是数字'],
				['money', 'required',  'message'=>'消费金额不能为空'],
				
				// 				[['cardNo', 'card_no'], 'unique', 'targetAttribute' => ['user_id', 'card_no']],
				// 				['cardNo', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
		];
	}
	
	public function checkmoney(){
		if (!is_numeric($this->money)){
			$this->addError('money','请输入消费金额');
			return false;
		}
		$this->money = intval($this->money*100*-1);
		if (empty($this->money)){
			$this->addError('money','消费金额必须为2位小数');
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'card_no' => '卡号',
				'card_id' => '系统编号',
				'money' => '消费金额',
		];
	}
	
	public function addPayMoney(){
		if (!$this->validate()) {
			\Yii::error('验证数据错误');
			return null;
		}
		
		// 查询卡是否属于该用户
		$userId = \Yii::$app->user->getId();
		
		$CardInf = Card::find()->where(['user_id'=>$userId])->where(['id'=>$this->card_id])->where(['card_no'=>$this->card_no])->one();
		if (empty($CardInf)){
			\Yii::error('查询卡失败');
			$this->addError('card_no','查询卡失败');
			return null;
		}
		// 开启事物
		$Transaction = \Yii::$app->db->beginTransaction();
		if (!Card::updateAllCounters(['card_money'=>$this->money],['id'=>$CardInf->id])){
			$Transaction->rollBack();
			\Yii::error('更新卡金额失败');
			$this->addError('card_money','更新卡金额失败');
			return null;
		}
		
		$CardInfEnd = Card::findOne(['id'=>$this->card_id]);
		$cardPayLog = new CardPayLog();
// 		$CardAddListModle = new CardPayList();
		$cardPayLog->money = $this->money;
		$cardPayLog->user_id= $userId;
		$cardPayLog->card_id= $this->card_id;
		
		$cardPayLog->create_time= time();
		$cardPayLog->after_mone = $CardInfEnd->card_money;
		$cardPayLog->type = 2;
		if (!$cardPayLog->save()){
			\Yii::error('添加记录失败');
			$this->addErrors($cardPayLog->errors);
			$Transaction->rollBack();
			return null;
		}
		$Transaction->commit();
		return true;
	}
}

