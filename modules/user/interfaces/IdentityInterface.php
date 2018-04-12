<?php

namespace app\modules\user\interfaces;

use yii\web\IdentityInterface as YiiIdentityInterface;

/**
 * Interface IdentityInterface
 * @package app\modules\user\interfaces
 */
interface IdentityInterface extends YiiIdentityInterface
{
    /**
     * @param string $usernameOrEmail
     * @return IdentityInterface
     */
    public static function findByUsernameOrEmail($usernameOrEmail);
}