<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-3
 * Time: 上午10:21
 */

namespace app\facade;

use think\Facade;


class IndexController extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\IndexController';
    }
}