<?php

namespace addons\Gdzb\common\models;

use Yii;

/**
 * This is the model class for table "gdzb_client.
 *
 * @property int $id
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
        return self::tableFullName('client');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'creator_id', 'created_at', 'updated_at'], 'integer'],
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
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
