<?php

namespace addons\Gdzb\common\models;

use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "gdzb_client.
 *
 * @property int $id
 * @property string $nickname 姓名
 * @property string $sex 姓别
 * @property string $phone 电话
 * @property string $qq 微信/QQ
 * @property string $area 地域
 * @property string $intention 意向
 * @property string $budget 预算
 * @property int $creator_id 创建人ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $special_id 专题ID
 */
class Client extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return self::tableFullName('client');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'special_id', 'creator_id', 'created_at', 'updated_at'], 'integer'],
            [['nickname','sex','phone','qq','area','intention','budget'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => '姓名',
            'sex' => '姓别',
            'phone' => '电话',
            'qq' => '微信/QQ',
            'area' => '地域',
            'intention' => '意向',
            'budget' => '预算',
            'creator_id' => '',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'special_id' => '专题ID'
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

    /**
     * 专题
     * @return \yii\db\ActiveQuery
     */
    public function getSpecial()
    {
        return $this->hasOne(Special::class, ['id'=>'special_id']);
    }
}
