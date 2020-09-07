<?php

use common\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\order\order */
/* @var $form yii\widgets\ActiveForm */

$this->title = '金料详情';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body nav-tabs-custom">
    <h2 class="page-header"><?php echo $this->title;?> - <?= $model->gold_sn?> - <?= \addons\Warehouse\common\enums\GoldStatusEnum::getValue($model->gold_status)?></h2>
    <?php echo Html::menuTab($tabList,$tab)?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="col-xs-6">
                    <div class="box">
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_sn') ?>：</td>
                                    <td><?= $model->gold_sn ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_name') ?>：</td>
                                    <td><?= $model->gold_name ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('style_sn') ?>：</td>
                                    <td><?= $model->style_sn ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_type') ?>：</td>
                                    <td><?= Yii::$app->attr->valueName($model->gold_type)??"" ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_num') ?>：</td>
                                    <td><?= $model->gold_num ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_weight') ?>：</td>
                                    <td><?= $model->gold_weight ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('gold_price') ?>：</td>
                                    <td><?= $model->gold_price ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('cost_price') ?>：</td>
                                    <td><?= $model->cost_price ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('supplier_id') ?>：</td>
                                    <td><?= $model->supplier->supplier_name??"" ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6" style="padding: 0px;">
                    <div class="box" style="margin-bottom: 0px;">
                        <div class="box-body table-responsive" >
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('put_in_type') ?>：</td>
                                    <td><?= \addons\Warehouse\common\enums\PutInTypeEnum::getValue($model->put_in_type) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('warehouse_id') ?>：</td>
                                    <td><?= $model->warehouse->name??""?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right">入库单号：</td>
                                    <td><?= $bill['bill_no']??""?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right">采购收货单号：</td>
                                    <td><?= $bill['receipt_no']??""?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right">采购单号：</td>
                                    <td><?= $bill['purchase_sn']??""?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('status') ?>：</td>
                                    <td><?= \common\enums\StatusEnum::getValue($model->status)?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('creator_id') ?>：</td>
                                    <td><?= $model->creator ? $model->creator->username:''  ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('created_at') ?>：</td>
                                    <td><?= \Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 text-right"><?= $model->getAttributeLabel('remark') ?>：</td>
                                    <td><?= $model->remark ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer text-center">
            <?php
                echo Html::button('导出', [
                    'class'=>'btn btn-success btn-sm',
                    'onclick' => 'batchExport()',
                ]);
                echo '&nbsp;';
            ?>
            <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>
</div>
<script>
    function batchExport() {
        window.location.href = "<?= \common\helpers\Url::buildUrl('export',[],['ids'])?>?ids=<?php echo $model->id ?>";
    }
</script>


