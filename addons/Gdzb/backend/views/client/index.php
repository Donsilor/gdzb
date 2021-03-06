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
            <?php
            $rowstr = <<<DOM
<tr class="input-edit input-add" data-url="ajax-edit" data-key="">
<td></td>
<td class="input-edit-item" data-attribute="Client[nickname]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[sex]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[phone]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[qq]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[area]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[intention]" data-value="">&nbsp;</td>
<td class="input-edit-item" data-attribute="Client[budget]" data-value="">&nbsp;</td>
<td><input type="button" class="btn btn-success btn-sm" value="保存"/></td>
<td>&nbsp;</td>
</tr>
DOM;
            ?>
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
                    'emptyText' => $rowstr,
                    'beforeRow' => function($row, $key, $index, $grid) use($rowstr) {
                    if($index!==0) {
                        return null;
                    }
                    return $rowstr;
                    },
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
                                    'data-attribute' => 'Client[nickname]',
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
                                    'data-attribute' => 'Client[sex]',
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
                                    'data-attribute' => 'Client[phone]',
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
                                    'data-attribute' => 'Client[qq]',
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
                                    'data-attribute' => 'Client[area]',
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
                                    'data-attribute' => 'Client[intention]',
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
                                    'data-attribute' => 'Client[budget]',
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
    function generateInput() {
        if ($(this).find("input").length > 0) {
            return;
        }

        var dataValue = $(this).attr('data-value');
        var dataAttribute = $(this).attr('data-attribute');

        var inputBox = $("<input type='text'>");
        inputBox.attr('type', "text");
        inputBox.attr('name', dataAttribute);
        inputBox.attr('class', "form-control");
        inputBox.css('height', '30px')

        $(this).empty()
        $(this).append(inputBox)

        inputBox.trigger('focus').val(dataValue)

        return true;
    }

    function editInput(e) {
        var parent = $(this).parent();
        var value = $(this).val();
        var oldValue = parent.attr('data-value');

        var add = false
        if(value==="保存") {
            add = true;
        }

        parent.attr('data-value', value);

        var inputEdit = parent.parent();
        var postUrl = inputEdit.attr('data-url');
        var id = inputEdit.attr('data-key');

        var data = {};
        data['_csrf-backend'] = $('meta[name=csrf-token]').attr("content");

        inputEdit.find('input').each(function () {

            var attr = $(this).attr('name');
            var value = $(this).val();

            data[attr] = value;
        });

        data['Client[special_id]'] = '<?= $special_id ?>';

        if(id!=="") {
            parent.text(value)
        }
        if(value===oldValue) {
            return false;
        }
        if(add===false && id==="") {
            return false;
        }

        $.ajax({
            type: "POST",
            url: postUrl+'?id='+id,
            dataType: 'json',
            data: data,
            success: function(msg){
                if(msg.error == 0) {
                    //window.location.reload();
                } else {
                    alert(msg.msg);
                }
            }
        });
        // $(this).parent().html(value);
    }

    $(function () {
        $(".input-edit .input-edit-item")
            .click(generateInput)
            .on('blur', '.form-control', editInput);

        //添加
        $(".input-add .input-edit-item").trigger("click");

        $(".input-add .btn").click(editInput);

        // $(".input-edit .input-edit-item")
        // $(this).parent().html(value);
    });



</script>