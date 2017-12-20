<?php
namespace app\modules\user\components;

interface RBACItemInterface {

    public function titleList();

    public function getTree();

    public function getTitleTask();

    public function getRuleNames();
}