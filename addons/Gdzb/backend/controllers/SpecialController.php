<?php

namespace addons\Gdzb\backend\controllers;

use addons\Gdzb\common\models\Special;
use addons\Sales\common\enums\ChannelIdEnum;
use addons\Sales\common\forms\OrderForm;
use addons\Sales\common\models\Order;
use common\helpers\DateHelper;
use Yii;
use common\helpers\Url;
use common\helpers\ResultHelper;
use common\models\base\SearchModel;
use addons\Gdzb\common\models\Customer;
use common\traits\Curd;
use yii\db\Exception;

/**
 * 客户管理
 *
 * Class CustomerController
 * @package addons\Sales\backend\controllers
 */
class SpecialController extends BaseController
{
    use Curd;

    /**
     * @var Special
     */
    public $modelClass = Special::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize,
            'relations' => [
                'creator' => ['username'],
            ]
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['created_at']);

        $created_at = $searchModel->created_at;
        if (!empty($created_at)) {
            $dataProvider->query->andFilterWhere(['>=',Customer::tableName().'.created_at', strtotime(explode('/', $created_at)[0])]);//起始时间
            $dataProvider->query->andFilterWhere(['<',Customer::tableName().'.created_at', (strtotime(explode('/', $created_at)[1]) + 86400)] );//结束时间
        }

        //$dataProvider->query->andWhere(['>',Customer::tableName().'.status',-1]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * 编辑客户
     *
     * @return mixed
     * @throws
     */
    public function actionEdit()
    {
        $this->layout = false;


        $id = Yii::$app->request->get('id');

        $model = $this->findModel($id);
//var_dump($model);exit;
//        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {

            if(!$model->id) {
                $model->creator_id = Yii::$app->user->getId();
            }

            if(!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            return $this->message("保存成功", $this->redirect(['view','id'=>$model->id]), 'success');
        }
        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 详情展示页
     * @return string
     * @throws
     */
    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $tab = Yii::$app->request->get('tab',1);
        $returnUrl = Yii::$app->request->get('returnUrl', Url::to(['index']));
        $model = $this->findModel($id);
        $model = $model ?? new Customer();
        return $this->render($this->action->id, [
            'model' => $model,
            'tab'=>$tab,
            'tabList'=>\Yii::$app->gdzbService->customer->menuTabList($id, $returnUrl),
            'returnUrl'=>$returnUrl,
        ]);
    }

    /**
     * 客户订单列表
     * @return string
     * @throws
     */
    public function actionOrder()
    {
        $this->modelClass = OrderForm::class;
        $tab = Yii::$app->request->get('tab',1);
        $returnUrl = Yii::$app->request->get('returnUrl', Url::to(['index']));
        $customer_id = \Yii::$app->request->get('customer_id', null);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
            'relations' => [
                'account' => ['order_amount', 'refund_amount'],
            ]
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['created_at', 'order_time']);
        $searchParams = \Yii::$app->request->queryParams['SearchModel'] ?? [];
        $dataProvider->query->andWhere(['=', Order::tableName().'.customer_id', $customer_id]);
        //创建时间过滤
        if (!empty($searchParams['order_time'])) {
            list($start_date, $end_date) = explode('/', $searchParams['order_time']);
            $dataProvider->query->andFilterWhere(['between', Order::tableName().'.order_time', strtotime($start_date), strtotime($end_date) + 86400]);
        }
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tab'=>$tab,
            'tabList'=>\Yii::$app->salesService->customer->menuTabList($customer_id, $returnUrl),
            'returnUrl'=>$returnUrl,
        ]);
    }
}