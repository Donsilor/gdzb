<?php
use yii\widgets\ActiveForm;
use common\helpers\Html;
use common\helpers\Url;
use addons\Style\common\enums\StyleSexEnum;
use addons\Style\common\enums\QibanTypeEnum;
use addons\Supply\common\enums\PeiliaoTypeEnum;
use addons\Style\common\enums\AttrModuleEnum;
use addons\Style\common\enums\JintuoTypeEnum;
use addons\Supply\common\enums\PeishiTypeEnum;
use addons\Supply\common\enums\PeijianTypeEnum;
use addons\Supply\common\enums\TempletTypeEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'Curd', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body" style="padding:20px 50px">
                 <?= $form->field($model, 'purchase_id')->hiddenInput()->label(false) ?>
			     <?php if($model->style_id) {?> 
			         <div class="row">
    			         <?php if($model->isNewRecord) {?>      			    
            			 <div class="col-lg-3">         
                			<?= $form->field($model, 'goods_sn')->textInput() ?> 
            			 </div>
            			 <div class="col-lg-1">
                            <?= Html::button('查询',['class'=>'btn btn-info btn-sm','style'=>'margin-top:27px;','onclick'=>"searchGoods()"]) ?>
        			     </div>
        			     <?php }else{?>
        			     <div class="col-lg-4">         
                			<?= $form->field($model, 'goods_sn')->textInput(['disabled'=>'disabled']) ?> 
            			 </div>
        			     <?php }?>
        			     <div class="col-lg-4">
                            <?= $form->field($model, 'qiban_type')->dropDownList(QibanTypeEnum::getMap(),['disabled'=>true]) ?> 
            			 </div>  
        			     <div class="col-lg-4">
            			 	<?= $form->field($model, 'style_sex')->dropDownList(StyleSexEnum::getMap(),['disabled'=>true]) ?>
            			 </div>        			               			
            			 <div class="col-lg-4">       			 
            			 	<?= $form->field($model, 'style_cate_id')->dropDownList(Yii::$app->styleService->styleCate->getDropDown(),['disabled'=>true]) ?>
            			 </div>
            			 <div class="col-lg-4">
            			 	<?= $form->field($model, 'product_type_id')->dropDownList(Yii::$app->styleService->productType->getDropDown(),['disabled'=>true]) ?>
            			 </div> 
            			 <div class="col-lg-4">
            			 	<?= $form->field($model, 'jintuo_type')->dropDownList(\addons\Style\common\enums\JintuoTypeEnum::getMap(),['prompt'=>'请选择','onchange'=>"searchGoods()",'disabled'=>$model->qiban_type!= QibanTypeEnum::NON_VERSION]) ?>
            			 </div>           			 
        			 </div> 
        			 <div class="row">
            			 <div class="col-lg-4">
                                <?= $form->field($model, 'goods_name')->textInput() ?> 
            			 </div>
            			 <div class="col-lg-4">
            			 	<?= $form->field($model, 'goods_num')->textInput() ?>
            			 </div>
            			 <div class="col-lg-4">
            			 	<?= $form->field($model, 'cost_price')->textInput() ?>
            			 </div> 
        			 </div>
                     <div class="row">
                         <div class="col-lg-4">
                             <?= $form->field($model, 'peishi_type')->dropDownList(PeishiTypeEnum::getMap(), ['prompt' => '请选择', 'disabled' => $model->is_inlay == \addons\Style\common\enums\InlayEnum::No]) ?>
                         </div>
                         <div class="col-lg-4">
                             <?= $form->field($model, 'peiliao_type')->dropDownList(PeiliaoTypeEnum::getMap(), ['prompt' => '请选择'])->label("配料类型(只允许黄金/铂金/银进行配料)") ?>
                         </div>
                         <div class="col-lg-4">
                             <?= $form->field($model, 'peijian_type')->dropDownList(PeijianTypeEnum::getMap(), ['prompt' => '请选择'])?>
                         </div>

                     </div>
                     <div class="row">
                         <div class="col-lg-4">
                             <?= $form->field($model, 'templet_type')->dropDownList(TempletTypeEnum::getMap(), ['prompt' => '请选择'])?>
                         </div>
                     </div>
					<div style="margin-bottom:20px;">
                        <h3 class="box-title"> 属性信息</h3>
                    </div>
    			<?php }else{?>
        			<div class="row">
            			 <div class="col-lg-4">         
                			<?= $form->field($model, 'goods_sn')->textInput() ?> 
            			 </div>
            			 <div class="col-lg-1">
                            <?= Html::button('查询',['class'=>'btn btn-info btn-sm','style'=>'margin-top:27px;','onclick'=>"searchGoods()"]) ?>
        			     </div>
        			</div>
    			<?php }?>        			 

            	<?php
            	  $attr_list = \Yii::$app->styleService->attribute->module(AttrModuleEnum::PURCHASE)->getAttrListByCateId($model->style_cate_id,JintuoTypeEnum::getValue($model->jintuo_type,'getAttrTypeMap'),$model->is_inlay);
            	  foreach ($attr_list as $k=>$attr){ 
                      $attr_id  = $attr['id'];//属性ID                      
                      $is_require = $attr['is_require'];                     
                      $attr_name = \Yii::$app->attr->attrName($attr_id);//属性名称
                      
                      $_field = $is_require == 1 ? 'attr_require':'attr_custom';
                      $field = "{$_field}[{$attr_id}]";
                      switch ($attr['input_type']){
                          case common\enums\InputTypeEnum::INPUT_TEXT :{
                              $input = $form->field($model,$field)->textInput()->label($attr_name);
                              break;
                          }
                          default:{
                              if($model->qiban_type == QibanTypeEnum::NON_VERSION) {
                                  //获取款式属性值列表
                                  $attr_values = Yii::$app->styleService->styleAttribute->getDropdowns($model->style_id,$attr_id);
                              }else{
                                  //获取起版属性值列表
                                  $attr_values = Yii::$app->styleService->qibanAttribute->getDropdowns($model->style_id,$attr_id);    
                              }
                              if(empty($attr_values)) {
                                  $attr_values = Yii::$app->styleService->attribute->getValuesByAttrId($attr_id);
                              }
                              $input = $form->field($model,$field)->dropDownList($attr_values,['prompt'=>'请选择'])->label($attr_name);
                              break;
                          }
                      }//end switch               
                      $collLg = 4;
                ?>
                <?php if ($k % 3 == 0){ ?><div class="row"><?php }?>
						<div class="col-lg-<?=$collLg?>"><?= $input ?></div>
                <?php if(($k+1) % 3 == 0 || ($k+1) == count($attr_list)){?></div><?php }?>
              <?php 
                  }//end foreach $attr_list
               ?>            
               <!-- ./box-body -->
                <?php if($model->style_id) {?>

                    <div style="margin: 0px 0 20px 0;">
                        <h3 class="box-title"> 其他信息<span style="font-size: 16px;color:grey;">（单据导出需填信息）</span></h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'main_peishi_way')->dropDownList(\addons\Warehouse\common\enums\PeiShiWayEnum::getMap(), ['prompt' => '请选择']) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'second_peishi_way1')->dropDownList(\addons\Warehouse\common\enums\PeiShiWayEnum::getMap(), ['prompt' => '请选择'])?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'second_peishi_way2')->dropDownList(\addons\Warehouse\common\enums\PeiShiWayEnum::getMap(), ['prompt' => '请选择'])?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'main_stone_sn')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'second_stone_sn1')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'second_stone_sn2')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'peiliao_way')->dropDownList(\addons\Warehouse\common\enums\PeiLiaoWayEnum::getMap(), ['prompt' => '请选择'])?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'gold_price')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'gold_cost_price')->textInput() ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'gold_loss')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'suttle_weight')->textInput() ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'peijian_way')->dropDownList(\addons\Warehouse\common\enums\PeiJianWayEnum::getMap(), ['prompt' => '请选择'])?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'peijian_cate')->dropDownList(\addons\Warehouse\common\enums\PeiJianCateEnum::getMap(), ['prompt' => '请选择'])?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_material')->textInput() ?>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_num')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_weight')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_price')->textInput() ?>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'peishi_fee')->textInput() ?>
                        </div>

                        <div class="col-lg-4">
                            <?= $form->field($model, 'peishi_amount')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'jiagong_fee')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'factory_mo')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'factory_cost_price')->textInput() ?>
                        </div>

                        <div class="col-lg-4">
                            <?= $form->field($model, 'ke_gong_fee')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'biaomiangongyi_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'fense_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'bukou_fee')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'penrasa_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'edition_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'gaitu_fee')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'penla_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_fee')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'cert_fee')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'unit_cost_price')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'factory_total_price')->textInput() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'company_total_price')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4">
                            <?= $form->field($model, 'xianqian_price')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'stone_info')->textarea() ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parts_remark')->textarea() ?>
                        </div>
                         <div class="col-lg-4">
                            <?= $form->field($model, 'remark')->textarea() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'goods_image')->widget(common\widgets\webuploader\Files::class, [
                                'config' => [
                                ]
                            ]); ?>
                        </div>
                    </div>
            	<?php }?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
function searchGoods() {
   var goods_sn = $.trim($("#purchasegoodsform-goods_sn").val());
   var jintuo_type = $("#purchasegoodsform-jintuo_type").val();
   if(!goods_sn) {
	    rfMsg("请输入款号或起版号");
        return false;
   }
   var url = "<?= Url::buildUrl(\Yii::$app->request->url,[],['goods_sn','search','jintuo_type'])?>&search=1&goods_sn="+goods_sn+"&jintuo_type="+jintuo_type;
   window.location.href = url;
}
</script>
