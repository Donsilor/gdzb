<?php

use common\helpers\Html;
use common\helpers\Url;
use kartik\select2\Select2;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use addons\Warehouse\common\enums\BillStatusEnum;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('warehouse_bill_m', '调整单列表');
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
                    'options' => ['style'=>' width:120%;white-space:nowrap;' ],
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
                            'filter' => Html::activeTextInput($searchModel, 'id', [
                                'class' => 'form-control',

                            ]),
                            'headerOptions' => ['class' => 'col-md-1'],
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
                            'attribute' => 'bill_type',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model){
                                return \addons\Warehouse\common\enums\BillTypeEnum::getValue($model->bill_type);
                            },
                            'filter' => false,
                        ],
                        //'to_company_id',
                        [
                            'attribute' => 'from_warehouse_id',
                            'value' =>"fromWarehouse.name",
                            'filter'=>Select2::widget([
                                'name'=>'SearchModel[from_warehouse_id]',
                                'value'=>$searchModel->from_warehouse_id,
                                'data'=>Yii::$app->warehouseService->warehouse::getDropDown(),
                                'options' => ['placeholder' =>"请选择"],
                                'pluginOptions' => [
                                    'allowClear' => true,

                                ],
                            ]),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-2'],
                        ],

                        [
                            'attribute' => 'goods_num',
                            'filter' => Html::activeTextInput($searchModel, 'goods_num', [
                                'class' => 'form-control',

                            ]),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label' => '审批数量',
                            'value' => function($model){
                                return \Yii::$app->warehouseService->billA->getDjustNum($model->id);
                            }
                        ],
                        [
                            'attribute' => 'bill_status',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model){
                                return BillStatusEnum::getValue($model->bill_status);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'bill_status',BillStatusEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style'=> 'width:100px;'

                            ]),
                        ],
                        [
                            'attribute' => 'creator_id',
                            'value' => 'creator.username',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'creator.username', [
                                'class' => 'form-control',

                            ]),

                        ],
                        [
                            'attribute'=>'created_at',
                            'filter' => DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'value' => $searchModel->created_at,
                                'options' => ['readonly' => false,'class'=>'form-control','style'=>'background-color:#fff;width:150px;'],
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
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{edit} {goods} {ajax-apply} {audit} {delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key){
                                    if($model->bill_status == BillStatusEnum::SAVE) {
                                        return Html::edit(['ajax-edit', 'id' => $model->id, 'returnUrl' => Url::getReturnUrl()], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
                                    }
                                },

                                'goods' => function($url, $model, $key){
                                    return Html::a('明细', ['bill-m-goods/index', 'bill_id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['class' => 'btn btn-warning btn-sm']);
                                },

                                'delete' => function($url, $model, $key){
                                    if($model->bill_status <= \addons\Warehouse\common\enums\BillStatusEnum::PENDING) {
                                        return Html::delete(['delete', 'id' => $model->id], '关闭',[
                                            'onclick' => 'rfTwiceAffirm(this,"关闭单据", "确定关闭吗？");return false;',
                                        ]);
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