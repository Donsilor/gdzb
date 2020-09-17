<?php

namespace addons\Gdzb\common\forms;

use Yii;
use common\helpers\ArrayHelper;
use addons\Gdzb\common\models\Order;


/**
 * 订单 Form
 */
class OrderForm extends Order
{

    public $country_id;
    public $province_id;
    public $city_id;
    public $address;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['channel_id','warehouse_id','customer_name','customer_mobile','customer_weixin','collect_type','collect_no','supplier_id'], 'required'],
            [['country_id','province_id','city_id'],'integer'],
            [['address'], 'string', 'max' => 100],
        ];
        return ArrayHelper::merge(parent::rules(),$rules);
    }    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        //合并
        return ArrayHelper::merge(parent::attributeLabels() , [
            'country_id' => '国家',
            'province_id' => '省',
            'city_id' => '市',
            'address' => '地址',
        ]);
    }




    public function setConsigneeInfo($post){
        return json_encode([
            'country_id' => $post['country_id'],
            'province_id' => $post['province_id'],
            'city_id' => $post['city_id'],
            'address' => $post['address']
        ]);
    }

    public function getConsigneeInfo(&$model){
        $consignee_info = json_decode($model->consignee_info,true);
        $model->country_id = $consignee_info['country_id'];
        $model->province_id = $consignee_info['province_id'];
        $model->city_id = $consignee_info['city_id'];
        $model->address = $consignee_info['address'];
    }




}
