<?php

namespace frontend\models\fromModels;

use yii\base\Model;
use frontend\models\dataModels\Card;

class CardAdd extends Model {
	
	public $cardNo;
	
	public $cardUserName;
	
	public $cardTel;
	
	public $cardMoney;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
				['cardNo', 'trim','message'=>'卡号不能为空'],
				['cardNo', 'required','message'=>'卡号不能为空'],
				['cardNo', 'integer', 'message'=>'卡号必须是数字'],
				['cardNo', 'string', 'length' => [4, 24]],
				
				
				['cardUserName', 'trim','message'=>'用户名不能为空'],
				['cardUserName', 'required','message'=>'用户名不能为空'],
				['cardUserName', 'string', 'length' => [4, 24]],
				
				['cardTel', 'trim','message'=>'手机号不能为空'],
				['cardTel', 'required','message'=>'手机号不能为空'],
				['cardTel', 'string', 'length' => [11, 11]],
				
				['cardMoney', 'integer',  'message'=>'金额必须是数字'],
				['cardMoney', 'required',  'message'=>'金额不能为空'],
				
// 				[['cardNo', 'card_no'], 'unique', 'targetAttribute' => ['user_id', 'card_no']],
// 				['cardNo', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'cardNo' => '卡号',
				'cardUserName' => '用户名',
				'cardTel' => '用户手机号码',
				'cardMoney' => '余额',
		];
	}
	
	// 添加会员卡
	public function addCard(){
		if (!$this->validate()) {
			return null;
		}
		$Mycard = new Card();
		$Mycard->user_id = \Yii::$app->user->getId();
		$Mycard->card_no = $this->cardNo;
		$Mycard->card_money = $this->cardMoney;
		$Mycard->card_user_name = $this->cardUserName;
		$Mycard->card_user_tel = $this->cardTel;
		$Mycard->create_time = time();
		$Mycard->update_time = time();
		
		return $Mycard->save() ? $Mycard: null;
	}
}

