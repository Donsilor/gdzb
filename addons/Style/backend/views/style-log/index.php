<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('style_channel', '日志信息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body nav-tabs-custom">
    <h2 class="page-header">款式详情- <?php echo $style->style_sn?></h2>
    <?php echo Html::menuTab($tabList,$tab)?>
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
                                'label' => '款式编号',
                                'value' => function($model) use($style){
                                    return $style->style_sn;
                                },
                                'filter' => false,
                                'headerOptions' => [],
                            ],
                            [
                                'label' => '操作模块',
                                'attribute'=>'log_module',
                                'filter' => false,
                                'headerOptions' => [],
                            ],
                            [
                                'label' => '操作内容',
                                'attribute'=>'log_msg',
                                'filter' => false,
                                'headerOptions' => [],
                            ],

                            [
                                'attribute' => 'log_type',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1','style'=>'width:100px;'],
                                'value' => function ($model){
                                    return \common\enums\LogTypeEnum::getValue($model->log_type);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'log_type',\addons\Style\common\enums\LogTypeEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',

                                ]),
                            ],
                            [
                                'label' => '创建时间',
                                'filter' => false,
                                'value' => function($model){
                                    return Yii::$app->formatter->asDatetime($model->created_at);
                                }

                            ],

                            [
                                'label' => '操作人',
                                'attribute' => 'member.username',
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