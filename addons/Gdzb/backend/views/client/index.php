<?php

use common\helpers\Url;
use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '客户数据列表';
$this->params['breadcrumbs'][] = ['label' => '专题列表'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit'], '创建'); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover rf-table'],
                    'rowOptions' => [
                        'class' => 'input-edit',
                        'data-url' => Url::to('ajax-edit')
                    ],
                    'options' => ['style' => 'white-space:nowrap;'],
                    'showFooter' => true,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'attribute' => 'id',
                            'headerOptions' => [],
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'nickname',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'nickname',
                                    'data-value' => $model->nickname,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'sex',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'sex',
                                    'data-value' => $model->sex,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'phone',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'phone',
                                    'data-value' => $model->phone,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'qq',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'qq',
                                    'data-value' => $model->qq,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'area',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'area',
                                    'data-value' => $model->area,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'intention',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'intention',
                                    'data-value' => $model->intention,
                                ];
                            },
                        ],
                        [
                            'attribute' => 'budget',
                            'headerOptions' => [],
                            'contentOptions' => function ($model) {
                                return [
                                    'class' => 'input-edit-item',
                                    'data-attribute' => 'budget',
                                    'data-value' => $model->budget,
                                ];
                            },
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
                            'attribute' => 'created_at',
                            'filter' => false,
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
                            'value' => function ($model) {
                                return Yii::$app->formatter->asDatetime($model->created_at);
                            }
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
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['style' => ['white-space' => 'nowrap']],
                            'template' => '{destroy}',
                            'buttons' => [
//                                'ajax-edit' => function ($url, $model, $key) {
//                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
//                                        'data-toggle' => 'modal',
//                                        'data-target' => '#ajaxModal',
//                                    ]);
//                                },
//                                'address' => function ($url, $model, $key) {
//                                    return Html::linkButton(['address/index', 'member_id' => $model->id], '收货地址');
//                                },
//                                'recharge' => function ($url, $model, $key) {
//                                    return Html::linkButton(['recharge', 'id' => $model->id], '充值', [
//                                        'data-toggle' => 'modal',
//                                        'data-target' => '#ajaxModal',
//                                    ]);
//                                },
//                                'edit' => function ($url, $model, $key) {
//                                    return Html::edit(['edit', 'id' => $model->id]);
//                                },
//                                'view' => function ($url, $model, $key) {
//                                    return Html::a('查看', ['view', 'id' => $model->id,'returnUrl'=>Url::getReturnUrl()], ['class' => 'btn btn-warning btn-sm']);
//                                },
//                                'status' => function ($url, $model, $key) {
//                                    return Html::status($model->status);
//                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".input-edit .input-edit-item")
            // .each(function () {
            // $(this)
                .click(function () {

                if ($(this).find("input").length > 0) {
                    return;
                }

                var dataValue = $(this).attr('data-value');
                var inputBox = $("<input type='text'>");
                inputBox.attr('name', "item");
                inputBox.attr('class', "form-control");
                inputBox.css('height', '30px')

                $(this).empty()
                $(this).append(inputBox)

                inputBox.trigger('focus').val(dataValue)

                return true;
            });
        // });
    });

    $(".input-edit .input-edit-item").on('blur', '.form-control', function (e) {
        var parent = $(this).parent();
        var value = $(this).val();
        var oldValue = parent.attr('data-value');

        parent.attr('data-value', value);
        parent.text(value)

        if(value===oldValue) {
            return false;
        }

        var inputEdit = parent.parent();
        var postUrl = inputEdit.attr('data-url');
        var id = inputEdit.attr('data-key');
        var attr = parent.attr('data-attribute');

        // postUrl = baseBackend + '/' + postUrl;
        $.ajax({
            type: "POST",
            url: postUrl+'?id='+id,
            dataType: 'json',
            data: "_csrf-backend=" + $('meta[name=csrf-token]').attr("content") + '&Client['+attr+']=' + value,
            success: function(msg){
                if(msg.error == 0) {
                    //window.location.reload();
                } else {
                    alert(msg.msg);
                }
            }
        });
        // $(this).parent().html(value);

    });
</script>