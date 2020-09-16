<?php

namespace addons\Warehouse\common\queues;

use Yii;
use common\queues\RetryJob;

/**
 * 单据日志
 * Class BillLogJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class BillLogJob extends RetryJob
{
    /**
     * 日志记录数据
     *
     * @var
     */
    public $data;
    
    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->warehouseService->billLog->realCreateBillLog($this->data);
    }
}