<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">创建推广数据</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'start_time')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'end_time')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'budget_cost')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'actual_cost')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'show_times')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'hits_times')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'visit_length')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'dialogue_times')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'client_times')->textInput()?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'order_times')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        </div>
    </div>
</div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
