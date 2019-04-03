<?php
namespace app\common\controller;

use think\facade\Session;

class Merchant extends BaseController {

    protected static $merchant;

    public function __construct()
    {
        parent::__construct();
        self::$merchant = \app\common\model\Merchant::get(Session::get('merchant')['id']);
    }
}
