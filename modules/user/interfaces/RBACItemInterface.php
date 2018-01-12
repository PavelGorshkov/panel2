<?php
namespace app\modules\user\interfaces;

/**
 * Interface RBACItemInterface
 * @package app\modules\user\interfaces
 */
interface RBACItemInterface {

    /**
     * @return array
     */
    public function titleList();

    /**
     * @return array
     */
    public function getTree();

    /**
     * @return string
     */
    public function getTitleTask();

    /**
     * @return mixed
     */
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