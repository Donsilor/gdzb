<?php

namespace addons\Gdzb\api\modules\v1\controllers;

use addons\Gdzb\common\models\Special;
use api\controllers\OnAuthController;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Gdzb\frontend\controllers
 */
class SpecialController extends OnAuthController
{
    /**
    * 首页
    *
    * @return string
    */
    public function actionDetails()
    {
        $url = Yii::$app->request->get('url', null);

        $result = Special::findOne(['url' => $url]);

        return $result;
    }
}