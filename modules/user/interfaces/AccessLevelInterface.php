<?php


namespace app\modules\user\interfaces;

/**
 * Interface AccessLevelInterface
 * @package app\modules\user\interfaces
 */
interface AccessLevelInterface
{
    /**
     * @return int
     */
    public function getAccessLevel();

    /**
     * @return array
     */
    public function getAccessPermissions();


    /**
     * @return bool
     */
    public function isUFAccessLevel();
}