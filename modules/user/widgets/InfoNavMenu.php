<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 16.12.2017
 * Time: 12:44
 */

namespace app\modules\user\widgets;

use yii\base\Widget;

class InfoNavMenu extends Widget {

    public function run() {

        $userModule = app()->getModule('user');

        $user = user()->profile;

        $avatar = $user->avatar?$user->avatar:$userModule->defaultAvatar;



    }



}