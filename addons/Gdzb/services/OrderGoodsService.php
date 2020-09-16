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
        $model->goods_id = $prefix.$middle;
        if($save === true) {
            $result = $model->save(true,['id','goods_id']);
            if($result === false){
                throw new \Exception("编货号失败：保存货号失败");
            }
        }
        return $model->goods_id;
    }

}