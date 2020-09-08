<?php

namespace addons\Gdzb\common\models;

use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "gdzb_promotional.
 *
 * @property int $id
 * @property double $budget_cost 预算
 * @property double $actual_cost 实际费用
 * @property int $show_times 展现次数
 * @property int $hits_times 点击次数
 * @property int $visit_length 访问时长
 * @property int $dialogue_times 对话数量
 * @property int $client_times 客户数量
 * @property int $order_times 订单数量
 * @property int $creator_id 创建人ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Promotional extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return self::tableFullName('promotional');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'show_times', 'hits_times', 'visit_length', 'dialogue_times', 'client_times', 'order_times ', 'creator_id', 'created_at', 'updated_at'], 'integer'],
            [['budget_cost', 'actual_cost'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'budget_cost' => '预算',
            'actual_cost' => '实际费用',
            'show_times' => '展现次数',
            'hits_times' => '点击次数',
            'visit_length' => '访问时长',
            'dialogue_times' => '对话数量',
            'client_times' => '客户数量',
            'order_times' => '订单数量',
            'creator_id' => '创建人ID',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 创建人
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Member::class, ['id'=>'creator_id'])->alias('creator');
    }
}
