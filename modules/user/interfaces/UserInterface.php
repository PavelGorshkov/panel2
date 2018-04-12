<?php

namespace app\modules\user\interfaces;

/**
 * Interface UserInterface
 * @package app\modules\user\interfaces
 */
interface UserInterface extends AccessLevelInterface, RegisterFromInterface
{
    /**
     * @return string
     */
    public function getAvatar();
}