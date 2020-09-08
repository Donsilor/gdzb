<?php

namespace addons\Gdzb\common\models;

use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "gdzb_special.
 *
 * @property int $id
 * @property string $title 专题名称
 * @property string $url 专题URL
 * @property int $creator_id 创建人ID
 * @property int $status 状态：0禁用，1启用
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Special extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return self::tableFullName('special');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'creator_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 45],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '专题名称',
            'url' => '专题URL',
            'creator_id' => '创建人ID',
            'status' => '状态',
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

    /**
     * 推广客户列表
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasMany(Client::class, ['special_id'=>'id']);
    }

    /**
     * 推广数据列表
     * @return \yii\db\ActiveQuery
     */
    public function getPromotional()
    {
        return $this->hasMany(Promotional::class, ['special_id'=>'id']);
    }
}
