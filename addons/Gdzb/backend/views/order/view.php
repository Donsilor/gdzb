<?php

use addons\Sales\common\enums\DistributeStatusEnum;
use addons\Sales\common\enums\PayStatusEnum;
use common\helpers\Html;
use addons\Sales\common\enums\OrderStatusEnum;
use addons\Sales\common\enums\IsStockEnum;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\AmountHelper;
use addons\Style\common\enums\AttrIdEnum;

/* @var $this yii\web\View */
/* @var $model common\models\order\order */
/* @var $form yii\widgets\ActiveForm */

$this->title = '订单详情';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//
?>
<div class="box-body nav-tabs-custom">
    <h2 class="page-header"><?php echo $this->title; ?> - <?php echo $model->order_sn ?></h2>
    <?php echo Html::menuTab($tabList, $tab) ?>
    <div class="tab-content">
        <div class="col-xs-12">
            <div class="box" style="margin:0px">
                <div class="box-header" style="margin:0">
                    <h3 class="box-title"><i class="fa fa-info"></i> 订单信息</h3>
                </div>
                <div class=" table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td class="col-xs-1 text-right no-border-top"><?= $model->getAttributeLabel('order_sn') ?>
                                ：
                            </td>
                            <td class="col-xs-3 no-border-top"><?= $model->order_sn ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('channel_id') ?>：</td>
                            <td><?= $model->saleChannel->name ?? '' ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('order_status') ?>：</td>
                            <td><?= addons\Sales\common\enums\OrderStatusEnum::getValue($model->order_status) ?></td>

                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"></td>
                            <td></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('warehouse_id') ?>：</td>
                            <td><?= $model->warehouse->name ?? '' ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('pay_status') ?>：</td>
                            <td><?= addons\Sales\common\enums\PayStatusEnum::getValue($model->pay_status) ?></td>
                        </tr>
                        <tr>

                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('order_from') ?>：</td>
                            <td><?= \addons\Gdzb\common\enums\OrderFromEnum::getValue($model->order_from) ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('express_id') ?>：</td>
                            <td><?= $model->express->name ?? '' ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('refund_status') ?>：</td>
                            <td><?= addons\Sales\common\enums\RefundStatusEnum::getValue($model->refund_status) ?></td>
                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('customer_name') ?>：</td>
                            <td><?= $model->customer_name ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('express_no') ?>：</td>
                            <td><?= $model->express_no ?? '' ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('pay_time') ?>：</td>
                            <td><?= $model->pay_time ? Yii::$app->formatter->asDate($model->pay_time) : '' ?></td>
                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('customer_weixin') ?>：</td>
                            <td><?= $model->customer_weixin ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('delivery_time') ?>：</td>
                            <td><?= $model->delivery_time ? Yii::$app->formatter->asDate($model->delivery_time) : '' ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('follower_id') ?>：</td>
                            <td><?= $model->follower->username ?? '' ?></td>
                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('customer_mobile') ?>：</td>
                            <td><?= $model->customer_mobile ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('is_invoice') ?>：</td>
                            <td><?= \common\enums\ConfirmEnum::getValue($model->is_invoice); ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('created_at') ?>：</td>
                            <td><?= $model->pay_time ? Yii::$app->formatter->asDate($model->created_at) : '' ?></td>

                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('order_amount') ?>：</td>
                            <td><?= $model->order_amount ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('refund_no') ?>：</td>
                            <td><?= $model->refund_no ?></td>
                            <td class="col-xs-1 text-right"></td>
                            <td></td>

                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('goods_num') ?>：</td>
                            <td><?= $model->goods_num ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('collect_type') ?>：</td>
                            <td><?= \addons\Gdzb\common\enums\PayTypeEnum::getValue($model->collect_type) ?></td>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('collect_no') ?>：</td>
                            <td><?= $model->collect_no ?></td>
                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right">供应商微信号：</td>
                            <td><?= $model->supplier->wechat ?? '' ?></td>
                            <td class="col-xs-1 text-right">供应商姓名：</td>
                            <td><?= $model->supplier->contactor ?? '' ?></td>
                            <td class="col-xs-1 text-right"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="col-xs-1 text-right"><?= $model->getAttributeLabel('remark') ?>：</td>
                            <td colspan="5"><?= $model->remark ?></td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer text-center">
                    <?php
                    if ($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::SAVE) {
                        echo Html::edit(['edit', 'id' => $model->id,'returnUrl' => Url::getReturnUrl()], '编辑', ['class' => 'btn btn-primary btn-ms']);
                    }
                    ?>

                    <?php
                    if ($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::SAVE) {
                        echo Html::edit(['ajax-apply', 'id' => $model->id], '提审', [
                            'class' => 'btn btn-success btn-ms',
                            'onclick' => 'rfTwiceAffirm(this,"提交审核", "确定提交吗？");return false;',
                        ]);
                    }
                    ?>
                    <?php

                    if ($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::PENDING ) {
                        echo Html::edit(['ajax-audit', 'id' => $model->id], '审核', [
                            'class' => 'btn btn-success btn-ms',
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                        ]);
                    }
                    ?>
                    <?php
                    if ($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::CONFORMED && empty($model->apply_id)) {
                        echo Html::edit(['ajax-purchase-apply', 'id' => $model->id], '申请采购', [
                            'class' => 'btn btn-success btn-ms',
                            'onclick' => 'rfTwiceAffirm(this,"申请采购", "确定申请采购吗？");return false;',
                        ]);
                    }
                    ?>
                    <?php
                    if ($model->pay_status == \addons\Sales\common\enums\PayStatusEnum::HAS_PAY
                        && !in_array($model->refund_status, [\addons\Sales\common\enums\RefundStatusEnum::HAS_RETURN])) {
                        echo Html::edit(['return', 'id' => $model->id], '退款', [
                            //'data-toggle' => 'modal',
                            'class' => 'btn btn-warning btn-ms openIframe',
                            //'data-target' => '#ajaxModalLg',
                            'data-width' => '90%', 'data-height' => '90%', 'data-offset' => '20px'
                        ]);
                    }
                    ?>

                    <?php
                    if ($model->delivery_status == \addons\Sales\common\enums\DeliveryStatusEnum::TO_SEND) {
                        echo Html::a('发货', ['shipping/view', 'id' => $model->id, 'returnUrl' => Url::getReturnUrl()], ['class' => 'btn btn-primary btn-ms']);
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- box end -->
        <!-- box begin -->
        <div class="col-xs-12">
            <div class="box" style="margin:0px">
                <div class="box-header" style="margin:0">
                    <h3 class="box-title"><i class="fa fa-info"></i> 商品明细</h3>
                    <?php
                    if ($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::SAVE) {
                        echo Html::create(['order-goods/ajax-edit','order_id'=>$model->id], '添加', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ]);
                    }
                    ?>
                    <?php
                    //                    if($model->order_status == \addons\Sales\common\enums\OrderStatusEnum::CONFORMED) {
                    //                        echo Html::button('布产', [
                    //                            'class'=>'btn btn-success btn-xs',
                    //                            'onclick' => 'batchBuchan()',
                    //                        ]);
                    //                    }
                    ?>
                </div>
                <div class="table-responsive col-lg-12">
                    <?php $order = $model ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-hover'],
                        'options' => ['id' => 'order-goods', 'style' => ' width:100%;white-space:nowrap;'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => false,
                            ],
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'name' => 'id',  //设置每行数据的复选框属性
                                'headerOptions' => ['width' => '30'],
                            ],
                            'id',
                            [
                                'attribute' => 'goods_sn',
                                'value' => 'goods_sn',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'goods_image',
                                'value' => function ($model) {
                                    $goods_image = $model->goods_image ? explode(',', $model->goods_image) : [];
                                    return common\helpers\ImageHelper::fancyBox($goods_image);
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['width' => '80'],
                            ],

                            [
                                'attribute' => 'goods_name',
                                'value' => function ($model) {
                                    return "<div style='width:200px;white-space:pre-wrap;'>" . $model->goods_name . "</div>";
                                },
                                'format' => 'raw',
                            ],

                            [
                                'attribute' => 'style_cate_id',
                                'value' => function ($model) {
                                    return $model->cate->name ?? '';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'product_type_id',
                                'value' => function ($model) {
                                    return $model->type->name ?? '';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'goods_price',
                                'value' => function ($model) {
                                    return $model->goods_price;
                                }
                            ],


                            [
                                'attribute' => 'bc_status',
                                'value' => function ($model) {
                                    return \addons\Supply\common\enums\BuChanEnum::getValue($model->bc_status) ?? '未布产' . '<br/>' . $model->produce_sn;
                                },
                                'format' => 'raw',
                            ],



                            'remark',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                //'headerOptions' => ['width' => '150'],
                                'template' => '{view} {edit} {delete} <br/>{stock} {untie} {apply-edit} ',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::edit(['order-goods/view', 'id' => $model->id, 'order_id' => $model->order_id, 'returnUrl' => Url::getReturnUrl()], '详情', [
                                            'class' => 'btn btn-info btn-xs',
                                        ]);
                                    },
                                    'edit' => function ($url, $model, $key) use ($order) {
                                        if ($order->order_status == OrderStatusEnum::SAVE) {
                                            if ($model->product_type_id == 1) {
                                                return Html::edit(['order-goods/edit-diamond', 'id' => $model->id], '编辑', ['class' => 'btn btn-primary btn-xs openIframe', 'data-width' => '90%', 'data-height' => '90%', 'data-offset' => '20px']);
                                            } elseif ($model->is_gift == \addons\Sales\common\enums\IsGiftEnum::YES) {
                                                return Html::edit(['order-goods/edit-gift', 'id' => $model->id], '编辑', ['class' => 'btn btn-primary btn-xs openIframe', 'data-width' => '90%', 'data-height' => '90%', 'data-offset' => '20px']);
                                            } elseif ($model->is_stock == IsStockEnum::NO) {
                                                return Html::edit(['order-goods/edit', 'id' => $model->id], '编辑', ['class' => 'btn btn-primary btn-xs openIframe', 'data-width' => '90%', 'data-height' => '90%', 'data-offset' => '20px']);
                                            } else {
                                                return Html::edit(['order-goods/edit-stock', 'id' => $model->id], '编辑', ['class' => 'btn btn-primary btn-xs openIframe', 'data-width' => '90%', 'data-height' => '90%', 'data-offset' => '20px']);
                                            }

                                        }
                                    },
                                    'stock' => function ($url, $model, $key) use ($order) {
                                        if ($order->order_status == OrderStatusEnum::SAVE && $model->is_stock == IsStockEnum::NO) {
                                            return Html::edit(['order-goods/stock', 'id' => $model->id], '绑定现货', ['class' => 'btn btn-primary btn-xs', 'data-toggle' => 'modal', 'data-target' => '#ajaxModalLg',]);
                                        }
                                    },
                                    'untie' => function ($url, $model, $key) use ($order) {
                                        if ($order->order_status == OrderStatusEnum::SAVE && $model->is_stock == IsStockEnum::YES && $model->product_type_id != 1 && $model->is_gift == \addons\Sales\common\enums\IsGiftEnum::NO) {
                                            return Html::edit(['order-goods/untie', 'id' => $model->id], '解绑', [
                                                'class' => 'btn btn-primary btn-xs',
                                                'onclick' => 'rfTwiceAffirm(this,"解绑现货", "确定解绑吗？");return false;',
                                            ]);
                                        }

                                    },
//                                            'apply-edit' =>function($url, $model, $key) use($order){
//                                                if($order->order_status == OrderStatusEnum::CONFORMED) {
//                                                    return Html::edit(['order-goods/apply-edit','id' => $model->id],'申请编辑',['class' => 'btn btn-primary btn-xs openIframe','data-width'=>'90%','data-height'=>'90%','data-offset'=>'20px']);
//                                                }
//                                            },
                                    'delete' => function ($url, $model, $key) use ($order) {
                                        if ($order->order_status == OrderStatusEnum::SAVE) {
                                            return Html::delete(['order-goods/delete', 'id' => $model->id, 'order_id' => $model->order_id, 'returnUrl' => Url::getReturnUrl()], '删除', ['class' => 'btn btn-danger btn-xs']);
                                        }
                                    },
                                ]
                            ]
                        ]
                    ]); ?>
                </div>
                <div class="box-footer">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8 text-right"><label><?= $model->getAttributeLabel('goods_num') ?>
                                    ：</label></div>
                            <div class="col-lg-4"><?= $model->goods_num ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.goods_amount') ?>：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->goods_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.shipping_fee') ?>：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->shipping_fee ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right"><label><?= $model->getAttributeLabel('account.tax_fee') ?>
                                    ：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->tax_fee ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right"><label><?= $model->getAttributeLabel('account.safe_fee') ?>
                                    ：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->safe_fee ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.order_amount') ?>：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->order_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.discount_amount') ?>：</label></div>
                            <div class="col-lg-4"><?= AmountHelper::outputAmount($model->account->discount_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.pay_amount') ?>：</label></div>
                            <div class="col-lg-4"
                                 style="color:red"><?= AmountHelper::outputAmount($model->account->pay_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.paid_amount') ?>：</label></div>
                            <div class="col-lg-4"
                                 style="color:red"><?= AmountHelper::outputAmount($model->account->paid_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 text-right">
                                <label><?= $model->getAttributeLabel('account.refund_amount') ?>：</label></div>
                            <div class="col-lg-4"
                                 style="color:red"><?= AmountHelper::outputAmount($model->account->refund_amount ?? 0, 2, $model->currency) ?></div>
                        </div>
                    </div><!-- end col-lg-6 -->
                </div><!-- end footer -->
            </div>
        </div>
        <!-- box begin -->
        <div class="col-xs-12">
            <div class="box" style="margin:0px">
                <div class="box-header" style="margin:0">
                    <h3 class="box-title"><i class="fa fa-info"></i> 收货人信息</h3>
                </div>
                <div class=" table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>收货人</th>
                            <th>联系方式</th>
                            <th>国家</th>
                            <th>省份</th>
                            <th>城市</th>
                            <th>详细地址</th>
                            <th>邮编</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $model->address->realname ?? '' ?></td>
                            <td>
                                <?php
                                $str = '';
                                if ($model->address) {
                                    if ($model->address->mobile) {
                                        $str .= $model->address->mobile . '<br/>';
                                    }
                                    if ($model->address->email) {
                                        $str .= $model->address->email;
                                    }
                                }
                                echo $str;
                                ?>
                            </td>
                            <td><?= $model->address->country_name ?? '' ?></td>
                            <td><?= $model->address->province_name ?? '' ?></td>
                            <td><?= $model->address->city_name ?? '' ?></td>
                            <td><?= $model->address->address_details ?? '' ?></td>
                            <td><?= $model->address->zip_code ?? '' ?></td>
                            <td><?= Html::edit(['ajax-edit-address', 'id' => $model->id, 'returnUrl' => $returnUrl], '编辑', [
                                    'class' => 'btn btn-primary btn-ms',
                                    'style' => "margin-left:5px",
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]); ?>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- box end -->
        <!-- box begin -->
        <div class="col-xs-12">
            <div class="box" style="margin:0px">
                <div class="box-header" style="margin:0">
                    <h3 class="box-title"><i class="fa fa-info"></i> 发票信息</h3>
                </div>
                <div class=" table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>是否开发票</th>
                            <th>发票抬头</th>
                            <th>纳税人识别号</th>
                            <th>发票类型</th>
                            <th>发票邮箱</th>
                            <th>发送次数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= addons\Sales\common\enums\IsInvoiceEnum::getValue($model->invoice->is_invoice ?? '') ?></td>
                            <td><?= $model->invoice->invoice_title ?? '' ?></td>
                            <td><?= $model->invoice->tax_number ?? '' ?></td>
                            <td><?= addons\Sales\common\enums\InvoiceTypeEnum::getValue($model->invoice->invoice_type ?? '') ?></td>
                            <td><?= $model->invoice->email ?? '' ?></td>
                            <td><?= $model->invoice->send_num ?? '' ?></td>
                            <td><?= Html::edit(['ajax-edit-invoice', 'id' => $model->id, 'returnUrl' => $returnUrl], '编辑', [
                                    'class' => 'btn btn-primary btn-ms',
                                    'style' => "margin-left:5px",
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]); ?>
                                <?= Html::edit(['ajax-send-invoice', 'id' => $model->id, 'returnUrl' => $returnUrl], '发送', [
                                    'class' => 'btn btn-success btn-ms',
                                    'style' => "margin-left:5px",
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- box end -->

        <div id="flow">

        </div>
    </div>
    <!-- tab-content end -->
</div>

