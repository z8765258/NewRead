<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 10:04
 */

namespace app\admin\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome() {
        return "新闻共读后台管理";
    }
}