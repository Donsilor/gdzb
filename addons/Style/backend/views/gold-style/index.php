<?php

use addons\Style\common\enums\AttrIdEnum;
use addons\Warehouse\common\enums\BillStatusEnum;
use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('gold_style', '金料款式列表');
$this->params['breadcrumbs'][] = $this->title;
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
                </div>
            </div>
            <div class="box-body table-responsive">
                <?php echo Html::batchButtons(false)?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'options' => ['style'=>'white-space:nowrap;' ],
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
                            'attribute' => 'style_sn',
                            'format' => 'raw',
                            'value'=>function($model) {
                                return Html::a($model->style_sn, ['view', 'id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['style'=>"text-decoration:underline;color:#3c8dbc"]);
                            },
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'style_sn', [
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'label' => '金料类型',
                            'attribute' => 'gold_type',
                            'value' => function ($model){
                                return Yii::$app->attr->valueName($model->gold_type);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'gold_type',Yii::$app->attr->valueMap(AttrIdEnum::MAT_GOLD_TYPE), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                                'style'=> 'width:100px;'
                            ]),
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'gold_name',
                            'value' => 'gold_name',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'gold_name', [
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'attribute' => 'remark',
                            'value' => 'remark',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'remark', [
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'label' => '创建人',
                            'attribute' => 'creator_id',
                            'value' => 'creator.username',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'creator.username', [
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'value' => '',
                                'options' => ['readonly' => true, 'class' => 'form-control','style'=>'background-color:#fff;width:160px;'],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'locale' => [
                                        'separator' => '/',
                                    ],
                                    'endDate' => date('Y-m-d', time()),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'todayBtn' => 'linked',
                                    'clearBtn' => true,
                                ],
                            ]),
                            'value' => function ($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        /*[
                            'label' => '审核人',
                            'attribute' => 'auditor_id',
                            'value' => 'auditor.username',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'auditor.username', [
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'attribute' => 'audit_time',
                            'filter' => DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'audit_time',
                                'value' => '',
                                'options' => ['readonly' => true, 'class' => 'form-control','style'=>'background-color:#fff;width:160px;'],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'locale' => [
                                        'separator' => '/',
                                    ],
                                    'endDate' => date('Y-m-d', time()),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'todayBtn' => 'linked',
                                    'clearBtn' => true,
                                ],
                            ]),
                            'value' => function ($model) {
                                return Yii::$app->formatter->asDatetime($model->audit_time);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'audit_remark',
                            'value' => 'audit_remark',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeTextInput($searchModel, 'audit_remark', [
                                'class' => 'form-control',
                            ]),
                        ],*/
                        [
                            'label' => '审核状态',
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
                            'attribute' => 'status',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model){
                                return \common\enums\StatusEnum::getValue($model->status);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\StatusEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]),
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'contentOptions' => ['style' => ['white-space' => 'nowrap']],
                            'template' => '{edit} {apply} {audit} {view} {status} {delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key){
                                    return Html::edit(['ajax-edit','id' => $model->id,'returnUrl' => Url::getReturnUrl()], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'apply' => function($url, $model, $key){
                                    if($model->audit_status == \common\enums\AuditStatusEnum::SAVE){
                                        return Html::edit(['ajax-apply','id'=>$model->id], '提审', [
                                            'class'=>'btn btn-success btn-sm',
                                            'onclick' => 'rfTwiceAffirm(this,"提交审核", "确定提交吗？");return false;',
                                        ]);
                                    }
                                },
                                'audit' => function($url, $model, $key){
                                    if($model->audit_status == \common\enums\AuditStatusEnum::PENDING){
                                        return Html::edit(['ajax-audit','id'=>$model->id], '审核', [
                                            'class'=>'btn btn-success btn-sm',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    }
                                },
                                'view' => function($url, $model, $key){
                                    return Html::a('查看', ['view', 'id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['class' => 'btn btn-warning btn-sm']);
                                },
                                'status' => function($url, $model, $key){
                                    if($model->audit_status == \common\enums\AuditStatusEnum::PASS) {
                                        return Html::status($model->status);
                                    }
                                },
                                'delete' => function($url, $model, $key){
                                    if($model->audit_status == \common\enums\AuditStatusEnum::SAVE) {
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