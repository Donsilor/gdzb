<?php

namespace addons\Gdzb\services;

use addons\Gdzb\common\models\Goods;
use addons\Sales\common\models\OrderGoods;
use addons\Sales\common\models\OrderGoodsAttribute;
use addons\Style\common\enums\QibanTypeEnum;
use addons\Warehouse\common\enums\GoodsStatusEnum;
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
class OrderGoodsService extends Service
{

    /**
     * 创建商品编号
     * @param WarehouseGoods $model
     * @param boolean $save
     * @throws \Exception
     * @return string
     */
    public function createGoodsSn($model, $save = true) {
        if(!$model->id) {
            throw new \Exception("编货号失败：id不能为空");
        }
        $prefix   = '';
        //2.商品材质（产品线）
        $type_tag = $model->productType->tag ?? '0';
        $prefix .= $type_tag;
        //3.产品分类
        $cate_tag = $model->styleCate->tag ?? '';
        if(count($cate_tag_list = explode("-", $cate_tag)) < 2 ) {
            $cate_tag_list = [0,0];
        }
        list($cate_m, $cate_w) = $cate_tag_list;

        //4.数字部分
        $middle = str_pad($model->id,8,'0',STR_PAD_LEFT);
        $model->goods_sn = $prefix.$middle;
        if($save === true) {
            $result = $model->save(true,['goods_sn']);
            if($result === false){
                throw new \Exception("编货号失败：保存货号失败");
            }
        }
        return $model->goods_sn;
    }

    /***
     * 查询库存并更新库存状态
     */
    public function syncGoods($model,$type = 'add'){
        $goods_sn = $model->goods_sn;
        $goods = Goods::find()->where(['goods_sn' => $goods_sn])->one();
        if($goods){
            switch ($type){
                case 'add':
                    if($goods->goods_status != GoodsStatusEnum::IN_STOCK){
                        throw new \Exception("货号不是库存中");
                    }
                    $goods->order_id = $model->order_id;
                    $goods->goods_status = GoodsStatusEnum::IN_SALE;
                    if($goods->save(true,['order_id','goods_status']) === false){
                        throw new \Exception($this->getError($goods));
                    }
                    break;
                case 'del':
                    if($goods->goods_status != GoodsStatusEnum::IN_SALE){
                        throw new \Exception("货号信息有误，请查明原因");
                    }
                    $goods->order_id = '';
                    $goods->goods_status = GoodsStatusEnum::IN_STOCK;
                    if($goods->save(true,['order_id','goods_status']) === false){
                        throw new \Exception($this->getError($goods));
                    }
                    break;
            }

            return $goods;
        }
        return true;

    }



}