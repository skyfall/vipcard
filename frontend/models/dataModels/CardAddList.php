<?php

namespace frontend\models\dataModels;

use Yii;

/**
 * This is the model class for table "card_add_list".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $card_id 卡的系统编号
 * @property int $add_money 充值金额 单位分
 * @property int $after_mone 充值后金额
 * @property int $create_time 创建时间
 */
class CardAddList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_add_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'card_id', 'add_money', 'after_mone', 'create_time'], 'required'],
            [['user_id', 'card_id', 'add_money', 'after_mone', 'create_time'], 'integer'],
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
            'add_money' => 'Add Money',
            'after_mone' => 'After Mone',
            'create_time' => 'Create Time',
        ];
    }
}
