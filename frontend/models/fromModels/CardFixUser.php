<?php
namespace frontend\models\fromModels;

use yii\base\Model;
use frontend\models\dataModels\Card;

class CardFixUser extends Model{
	
	public $user_name;
	
	public $user_tel;
	
	public $card_id;
	
	public $card_no;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
				['user_name', 'trim','message'=>'用户名不能为空'],
				['user_name', 'required','message'=>'用户名不能为空'],
				['user_name', 'string', 'length' => [2, 24]],
				
				['card_id', 'trim','message'=>'系统编号不能为空'],
				['card_id', 'required','message'=>'系统编号不能为空'],
				['card_id', 'integer',  'message'=>'系统编号必须是数字'],
				
				['card_no', 'trim','message'=>'卡号不能为空'],
				['card_no', 'required','message'=>'卡号不能为空'],
				['card_no', 'integer',  'message'=>'金额必须是数字'],
				
				['user_tel', 'trim','message'=>'手机号不能为空'],
				['user_tel', 'required','message'=>'手机号不能为空'],
				['user_tel', 'string', 'length' => [11, 11]],

				
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
				'card_no'=>'卡号',
				'user_name' => '用户名',
				'user_tel' => '用户手机号码',
				'card_id'=>'系统编号'
		];
	}
	
	// 修改会员卡
	public function fixUser(){
		if (!$this->validate()) {
			return null;
		}
		/**
		 * @var Card $myCard
		 */
		$myCard = Card::find()->where(['id'=>$this->card_id])->andWhere(['user_id'=>\Yii::$app->user->getId()])->one();
		if (empty($myCard)){
			return null;
		}
		$myCard->card_user_name = $this->user_name;
		$myCard->card_user_tel = $this->user_tel;
		
		return $myCard->save() ? $myCard: null;
	}
}