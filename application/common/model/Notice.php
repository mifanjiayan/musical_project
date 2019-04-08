<?php

namespace app\common\model;


class Notice extends BaseModel
{
    protected $autoWriteTimestamp = true;

    protected $insert = [
        'create_time'
    ];

}
