<?php

namespace addons\Gdzb\services;

use addons\Sales\common\models\OrderGoods;
use addons\Sales\common\models\OrderGoodsAttribute;
use addons\Style\common\enums\QibanTypeEnum;
use Yii;
use common\components\Service;
use common\helpers\Url;
use addons\Sales\common\forms\OrderForm;
use addons\Sales\common\models\OrderAccount;
use addons\Sales\common\models\Customer;
use addons\Sales\common\models\Order;
use addons\Sales\common\models\OrderAddress;
use common\enums\AuditStatusEnum;
use addons\Sales\common\enums\IsStockEnum;
use addons\Style\common\models\Style;
use addons\Finance\common\models\OrderPay;
use common\helpers\SnHelper;
use addons\Sales\common\enums\PayStatusEnum;
use common\enums\LogTypeEnum;
use addons\Sales\common\enums\OrderFromEnum;
use addons\Sales\common\models\OrderInvoice;

/**
 * Class SaleChannelService
 * @package services\common
 */
class OrderService extends Service
{
    /**
     * 顾客订单菜单
     * @param int $order_id
     * @return array
     */
    public function menuTabList($order_id, $returnUrl = null)
    {
        return [
            1=>['name'=>'订单信息','url'=>Url::to(['order/view','id'=>$order_id,'tab'=>1,'returnUrl'=>$returnUrl])],
            2=>['name'=>'日志信息','url'=>Url::to(['order-log/index','order_id'=>$order_id,'tab'=>2,'returnUrl'=>$returnUrl])],
        ];
    }
    /**
     * 人工创建订单
     *
     * @param OrderForm $form
     */
    public function createOrder($form)
    {
        if(false == $form->validate()) {
            throw new \Exception($this->getError($form));
        }
        $isNewOrder = $form->isNewRecord;
        //1.创建订单
        $order = clone $form;
        if(false == $order->save()) {
            throw new \Exception($this->getError($order));
        }
//        $customer = Customer::find()->where(['mobile'=>$order->customer_mobile,'channel_id'=>$order->sale_channel_id])->one();
//        if(!$customer) {
//            //2.创建用户信息
//            $customer = new Customer();
//            $customer->realname = $order->customer_name;
//            $customer->mobile = $order->customer_mobile;
//            $customer->email = $order->customer_email;
//            $customer->channel_id = $order->sale_channel_id;
//            $customer->level = $form->customer_level;
//            $customer->source_id = $form->customer_source;
//            if(false == $customer->save()) {
//                throw new \Exception("创建用户失败：".$this->getError($customer));
//            }
//            \Yii::$app->salesService->customer->createCustomerNo($customer,true);
//        }else{
//            //更新用户信息
//            $customer->realname = $customer->realname ? $customer->realname : $order->customer_name;
//            $customer->mobile = $customer->mobile ? $customer->mobile: $order->customer_mobile;
//            $customer->email = $customer->email ? $customer->email : $order->customer_email;
//            $customer->level = $customer->level ? $customer->level: $form->customer_level;
//            $customer->source_id = $customer->source_id ? $customer->source_id : $form->customer_source;
//            if(false == $customer->save()) {
//                throw new \Exception("更新用户失败：".$this->getError($customer));
//            }
//        }
//        $order->customer_id = $customer->id;
        if($form->isNewRecord){
            $order->order_sn = $this->createOrderSn($order);
        }
        if(false == $order->save()) {
            throw new \Exception($this->getError($order));
        }

        //创建订单日志
        if($isNewOrder === true) {
            $log = [
                'order_id' => $order->id,
                'order_sn' => $order->order_sn,
                'order_status' => $order->order_status,
                'log_type' => LogTypeEnum::ARTIFICIAL,
                'log_time' => time(),
                'log_module' => '创建订单',
                'log_msg' => "创建订单, 订单号:".$order->order_sn
            ];
            \Yii::$app->gdzbService->orderLog->realCreateOrderLog($log);
        }
        return $order;
    }


    /**
     * 创建订单编号
     * @param Style $model
     * @throws
     * @return string
     */
    public static function createOrderSn($model,$save = false)
    {
        if(!$model->id) {
            throw new \Exception("创建订单号失败：ID不能为空");
        }
        $order_sn = date("Ymd").str_pad($model->id,8,'0',STR_PAD_LEFT);
        $model->order_sn = $order_sn;
        if($save === true) {
            $result = $model->save(true,['id','order_sn']);
            if($result === false){
                throw new \Exception("保存失败");
            }
        }
        return $model->order_sn;
    }



    /**
     * 订单金额汇总
     * @param unknown $purchase_id
     */
    public function orderSummary($order_id)
    {
        $sum = OrderGoods::find()
            ->select(['sum(1) as total_num','sum(goods_price) as order_amount'])
            ->where(['order_id'=>$order_id])
            ->asArray()->one();
        if($sum) {
            Order::updateAll(['goods_num'=>$sum['total_num'], 'goods_price'=>$sum['order_amount']],['id'=>$order_id]);
        }
    }

}