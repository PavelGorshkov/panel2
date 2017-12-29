<?php
namespace app\modules\user\interfaces;

interface RBACItemInterface {

    public function titleList();

    public function getTree();

    public function getTitleTask();

    public function getRuleNames();

    /**
     * @param string $item
     * @return string
     */
    public function getTitle($item);

    /**
     * @return array
     */
    public function getTypes();
}