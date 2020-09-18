<?php
namespace addons\Gdzb\backend\controllers;

use addons\Sales\common\enums\OrderStatusEnum;
use addons\Gdzb\common\forms\OrderGoodsForm;
use addons\Gdzb\common\models\Order;
use addons\Gdzb\common\models\OrderGoods;
use addons\Style\common\enums\QibanTypeEnum;
use common\enums\ConfirmEnum;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\traits\Curd;
use Yii;

class OrderGoodsController extends BaseController
{
    use Curd;
    /**
     * @var PurchaseGoodsForm
     */
    public $modelClass = OrderGoodsForm::class;

    /**
     * 编辑/创建
     * @var PurchaseGoodsForm $model
     * @return mixed
     */
    public function actionAjaxEdit()
    {
        $this->layout = '@backend/views/layouts/iframe';
        $id = Yii::$app->request->get('id');
        $order_id = Yii::$app->request->get('order_id');
        $model = $this->findModel($id);
        $model = $model ?? new OrderGoodsForm();
        $model->order_id = $order_id;
        $order = Order::find()->where(['id'=>$order_id])->one();

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->validate()) {
                return ResultHelper::json(422, $this->getError($model));
            }
            try{
                $trans = Yii::$app->trans->beginTransaction();
                $model->warehouse_id = $order->warehouse_id;
                if(false === $model->save()){
                    throw new \Exception($this->getError($model));
                }
                //没有货号，生成货号
                if(!$model->goods_sn){
                    Yii::$app->gdzbService->orderGoods->createGoodsSn($model);
                }else{
                    //判断货品是否库存，如果是库存，则改变库存货品状态
                    Yii::$app->gdzbService->orderGoods->syncGoods($model);
                }
                //更新采购汇总：总金额和总数量
                Yii::$app->gdzbService->order->orderSummary($model->order_id);
                $trans->commit();
                $this->redirect(Yii::$app->request->referrer);
            }catch (\Exception $e){
                $trans->rollBack();
                $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
            }
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }




    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {

        $order_id = Yii::$app->request->get('order_id');
        try{
            $trans = Yii::$app->trans->beginTransaction();
            $order = Order::find()->where(['id'=>$order_id])->one();
            if($order->order_status == OrderStatusEnum::CONFORMED) {
                throw new \Exception("订单已审核,不允许删除",422);
            }
            $model = $this->findModel($id);
            if (!$model->delete()) {
                throw new \Exception("删除失败",422);
            }
            //如果货号已在库存，则还原
            Yii::$app->gdzbService->orderGoods->syncGoods($model,'del');


            //更新单据汇总
            Yii::$app->gdzbService->order->orderSummary($order_id);
            $trans->commit();
            return $this->message("删除成功", $this->redirect($this->returnUrl));
        }catch (\Exception $e) {

            $trans->rollback();
            return $this->message($e->getMessage(), $this->redirect($this->returnUrl), 'error');
        }
    }






    /**
     * @return mixed
     * 布产
     */
    public function actionBuchan(){
        $ids = \Yii::$app->request->get('ids');
        if(!is_object($ids)) {
            $ids = StringHelper::explodeIds($ids);
        }
        try{
            $trans = Yii::$app->db->beginTransaction();
            $order_goods = OrderGoods::find()->where(['id'=>$ids])->all();
            foreach ($order_goods as $model){
                if($model['is_stock'] == IsStockEnum::YES){
                    return $this->message("商品{$model->id}为现货，不能布产", $this->redirect(\Yii::$app->request->referrer), 'warning');
                }
                if($model['is_bc'] == ConfirmEnum::YES){
                    return $this->message("商品{$model->id}为已经布产", $this->redirect(\Yii::$app->request->referrer), 'warning');
                }

                if($model->qiban_type == QibanTypeEnum::NON_VERSION ){
                    //非起版
                    $is_exeist = Yii::$app->styleService->style->isExist($model->style_sn);
                    if(!$is_exeist){
                        return $this->message("款式库没有此款号{$model->style_sn},请确认", $this->redirect(\Yii::$app->request->referrer), 'warning');
                    }
                }else{
                    //起版
                    $is_exeist = Yii::$app->styleService->qiban->isExist($model->qiban_sn);
                    if(!$is_exeist){
                        return $this->message("起版库没有此起版号{$model->qiban_sn},请确认", $this->redirect(\Yii::$app->request->referrer), 'warning');
                    }
                }


                $goods = [
                    'goods_name' =>$model->goods_name,
                    'goods_num' =>$model->goods_num,
                    'order_detail_id'=>$model->order_id,
                    'order_detail_id' => $model->id,
                    'order_sn'=>$model->order->order_sn,
                    'from_type' => FromTypeEnum::ORDER,
                    'style_sn' => $model->style_sn,
                    'bc_status' => BuChanEnum::INITIALIZATION,
                    'qiban_sn' => $model->qiban_sn,
                    'qiban_type'=>$model->qiban_type,
                    'jintuo_type'=>$model->jintuo_type,
                    'style_sex' =>$model->style_sex,
                    'is_inlay' =>$model->is_inlay,
                    'product_type_id'=>$model->product_type_id,
                    'style_cate_id'=>$model->style_cate_id,
                ];
                $goods_attrs = OrderGoodsAttribute::find()->where(['id'=>$model->id])->asArray()->all();
                $produce = Yii::$app->supplyService->produce->createProduce($goods ,$goods_attrs);
                if($produce) {
                    $model->bc_status = BuChanEnum::INITIALIZATION;
                    $model->is_bc = ConfirmEnum::YES;
                    $model->produce_sn = $produce->produce_sn;
                }
                if(false === $model->save()) {
                    throw new \Exception($this->getError($model),422);
                }
            }
            $trans->commit();
            return $this->message('操作成功', $this->redirect(\Yii::$app->request->referrer), 'success');
        }catch (\Exception $e){
            $trans->rollBack();
            return $this->message($e->getMessage(), $this->redirect(Yii::$app->request->referrer), 'error');
        }
    }

}

