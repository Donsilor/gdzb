<?php

use common\helpers\Html;
use yii\grid\GridView;

$this->title = '订单日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body nav-tabs-custom">
    <h2 class="page-header">退货单详情 - <?php echo $refund->refund_sn ?? ''?></h2>
    <?php echo Html::menuTab($tabList,$tab)?>
    <div class="tab-content">
        <div class="row col-xs-14">
            <div class="box">
                <div class="box-body table-responsive">
                  <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-hover'],
                        'showFooter' => false,//显示footer行
                        'id'=>'grid',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'id',
                                'filter' => true,
                                'format' => 'raw',
                                'headerOptions' => ['width'=>'80'],
                            ],
                            [
                                'attribute'=>'refund_sn',
                                'value' => function($model) use($refund){
                                    return $refund->refund_sn;
                                },
                                'filter' => false,
                                'headerOptions' => [],
                            ],
                            [
                                'attribute'=>'log_module',
                                'filter' => false,
                                'headerOptions' => [],
                            ],
                            [
                                'attribute'=>'log_msg',
                                'filter' => true,
                                'headerOptions' => [],
                            ],

                            [
                                'attribute' => 'log_type',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1','style'=>'width:100px;'],
                                'value' => function ($model){
                                    return common\enums\LogTypeEnum::getValue($model->log_type);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'log_type',\common\enums\LogTypeEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',

                                ]),
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => false,
                                'value' => function($model){
                                    return Yii::$app->formatter->asDatetime($model->created_at);
                                }

                            ],
                            [
                                'attribute' => 'creator',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeTextInput($searchModel, 'creator', [
                                    'class' => 'form-control',
                                ]),

                            ],


                        ]
                    ]); ?>
                </div>
            </div>
        <!-- box end -->
        </div>
    </div>
</div>