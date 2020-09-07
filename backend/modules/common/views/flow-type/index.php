<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\enums\AuditStatusEnum;
use kartik\daterange\DateRangePicker;
use addons\Style\common\enums\QibanTypeEnum;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '流程设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>

                </div>
            </div>
            <div class="box-body table-responsive">  
    <?php //echo Html::batchButtons()?>                  
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
                    'attribute' => 'name',
                    'value' => "name",
                    'filter' => true,
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-md-1'],
            ],
            [
                'attribute' => 'cate',
                'value' => function($model){
                    return \common\enums\FlowCateEnum::getValue($model->cate);
                },
                'filter' => Html::activeDropDownList($searchModel, 'cate',\common\enums\FlowCateEnum::getMap(), [
                    'prompt' => '全部',
                    'class' => 'form-control',
                ]),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
            ],
            [
                    'attribute' => 'method',
                    'value' => function($model){
                        return \common\enums\FlowMethodEnum::getValue($model->method);
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'method',\common\enums\FlowMethodEnum::getMap(), [
                            'prompt' => '全部',
                            'class' => 'form-control',
                    ]),
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-md-1'],
            ],
            [
                'attribute' => 'users',
                'value' => function($model){
                    $user_id_arr = explode(',',$model->users);
                    $users = '';
                    foreach ($user_id_arr as $user_id){
                        $user = Yii::$app->services->backendMember->findByIdWithAssignment($user_id);
                        $users .= $user['username']. ' , ';
                    }
                    $users = trim($users,', ');
                    return $users;

                },
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-2'],
            ],


            [
                    'attribute' => 'status',                    
                    'value' => function ($model){
                        return \common\enums\StatusEnum::getValue($model->status);
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\StatusEnum::getMap(), [
                        'prompt' => '全部',
                        'class' => 'form-control',
                        'style' => 'width:80px;'
                    ]),
                    'format' => 'raw',
                    'headerOptions' => [],
            ],
            [
                'attribute' => 'creator_id',
                'value' => function($model){
                    return $model->creator->username ?? '';
                },
                'headerOptions' => ['class' => 'col-md-1'],
                'filter' => false
            ],
            [
                'attribute'=>'created_at',
                'value'=>function($model){
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
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

            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($model){
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                },
                'filter' => DateRangePicker::widget([    // 日期组件
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'value' => $searchModel->updated_at,
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

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {ajax-edit} {status} {delete}',
                'buttons' => [
                    'edit' => function($url, $model, $key){
                        return Html::edit(['ajax-edit','id' => $model->id,'returnUrl' => Url::getReturnUrl()],'编辑',[
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                        ]);

                    },


                    'status' => function($url, $model, $key){
                        return Html::status($model->status);
                    },
                    'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                    }

                    
                ]
            ]
        ]
      ]);
    ?>
            </div>
        </div>
    </div>
</div>
