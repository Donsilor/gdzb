<?php

namespace addons\Sales\common\models;

use Yii;
use common\models\backend\Member;

/**
 * This is the model class for table "sales_express".
 *
 * @property int $id ID
 * @property string $code 快递编码(暂时不用)
 * @property string $cover 快递公司logo
 * @property string $name 快递名称
 * @property string $express_man 快递联系人
 * @property string $express_phone 快递联系人电话
 * @property string $company_man 公司联系人
 * @property string $company_phone 公司联系人电话
 * @property string $settlement_way 结算方式
 * @property string $settlement_period 结算周期
 * @property string $settlement_account 结算账户
 * @property string $delivery_scope 配送范围
 * @property string $receive_time 收件时间
 * @property int $stop_receive_time 暂停收件时间
 * @property string $stop_receive_area 暂停配送区域
 * @property int $start_receive_time 恢复收件时间
 * @property string $pact_file 合同文件
 * @property string $cert_file 资质文件
 * @property int $auditor_id 审核人
 * @property int $audit_status 审核状态
 * @property int $audit_time 审核时间
 * @property string $audit_remark 审核备注
 * @property string $remark 备注
 * @property int $status 状态 1启用 0禁用
 * @property int $sort 排序
 * @property int $creator_id 创建人
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Express extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return self::tableFullName('express');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['auditor_id', 'audit_status', 'audit_time', 'status', 'sort', 'creator_id', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 25],
            [['cover', 'settlement_account', 'receive_time'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 50],
            [['express_man', 'express_phone', 'company_man', 'company_phone'], 'string', 'max' => 30],
            [['stop_receive_area', 'pact_file', 'cert_file', 'audit_remark', 'remark'], 'string', 'max' => 255],
            [['settlement_way'], 'parseSettlementWay'],
            [['settlement_period'], 'parseSettlementPeriod'],
            [['delivery_scope'], 'parseDeliveryScope'],
            [['stop_receive_time', 'start_receive_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '快递编码(暂时不用)',
            'cover' => '快递公司logo',
            'name' => '快递名称',
            'express_man' => '快递联系人',
            'express_phone' => '快递联系人电话',
            'company_man' => '公司联系人',
            'company_phone' => '公司联系人电话',
            'settlement_way' => '结算方式',
            'settlement_period' => '结算周期',
            'settlement_account' => '结算账户',
            'delivery_scope' => '配送范围',
            'receive_time' => '收件时间',
            'stop_receive_time' => '暂停收件时间',
            'stop_receive_area' => '暂停配送区域',
            'start_receive_time' => '恢复收件时间',
            'pact_file' => '合同文件',
            'cert_file' => '资质文件',
            'auditor_id' => '审核人',
            'audit_status' => '审核状态',
            'audit_time' => '审核时间',
            'audit_remark' => '审核备注',
            'remark' => '备注',
            'status' => '状态',
            'sort' => '排序',
            'creator_id' => '创建人',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->creator_id = Yii::$app->user->identity->getId();
        }
        return parent::beforeSave($insert);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::afterValidate()
     */
    public function afterValidate()
    {
        if(!is_numeric($this->stop_receive_time)) {
            $this->setAttribute('stop_receive_time' , $this->stop_receive_time ? strtotime($this->stop_receive_time):0);
        }
        if(!is_numeric($this->start_receive_time)) {
            $this->setAttribute('start_receive_time' , $this->start_receive_time ? strtotime($this->start_receive_time):0);
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * 结算方式
     */
    public function parseSettlementWay()
    {
        if(is_array($this->settlement_way)){
            $this->settlement_way = ','.implode(',',$this->settlement_way).',';
        }
        return $this->settlement_way;
    }

    /**
     * 结算周期
     */
    public function parseSettlementPeriod()
    {
        if(is_array($this->settlement_period)){
            $this->settlement_period = ','.implode(',',$this->settlement_period).',';
        }
        return $this->settlement_period;
    }

    /**
     * 配送范围
     */
    public function parseDeliveryScope()
    {
        if(is_array($this->delivery_scope)){
            $this->delivery_scope = ','.implode(',',$this->delivery_scope).',';
        }
        return $this->delivery_scope;
    }

    /**
     * 关联管理员一对一
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id'=>'creator_id'])->alias('member');
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
     * 审核人
     * @return \yii\db\ActiveQuery
     */
    public function getAuditor()
    {
        return $this->hasOne(Member::class, ['id'=>'auditor_id'])->alias('auditor');
    }
}
