<?php

use addons\Style\common\enums\AttrIdEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use kartik\date\DatePicker;
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
</div>
    <div class="modal-body"> 
       <div class="col-sm-12">
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_type')->dropDownList(Yii::$app->attr->valueMap(AttrIdEnum::MAT_GOLD_TYPE),['prompt'=>'请选择','disabled'=>true]) ?>
               </div>
               <div class="col-lg-6">
                   <?= $form->field($model, 'style_sn')->textInput(['disabled'=>true]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_name')->textInput() ?>
               </div>
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_sn')->textInput(['disabled'=>true]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_num')->textInput(['disabled'=>true]) ?>
               </div>
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_weight')->textInput() ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model, 'gold_price')->textInput() ?>
               </div>
               <div class="col-lg-6">
                   <?= $form->field($model, 'cost_price')->textInput(['disabled'=>true]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-12">
                   <?= $form->field($model, 'remark')->textarea() ?>
               </div>
           </div>
       </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>