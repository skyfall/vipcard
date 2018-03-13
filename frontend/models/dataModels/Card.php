<?php

namespace frontend\models\dataModels;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $card_no 卡号
 * @property string $card_user_name 纸卡人姓名
 * @property string $card_user_tel 纸卡人电话
 * @property int $card_money 卡余额  单位分
 * @property int $create_time 创建时间
 * @property int $user_id user表的id
 * @property string $mark 备注
 * @property int $update_time 最后更新时间
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_no', 'card_user_name', 'card_user_tel', 'card_money', 'create_time', 'user_id', 'update_time'], 'required'],
            [['card_money', 'create_time', 'user_id', 'update_time'], 'integer'],
            [['card_no', 'card_user_name'], 'string', 'max' => 45],
            [['card_user_tel'], 'string', 'max' => 12],
            [['mark'], 'string', 'max' => 255],
            [['user_id', 'card_no'], 'unique', 'targetAttribute' => ['user_id', 'card_no']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no' => 'Card No',
            'card_user_name' => 'Card User Name',
            'card_user_tel' => 'Card User Tel',
            'card_money' => 'Card Money',
            'create_time' => 'Create Time',
            'user_id' => 'User ID',
            'mark' => 'Mark',
            'update_time' => 'Update Time',
        ];
    }
}
