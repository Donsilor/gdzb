<?php

namespace addons\Warehouse\common\forms;

use Yii;
use common\helpers\StringHelper;
use addons\Warehouse\common\models\WarehouseBill;
use common\helpers\ArrayHelper;

/**
 * 销售单 Form
 *
 */
class WarehouseBillSForm extends WarehouseBill
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
         $rules = [

         ];
         return array_merge(parent::rules() , $rules);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        //合并
        return ArrayHelper::merge(parent::attributeLabels() , [

        ]);
    }
}
