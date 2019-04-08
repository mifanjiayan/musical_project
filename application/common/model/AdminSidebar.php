<?php

namespace app\common\model;


class AdminSidebar extends BaseModel
{
    public static function getList($where = [])
    {
        return self::where($where)->select();
    }
}
