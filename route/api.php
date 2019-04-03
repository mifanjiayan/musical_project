<?php

use think\facade\Route;

// 用户登录 api/v1/login
//Route::post("api/:version/login","api/:version.Auth/login");
Route::post("api/:version/login.do","api/:version.OpenAuth/login");



