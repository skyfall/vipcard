<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "card_pay_log".
 *
 * @property int $id
 * @property int $money 交易金额 单位分
 * @property int $after_mone 交易后金额 单位分
 * @property int $card_id 卡id
 * @property int $user_id 用户id
 * @property int $create_time 创建时间
 * @property int $type 1表示充值 2表示消费
 */
class CardPayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_pay_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'after_mone', 'card_id', 'user_id', 'create_time', 'type'], 'required'],
            [['money', 'after_mone', 'card_id', 'user_id', 'create_time', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => 'Money',
            'after_mone' => 'After Mone',
            'card_id' => 'Card ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'type' => 'Type',
        ];
    }
}
