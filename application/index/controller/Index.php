<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-4
 * Time: 上午11:33
 */

namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return view('test');
    }

}
