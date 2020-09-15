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
            [['consignee_info'],'parseConsigneeInfo'],
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


    /**
     * 款式图库
     */
    public function parseConsigneeInfo()
    {
        $this->consignee_info = json_encode([
            'country_id' => $this->country_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'address' => $this->address
        ]);
        return $this->consignee_info;
    }

    
}
