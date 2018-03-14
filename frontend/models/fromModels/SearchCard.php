<?php

namespace frontend\models\fromModels;

use yii\base\Model;
use frontend\models\dataModels\Card;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class SearchCard extends Model {
	
	public $card_no;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'card_no' => '卡号',
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
				
				
// 				['card_no', 'trim','message'=>'卡号不能为空'],
// 				['card_no', 'required','message'=>'卡号不能为空'],
// 				['card_no', 'string', 'length' => [2, 24]],
				
				// 				[['cardNo', 'card_no'], 'unique', 'targetAttribute' => ['user_id', 'card_no']],
				// 				['cardNo', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
		];
	}
	
	public function sreach( ActiveQuery &$cardModle){

		if (!$this->validate()){
			return $cardModle;
		}
		if (!empty($this->card_no)){
			$cardModle->andWhere(['like','card_no',$this->card_no]);
		}
	
		return  $cardModle;
	}
	
	
}

