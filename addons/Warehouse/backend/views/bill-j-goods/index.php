<?php

use common\helpers\Html;
use common\helpers\Url;
use kartik\select2\Select2;
use addons\Warehouse\common\enums\BillStatusEnum;
use addons\Warehouse\common\enums\DeliveryTypeEnum;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('bill_j_goods', '借货单明细');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-body nav-tabs-custom">
    <h2 class="page-header"><?php echo $this->title; ?> - <?php echo $bill->bill_no?> - <?= \addons\Warehouse\common\enums\BillStatusEnum::getValue($bill->bill_status)?></h2>
    <?php echo Html::menuTab($tabList,$tab)?>
    <div style="float:right;margin-top:-40px;margin-right: 20px;">
        <?php
        if($bill->bill_status == BillStatusEnum::SAVE){
            echo Html::create(['add', 'bill_id' => $bill->id], '新增货品', [
                'class' => 'btn btn-primary btn-xs openIframe',
                'data-width'=>'90%',
                'data-height'=>'90%',
                'data-offset'=>'20px',
            ]);
            echo '&nbsp;';
//            echo Html::edit(['edit-all', 'bill_id' => $bill->id], '编辑货品', ['class'=>'btn btn-info btn-xs']);
//            echo '&nbsp;';
        }
        if($bill->bill_status == BillStatusEnum::CONFIRM) {
            echo Html::batchPopButton(['batch-receive', 'bill_id'=>$bill->id, 'check'=>1],'批量接收', [
                'class'=>'btn btn-primary btn-xs',
                'data-width'=>'50%',
                'data-height'=>'60%',
                'data-offset'=>'10px',
            ]);
            echo '&nbsp;';
            echo Html::batchPopButton(['batch-return', 'bill_id'=>$bill->id,'check'=>1],'批量还货', [
                'class'=>'btn btn-info btn-xs',
                'data-width'=>'40%',
                'data-height'=>'85%',
                'data-offset'=>'10px',
            ]);
            echo '&nbsp;';
        }
        echo Html::a('导出', ['bill-j/export?ids='.$bill->id],[
            'class'=>'btn btn-success btn-xs'
        ]);
        ?>
    </div>
    <div class="tab-content">
        <div class="row col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body table-responsive">
                    <?php echo Html::batchButtons(false)?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-hover'],
                        'options' => ['style'=>' width:130%;white-space:nowrap;'],
                        'showFooter' => false,//显示footer行
                        'id'=>'grid',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => false,
                            ],
                            [
                                'class'=>'yii\grid\CheckboxColumn',
                                'name'=>'id',  //设置每行数据的复选框属性
                            ],
                            [
                                'attribute' => 'id',
                                'filter' => false,
                                'format' => 'raw',
                            ],
                            [
                                'attribute'=>'goods_id',
                                'filter' => true,
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'style_sn',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => true,
                            ],
                            [
                                'attribute' => 'goods_name',
                                'filter' => true,
                                'headerOptions' => ['class' => 'col-md-2'],
                            ],
                            
                            [
                                'attribute' => 'goods.style_cate_id',
                                'value' => 'goods.styleCate.name',
                                'filter' => true,
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'goods.product_type_id',
                                'value' => 'goods.productType.name',
                                'filter' => true,
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],

                            [
                                'attribute' => 'warehouse_id',
                                'value' =>"warehouse.name",
                                'filter'=>Select2::widget([
                                    'name'=>'SearchModel[warehouse_id]',
                                    'value'=>$searchModel->warehouse_id,
                                    'data'=>Yii::$app->warehouseService->warehouse::getDropDown(),
                                    'options' => ['placeholder' =>"请选择"],
                                    'pluginOptions' => [
                                        'allowClear' => true,

                                    ],
                                ]),
                                'headerOptions' => ['class' => 'col-md-2'],
                            ],

                            [
                                'attribute' => 'material',
                                'value' => function($model){
                                    return Yii::$app->attr->valueName($model->material);
                                },
                                'filter' => false,
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'gold_weight',
                                'filter' => false,
                            ],

                            [
                                'attribute' => 'goods.main_stone_type',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.diamond_carat',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.main_stone_num',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.second_stone_weight1',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.second_stone_num1',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.finger',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.finger_hk',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goods.cert_id',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'cost_price',
                                'visible' => \common\helpers\Auth::verify(\common\enums\SpecialAuthEnum::VIEW_CAIGOU_PRICE),
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'goodsJ.lend_status',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return \addons\Warehouse\common\enums\LendStatusEnum::getValue($model->goodsJ->lend_status)??"";
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'goodsJ.lend_status',\addons\Warehouse\common\enums\LendStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label' => '接收人',
                                'value' => function($model){
                                    return $model->goodsJ->receive->username ?? "";
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label' => '接收时间',
                                'value' => function($model){
                                    if($model->goodsJ->receive_time){
                                        return Yii::$app->formatter->asDatetime($model->goodsJ->receive_time) ?? "";
                                    }
                                    return "";
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label' => '接收备注',
                                'value' => function($model){
                                    return $model->goodsJ->receive_remark ?? "";
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-2'],
                            ],
                            [
                                'attribute' => 'goodsJ.qc_status',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return \addons\Warehouse\common\enums\QcStatusEnum::getValue($model->goodsJ->qc_status)??"";
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'goodsJ.qc_status',\addons\Warehouse\common\enums\QcStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label' => '质检备注',
                                'value' => function($model){
                                    return $model->goodsJ->qc_remark ?? "";
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-2'],
                            ],
                            [
                                'label' => '还货时间',
                                'value' => function($model){
                                    if($model->goodsJ->restore_time){
                                        return Yii::$app->formatter->asDatetime($model->goodsJ->restore_time) ?? "";
                                    }
                                    return "";
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'goods_remark',
                                'filter' => false,
                                'headerOptions' => ['class' => 'col-md-2'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{delete}',
                                'buttons' => [
                                    'edit' => function($url, $model, $key) use($bill) {
                                        if($model->goodsJ->lend_status == \addons\Warehouse\common\enums\LendStatusEnum::IN_RECEIVE) {
                                            return Html::edit(['ajax-edit', 'id' => $model->id, 'returnUrl' => Url::getReturnUrl()], '编辑', [
                                                'class'=>'btn btn-primary btn-xs',
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModal',
                                            ]);
                                        }
                                    },
                                    'delete' => function($url, $model, $key) use($bill) {
                                        if($bill->bill_status == BillStatusEnum::SAVE){
                                            return Html::delete(['delete', 'id' => $model->id],'删除',['class'=>'btn btn-danger btn-xs']);
                                        }
                                    },
                                ],
                                'headerOptions' => ['class' => 'col-md-3'],
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>