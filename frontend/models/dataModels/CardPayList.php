<?php

namespace frontend\models\dataModels;

use Yii;

/**
 * This is the model class for table "card_pay_list".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $card_id 卡编号
 * @property int $pay_money 支付余额
 * @property int $after_mone 支付后余额
 * @property int $create_time 创建时间
 */
class CardPayList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_pay_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'card_id', 'pay_money', 'after_mone', 'create_time'], 'required'],
            [['user_id', 'card_id', 'pay_money', 'after_mone', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'card_id' => 'Card ID',
            'pay_money' => 'Pay Money',
            'after_mone' => 'After Mone',
            'create_time' => 'Create Time',
        ];
    }
}
