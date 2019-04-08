<?php

namespace app\common\command;

use app\api\service\CompanyService;
use app\common\model\OrderReports;
use app\common\model\TransactionAccountTables;
use redis\RedisSubscribe;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\facade\Log;

class Jobs extends Command
{
    protected function configure()
    {
        $this->addArgument('type', 1, 'this is type');
        $this->setName('jobs')->setDescription('order_count|redis-notify-key-events|transaction_account_tables');
    }

    protected function execute(Input $input, Output $output)
    {
        $type = $input->getArgument('type');
        if ($type == 'order_count') {
            Log::info(time() . "=>每日报表执行成功！");
       }
    }

}