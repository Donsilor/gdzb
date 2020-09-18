<?php

namespace addons\Gdzb\backend\controllers;

use addons\Gdzb\common\forms\ConsigneeForm;
use addons\Gdzb\common\forms\InvoiceForm;
use addons\Sales\common\enums\IsReturnEnum;
use addons\Sales\common\enums\OrderStatusEnum;

use addons\Sales\common\enums\ReturnTypeEnum;
use addons\Gdzb\common\forms\OrderForm;
use addons\Gdzb\common\forms\OrderGoodsForm;
use addons\Sales\common\forms\ReturnForm;
use addons\Sales\common\models\SalesReturn;
use common\enums\AuditStatusEnum;
use common\enums\ConfirmEnum;
use common\helpers\Url;
use Yii;
use common\traits\Curd;
use addons\Gdzb\common\models\Order;
use common\models\base\SearchModel;
use addons\Gdzb\common\models\OrderGoods;
use common\helpers\ResultHelper;
use addons\Sales\common\models\Customer;
use common\enums\LogTypeEnum;
use common\helpers\Auth;

/**
 * Default controller for the `order` module
 */
class OrderController extends BaseController
{
    use Curd;
    
    /**
     * @var Order
     */
    public $modelClass = OrderForm::class;

    public function actionTest()
    {   
        $res = Auth::verify('special:1001');
        var_dump($res);exit;
        $order_no = '130311942049';
        Yii::$app->jdSdk->getOrderInfo($order_no);
        exit;
    }    
    /**
     * Renders the index view for the module
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $order_status = Yii::$app->request->get('order_status', -1);        
        $searchModel = new SearchModel([
                'model' => $this->modelClass,
                'scenario' => 'default',
                'partialMatchAttributes' => [], // 模糊查询
                'defaultOrder' => [
                        'id' => SORT_DESC,
                ],
                'pageSize' => $this->pageSize,
                'relations' => [
                        'address' => [],
                        'creator' =>['username'],
                ]
        ]);
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['created_at']);
        $searchParams = Yii::$app->request->queryParams['SearchModel'] ?? [];
        if($order_status != -1) {
            $dataProvider->query->andWhere(['=', 'order_status', $order_status]);
        }
        // 联系人搜索
        if(!empty($searchParams['customer_mobile'])) {
            $where = [ 'or',
                    ['like', Order::tableName().'.customer_mobile', $searchParams['customer_mobile']],

            ];            
            $dataProvider->query->andWhere($where);
        }        
        //创建时间过滤
        if (!empty($searchParams['order_time'])) {
            list($start_date, $end_date) = explode('/', $searchParams['order_time']);
            $dataProvider->query->andFilterWhere(['between', Order::tableName().'.order_time', strtotime($start_date), strtotime($end_date) + 86400]);
        }        
        return $this->render($this->action->id, [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }
    /**
     * 创建订单
     * @return array|mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id');
        $returnUrl = Yii::$app->request->get('returnUrl',Url::to(['index']));
        $model = $this->findModel($id);
        $model = $model ?? new OrderForm();
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('OrderForm');
            $model->consignee_info = $model->setConsigneeInfo($post);

            $isNewRecord = $model->isNewRecord;
            try{
                $trans = Yii::$app->trans->beginTransaction();
                $model = Yii::$app->gdzbService->order->createOrder($model);
                $trans->commit();
                return $isNewRecord
                    ? $this->message("创建成功", $this->redirect(['view', 'id' => $model->id]), 'success')
                    : $this->message("保存成功", $this->redirect($returnUrl), 'success');

            }catch (\Exception $e) {
                $trans->rollback();
                return $this->message($e->getMessage(), $this->redirect(Yii::$app->request->referrer), 'error');
            }
        }
        //初始化
        $model->getConsigneeInfo($model);

        return $this->render($this->action->id, [
            'model' => $model,
        ]);

    }
    /**
     * 查询客户信息
     * @return array|\yii\db\ActiveRecord|NULL
     */
    public function actionAjaxGetCustomer()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $mobile = Yii::$app->request->get('mobile');
        $email = Yii::$app->request->get('email');
        $channel_id = Yii::$app->request->get('channel_id');
        if(empty($mobile) && empty($email)) {
            return ResultHelper::json(200,'查询成功',[]);
        }        
        $model = Customer::find()->select(['id','realname','mobile','email','level','source_id'])
            ->where(['channel_id'=>$channel_id])
            ->andFilterWhere(['=','mobile',$mobile])
            ->andFilterWhere(['=','email',$email])
            ->asArray()->one();
        return ResultHelper::json(200,'查询成功',$model);
    }
    /**
     * 详情展示页
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $id = Yii::$app->request->get('id');        
        $model = $this->findModel($id);
        $dataProvider = null;
        if (!is_null($id)) {
            $searchModel = new SearchModel([
                    'model' => OrderGoodsForm::class,
                    'scenario' => 'default',
                    'partialMatchAttributes' => [], // 模糊查询
                    'defaultOrder' => [
                         'id' => SORT_DESC
                    ],
                    'pageSize' => 1000,
            ]);
            
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andWhere(['=', 'order_id', $id]);
            $dataProvider->setSort(false);
        }
        return $this->render($this->action->id, [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'tab'=>Yii::$app->request->get('tab',1),
                'tabList'=>Yii::$app->gdzbService->order->menuTabList($id,$this->returnUrl),
                'returnUrl'=>$this->returnUrl,
                'return'=>!empty($return)?json_encode($return):"",
        ]);
    }
    /**
     * 物流轨迹日志
     */
    public function actionLogistics()
    {
        $this->layout = '@backend/views/layouts/iframe';
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $logistics = \Yii::$app->logistics->kd100($model->express_no, $model->express->api_code ?? null, true);
        return $this->render($this->action->id, [
                'model' => $model,
                'logistics'=>$logistics,
        ]);
    }
    /**
     * 取消订单
     * @throws Exception
     * @return array|mixed
     */
    public function actionDelete()
    {
        $ids = Yii::$app->request->post("ids", []);
        if(empty($ids) || !is_array($ids)) {
            return ResultHelper::json(422, '提交数据异常');
        } 
        
        try {
            $trans = Yii::$app->db->beginTransaction();                      
            foreach ($ids as $id) {
                
            }
            $trans->commit();
            return ResultHelper::json(200, '操作成功');   
        } catch (\Exception $e) {
            $trans->rollBack();
            return ResultHelper::json(422, '取消失败！'.$e->getMessage());
        }        
              
    }


    /**
     * @return mixed
     * 申请审核
     */
    public function actionAjaxApply(){
        $id = \Yii::$app->request->get('id');
        $order_goods_count = OrderGoods::find()->where(['order_id'=>$id])->count();
        if($order_goods_count == 0){
            return $this->message('订单没有明细', $this->redirect(\Yii::$app->request->referrer), 'error');
        }
        $this->modelClass = Order::class;
        $model = $this->findModel($id);
        $model = $model ?? new Order();
        if($model->order_status != OrderStatusEnum::SAVE){
            return $this->message('订单不是保存状态', $this->redirect(\Yii::$app->request->referrer), 'error');
        }

        try{
            $trans = Yii::$app->db->beginTransaction();
            $model->order_status = OrderStatusEnum::PENDING;
            $model->audit_status = AuditStatusEnum::PENDING;
            if(false === $model->save()){
                throw new \Exception($this->getError($model));
            }

            //订单日志
            $log = [
                    'order_id' => $model->id,
                    'order_sn' => $model->order_sn,
                    'order_status' => $model->order_status,
                    'log_type' => LogTypeEnum::ARTIFICIAL,
                    'log_time' => time(),
                    'log_module' => '申请审核',
                    'log_msg' => '订单提交申请',
            ];
            \Yii::$app->gdzbService->orderLog->createOrderLog($log);
            $trans->commit();
            return $this->message('操作成功', $this->redirect(\Yii::$app->request->referrer), 'success');
        }catch (\Exception $e){
            $trans->rollBack();
            return $this->message($e->getMessage(), $this->redirect(Yii::$app->request->referrer), 'error');
        }

    }

    /**
     * 订单审核
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionAjaxAudit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $this->modelClass = Order::class;
        $model = $model ?? new Order();
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            try{
                $trans = Yii::$app->trans->beginTransaction();

                $model->auditor_id = \Yii::$app->user->id;
                $model->audit_time = time();
                if($model->audit_status == AuditStatusEnum::PASS){
                    $model->order_status = OrderStatusEnum::CONFORMED;
                }else{
                    $model->order_status = OrderStatusEnum::SAVE;
                }
                if(false === $model->save()){
                    return $this->message($this->getError($model), $this->redirect(\Yii::$app->request->referrer), 'error');
                }

                //同步客户
                Yii::$app->gdzbService->order->createSyncCustomer($model);

                //同步商品
                Yii::$app->gdzbService->order->createSyncGoods($id);



                $log_msg = "订单审核：".AuditStatusEnum::getValue($model->audit_status)."，审核备注：".$model->audit_remark;
                
                //订单日志
                $log = [
                        'order_id' => $model->id,
                        'order_sn' => $model->order_sn,
                        'order_status' => $model->order_status,
                        'log_type' => LogTypeEnum::ARTIFICIAL,
                        'log_time' => time(),
                        'log_module' => '订单审核',
                        'log_msg' => $log_msg,
                ];
                \Yii::$app->gdzbService->orderLog->createOrderLog($log);
                $trans->commit();
            }catch (\Exception $e){
                $trans->rollBack();
                return $this->message("审核失败:". $e->getMessage(),  $this->redirect(Yii::$app->request->referrer), 'error');
            }
            return $this->message("保存成功", $this->redirect(Yii::$app->request->referrer), 'success');
        }
        $model->audit_status = AuditStatusEnum::PASS;
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }



    /**
     * 修改收货地址
     * @return \yii\web\Response|mixed|string|string
     */
    public function actionAjaxEditAddress()
    {
        $id = Yii::$app->request->get('id');
        $this->modelClass = ConsigneeForm::class;
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            try{
                $trans = Yii::$app->trans->beginTransaction();
                $model->consignee_info = $model->setConsigneeInfo($model);

                if(false === $model->save()) {
                    throw new \Exception($this->getError($model));
                }                
                $trans->commit();
                
                return $this->message("保存成功", $this->redirect(Yii::$app->request->referrer), 'success');                
            }catch (\Exception $e) {
                $trans->rollback();
                return $this->message($e->getMessage(), $this->redirect(Yii::$app->request->referrer), 'error');
            }
        }
        //初始化
        $model->getConsigneeInfo($model);
        return $this->renderAjax($this->action->id, [
                'model' => $model,
        ]);
    }
    
    /**
     * 修改发票
     * @return \yii\web\Response|mixed|string|string
     */
    public function actionAjaxEditInvoice()
    {
        $id = Yii::$app->request->get('id');
        $this->modelClass = InvoiceForm::class;
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            try{
                $trans = Yii::$app->trans->beginTransaction();
                $model->invoice_info = $model->setInvoiceInfo($model);

                if(false === $model->save()) {
                    throw new \Exception($this->getError($model));
                }
                $trans->commit();

                return $this->message("保存成功", $this->redirect(Yii::$app->request->referrer), 'success');
            }catch (\Exception $e) {
                $trans->rollback();
                return $this->message($e->getMessage(), $this->redirect(Yii::$app->request->referrer), 'error');
            }
        }
        //初始化
        $model->getInvoiceInfo($model);
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 退款
     * @var SalesReturn $model
     * @throws
     * @return mixed
     */
    public function actionReturn()
    {
        $this->layout = '@backend/views/layouts/iframe';
        $id = Yii::$app->request->get('id');
        $ids = Yii::$app->request->post('ids');
        $model = new ReturnForm();
        $model->ids = $ids;
        $order = $this->findModel($id) ?? new Order();
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->validate()) {
                //return ResultHelper::json(422, $this->getError($model));
            }
            try{
                $trans = Yii::$app->trans->beginTransaction();

                \Yii::$app->salesService->return->createReturn($model, $order);
                $trans->commit();
                Yii::$app->getSession()->setFlash('success','保存成功');
                return ResultHelper::json(200, '保存成功');
            }catch (\Exception $e){
                $trans->rollBack();
                return ResultHelper::json(422, $e->getMessage());
            }
        }
        $dataProvider = null;
        if (!is_null($id)) {
            $searchModel = new SearchModel([
                'model' => OrderGoodsForm::class,
                'scenario' => 'default',
                'partialMatchAttributes' => [], // 模糊查询
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
                'pageSize' => 1000,
            ]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andWhere(['=', 'order_id', $id]);
            $dataProvider->query->andWhere(['=', 'is_return', IsReturnEnum::SAVE]);
            $dataProvider->setSort(false);
        }
        $model->is_quick_refund = ConfirmEnum::NO;
        $model->return_type = ReturnTypeEnum::CARD;
        return $this->render($this->action->id, [
            'model' => $model,
            'order' => $order,
            'dataProvider' => $dataProvider,
        ]);
    }
}

