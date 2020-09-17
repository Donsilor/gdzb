<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use addons\Warehouse\common\enums\BillTypeEnum;
use addons\Warehouse\common\forms\WarehouseBillWForm;
$form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['ajax-edit', 'id' => $model->id]),
        'fieldConfig' => [
                //'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
        ]
]);
$model = $model ?? new WarehouseBillWForm();
?>

<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
</div>
    <div class="modal-body"> 
       <div class="col-sm-12">
            <?= $form->field($model, 'bill_no')->textInput(['disabled'=>true, "placeholder"=>"系统自动生成"])?>
            <?= $form->field($model, 'bill_type')->dropDownList(BillTypeEnum::getMap(),['disabled'=>true])?>	        
	        <?= $form->field($model, 'to_warehouse_id')->label("盘点仓库")->widget(\kartik\select2\Select2::class, [
	                'data' => $model->getWarehouseDropdown(),
	                'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'disabled'=>$model->isNewRecord ? null:'disabled'
                    ],
            ])
	        ?>
            <?= $form->field($model, 'remark')->textArea(['options'=>['maxlength' => true]])?>
        </div>    
                   
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>