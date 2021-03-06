<?php

use addons\Warehouse\common\enums\BillStatusEnum;
use common\helpers\Html;
use common\helpers\Url;
use kartik\select2\Select2;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('templet_bill_l', '样板入库单');
$this->params['breadcrumbs'][] = $this->title;
$params = Yii::$app->request->queryParams;
$params = $params ? "&".http_build_query($params) : '';
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                    <?= Html::button('导出', [
                        'class'=>'btn btn-success btn-xs',
                        'onclick' => 'batchExport()',
                    ]);?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?php echo Html::batchButtons(false)?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'options' => ['style'=>'white-space:nowrap;'],
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
                            'headerOptions' => ['width'=>'30'],
                        ],
                        [
                            'attribute' => 'id',
                            'filter' => true,
                            'format' => 'raw',
                            'headerOptions' => ['width'=>'80'],
                        ],
                        [
                            'attribute'=>'bill_no',
                            'value'=>function($model) {
                                return Html::a($model->bill_no, ['view', 'id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['style'=>"text-decoration:underline;color:#3c8dbc"]);
                            },
                            'filter' => Html::activeTextInput($searchModel, 'bill_no', [
                                'class' => 'form-control',
                            ]),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'supplier_id',
                            'value' =>"supplier.supplier_name",
                            'filter'=>Select2::widget([
                                'name'=>'SearchModel[supplier_id]',
                                'value'=>$searchModel->supplier_id,
                                'data'=>Yii::$app->supplyService->supplier->getDropDown(),
                                'options' => ['placeholder' =>"请选择",'class' => 'col-md-4', 'style'=> 'width:160px;'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]),
                            'format' => 'raw',
                            'headerOptions' => [],
                        ],
                        /*[
                            'attribute' => 'bill_type',
                            'value' => function ($model){
                                return \addons\Warehouse\common\enums\GoldBillTypeEnum::getValue($model->bill_type);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'bill_type',\addons\Warehouse\common\enums\GoldBillTypeEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style' => 'width:100px;'

                            ]),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1','style'=>'width:100px;'],
                        ],*/
                        /*[
                            'attribute' => 'to_warehouse_id',
                            'value' =>"toWarehouse.name",
                            'filter'=>Select2::widget([
                                'name'=>'SearchModel[to_warehouse_id]',
                                'value'=>$searchModel->to_warehouse_id,
                                'data'=>Yii::$app->warehouseService->warehouse::getDropDown(),
                                'options' => ['placeholder' =>"请选择"],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '200',
                                ],
                            ]),
                            'format' => 'raw',
                            'headerOptions' => [],
                        ],
                        [
                            'attribute'=>'total_num',
                            'filter' => Html::activeTextInput($searchModel, 'total_num', [
                                'class' => 'form-control',
                            ]),
                            'headerOptions' => ['width'=>'120'],
                        ],*/
                        [
                            'attribute'=>'total_weight',
                            'filter' => Html::activeTextInput($searchModel, 'total_weight', [
                                'class' => 'form-control',
                            ]),
                            'headerOptions' => ['width'=>'80'],
                        ],
                        [
                            'attribute'=>'total_cost',
                            'filter' => Html::activeTextInput($searchModel, 'total_cost', [
                                'class' => 'form-control',
                            ]),
                            'headerOptions' => ['width'=>'80'],
                        ],
                        [
                            'attribute'=>'delivery_no',
                            'filter' => Html::activeTextInput($searchModel, 'delivery_no', [
                                'class' => 'form-control',
                            ]),
                            'headerOptions' => ['width'=>'120'],
                        ],
                        [
                            'attribute' => 'channel_id',
                            'value' => function ($model){
                                return $model->channel->name ?? '';
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'channel_id',Yii::$app->salesService->saleChannel->getDropDown(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style'=> 'width:120px;'
                            ]),
                            'format' => 'raw',
                            'headerOptions' => [],
                        ],
                        [
                            'attribute' => 'creator_id',
                            'value' => 'creator.username',
                            'filter' => Html::activeTextInput($searchModel, 'creator.username', [
                                'class' => 'form-control',
                            ]),
                            'headerOptions' => ['width'=>'80'],
                        ],
                        [
                            'attribute'=>'created_at',
                            'filter' => DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'value' => $searchModel->created_at,
                                'options' => ['readonly' => false,'class'=>'form-control','style'=>'width:160px;'],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'locale' => [
                                        'separator' => '/',
                                    ],
                                    'endDate' => date('Y-m-d',time()),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'todayBtn' => 'linked',
                                    'clearBtn' => true,
                                ],
                            ]),
                            'value'=>function($model){
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
                        ],
                        [
                            'attribute' => 'audit_status',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model){
                                return \common\enums\AuditStatusEnum::getValue($model->audit_status);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'audit_status',\common\enums\AuditStatusEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style'=> 'width:100px;'
                            ]),
                        ],
                        [
                            'attribute' => 'bill_status',
                            'value' => function ($model){
                                return \addons\Warehouse\common\enums\BillStatusEnum::getValue($model->bill_status);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'bill_status',\addons\Warehouse\common\enums\BillStatusEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style' => 'width:100px;'

                            ]),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1','style'=>'width:120px;'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'contentOptions' => ['style' => ['white-space' => 'nowrap']],
                            'template' => '{edit} {apply} {audit} {goods} {cancel} {delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key){
                                    if(in_array($model->bill_status, [BillStatusEnum::SAVE])){
                                        return Html::edit(['ajax-edit', 'id' => $model->id, 'returnUrl' => Url::getReturnUrl()], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    }
                                },
                                'apply' => function($url, $model, $key){
                                    if($model->bill_status == BillStatusEnum::SAVE){
                                        return Html::edit(['ajax-apply','id'=>$model->id], '提审', [
                                            'class'=>'btn btn-success btn-sm',
                                            'onclick' => 'rfTwiceAffirm(this,"提交审核", "确定提交吗？");return false;',
                                        ]);
                                    }
                                },
                                'audit' => function($url, $model, $key){
                                    if(in_array($model->bill_status,[BillStatusEnum::PENDING])){
                                        return Html::edit(['ajax-audit','id'=>$model->id], '审核', [
                                            'class'=>'btn btn-success btn-sm',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    }
                                },
                                'goods' => function($url, $model, $key){
                                    return Html::a('明细', ['templet-bill-l-goods/index', 'bill_id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['class' => 'btn btn-warning btn-sm']);
                                },
                                'cancel' => function($url, $model, $key){
                                    if($model->bill_status == \addons\Warehouse\common\enums\TempletBillStatusEnum::SAVE) {
                                        return Html::delete(['cancel', 'id' => $model->id],'取消',[
                                            'onclick' => 'rfTwiceAffirm(this,"取消单据", "确定取消吗？");return false;',
                                        ]);
                                    }
                                },
                                'delete' => function($url, $model, $key){
                                    if($model->bill_status == \addons\Warehouse\common\enums\TempletBillStatusEnum::CANCEL) {
                                        return Html::delete(['delete', 'id' => $model->id]);
                                    }
                                },
                            ],
                        ]
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
<script>
    function batchExport() {
        var ids = $("#grid").yiiGridView("getSelectedRows");
        if(ids.length == 0){
            var url = "<?= Url::to('index?action=export'.$params);?>";
            rfExport(url)
        }else{
            window.location.href = "<?= Url::buildUrl('export',[],['ids'])?>?ids=" + ids;
        }
    }
</script>