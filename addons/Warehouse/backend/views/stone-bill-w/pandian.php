<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['pandian','id' => $model->id]),
        'fieldConfig' => [
                
        ]
]);
?>
<div class="box-body nav-tabs-custom">
    <h2 class="page-header">石料盘点 - <?php echo $model->bill_no?></h2>
    <div class="box">
        <div class=" table-responsive">
            <table class="table table-hover">
                <tr>
                    <td class="col-xs-1 text-right no-border-top">石料编号：</td>
                    <td class="col-xs-4 text-left no-border-top"><?= $form->field($model, 'stone_sn')->textInput()->label(false)?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right no-border-top">石料粒数：</td>
                    <td class="col-xs-4 text-left no-border-top"><?= $form->field($model, 'stone_num')->textInput()->label(false)?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right no-border-top">石料总重：</td>
                    <td class="col-xs-4 text-left no-border-top"><?= $form->field($model, 'stone_weight')->textInput()->label(false)?></td>
                    <td class="text-left no-border-top"><button class="btn btn-primary" type="submit">盘点</button></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">盘点仓库：</td>
                    <td><?= $model->toWarehouse->name ??'' ?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">盘点材质：</td>
                    <td><?= Yii::$app->attr->valueName($model->billW->stone_type) ??'' ?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">应盘数量：</td>
                    <td style='color:green'><?= $model->billW->should_num ?? 0;?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">实盘数量：</td>
                    <td style='color:red'> <?= $model->billW->actual_num ?? 0;?> </td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">应盘粒数：</td>
                    <td style='color:green'><?= $model->billW->should_grain ?? 0;?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">实盘粒数：</td>
                    <td style='color:red'> <?= $model->billW->actual_grain ?? 0;?> </td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">应盘重量(ct)：</td>
                    <td style='color:green'><?= $model->billW->should_weight ?? 0;?></td>
                </tr>
                <tr>
                    <td class="col-xs-1 text-right">实盘重量(ct)：</td>
                    <td style='color:red'> <?= $model->billW->actual_weight ?? 0;?> </td>
                </tr>
            </table>
        </div>                
    </div>
</div>
<?php ActiveForm::end(); ?>
