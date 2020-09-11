<?php

use common\helpers\Url;
use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '推广数据列表';
$this->params['breadcrumbs'][] = ['label' => '专题列表'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit', 'special_id'=>$special_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg'
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover rf-table'],
                    'options' => ['style'=>'white-space:nowrap;'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'attribute' => 'id',
                            'filter' => false,
                        ],
//                        [
//                            'label' => '创建人',
//                            'attribute' => 'creator.username',
//                            'headerOptions' => ['class' => 'col-md-1'],
//                            'filter' => Html::activeTextInput($searchModel, 'creator.username', [
//                                'class' => 'form-control',
//                            ]),
//                        ],
                        [
                            'label' => '开始时间',
                            'attribute'=>'start_time',
                            'filter' => false,
                        ],
                        [
                            'label' => '结束时间',
                            'attribute'=>'end_time',
                            'filter' => false,
                        ],
                        [
                            'attribute'=>'created_at',
//                            'filter' => DateRangePicker::widget([    // 日期组件
//                                'model' => $searchModel,
//                                'attribute' => 'created_at',
//                                'value' => $searchModel->created_at,
//                                'options' => ['readonly' => false,'class'=>'form-control','style'=>'background-color:#fff;width:200px;'],
//                                'pluginOptions' => [
//                                    'format' => 'yyyy-mm-dd',
//                                    'locale' => [
//                                        'separator' => '/',
//                                    ],
//                                    'endDate' => date('Y-m-d',time()),
//                                    'todayHighlight' => true,
//                                    'autoclose' => true,
//                                    'todayBtn' => 'linked',
//                                    'clearBtn' => true,
//                                ],
//                            ]),
                            'filter' => false,
                            'value'=>function($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
                        ],
                        [
                            'label' => '预算',
                            'attribute'=>'budget_cost',
                            'filter' => false,
                        ],
                        [
                            'label' => '实际费用',
                            'attribute'=>'actual_cost',
                            'filter' => false,
                        ],
                        [
                            'label' => '展现',
                            'attribute'=>'show_times',
                            'filter' => false,
                        ],
                        [
                            'label' => '点击',
                            'attribute'=>'hits_times',
                            'filter' => false,
                        ],
                        [
                            'label' => '点击率',
                            'attribute'=>'budget_cost',
                            'filter' => false,
                        ],
                        [
                            'label' => '点击均价',
                            'attribute'=>'budget_cost',
                            'filter' => false,
                        ],
                        [
                            'label' => '平均访问时长',
                            'attribute'=>'visit_length',
                            'filter' => false,
                        ],
                        [
                            'label' => '对话数量',
                            'attribute'=>'dialogue_times',
                            'filter' => false,
                        ],
                        [
                            'label' => '获客数量',
                            'attribute'=>'client_times',
                            'filter' => false,
                        ],
                        [
                            'label' => '订单数量',
                            'attribute'=>'order_times',
                            'filter' => false,
                        ],
//                        [
//                            'attribute' => 'status',
//                            'format' => 'raw',
//                            'headerOptions' => ['class' => 'col-md-1'],
//                            'value' => function ($model){
//                                return \common\enums\StatusEnum::getValue($model->status);
//                            },
//                            'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\StatusEnum::getMap(), [
//                                'prompt' => '全部',
//                                'class' => 'form-control',
//                                'style'=> 'width:60px;',
//                            ]),
//                        ],
//                        [
//                            'header' => "操作",
//                            'class' => 'yii\grid\ActionColumn',
//                            'contentOptions' => ['style' => ['white-space' => 'nowrap']],
//                            'template' => '{edit} {view} {status}',
//                            'buttons' => [
////                                'ajax-edit' => function ($url, $model, $key) {
////                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
////                                        'data-toggle' => 'modal',
////                                        'data-target' => '#ajaxModal',
////                                    ]);
////                                },
////                                'address' => function ($url, $model, $key) {
////                                    return Html::linkButton(['address/index', 'member_id' => $model->id], '收货地址');
////                                },
////                                'recharge' => function ($url, $model, $key) {
////                                    return Html::linkButton(['recharge', 'id' => $model->id], '充值', [
////                                        'data-toggle' => 'modal',
////                                        'data-target' => '#ajaxModal',
////                                    ]);
////                                },
////                                'edit' => function ($url, $model, $key) {
////                                    return Html::edit(['edit', 'id' => $model->id]);
////                                },
////                                'view' => function ($url, $model, $key) {
////                                    return Html::a('查看', ['view', 'id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['class' => 'btn btn-warning btn-sm']);
////                                },
//                                'status' => function ($url, $model, $key) {
//                                    return Html::status($model->status);
//                                },
////                                'destroy' => function ($url, $model, $key) {
////                                    return Html::delete(['destroy', 'id' => $model->id]);
////                                },
//                            ],
//                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>