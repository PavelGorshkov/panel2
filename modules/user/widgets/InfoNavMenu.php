<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 16.12.2017
 * Time: 12:44
 */

namespace app\modules\user\widgets;

use app\modules\user\helpers\ModuleTrait;
use yii\base\Widget;

class InfoNavMenu extends Widget {

    use ModuleTrait;

    public $view = 'info_nav_menu';

    public function run() {

        $user = user()->profile;

        $avatar = $user->avatar?$user->avatar:$this->module->defaultAvatar;

        return $this->render($this->view, [
            'mini_icon'=>app()->thumbNailer->thumbnail($this->module->avatarDirs. $avatar,
                $this->module->avatarDirs,
                24,
                24
            ),
            'icon'=>app()->thumbNailer->thumbnail($this->module->avatarDirs. $avatar,
                $this->module->avatarDirs,
                160,
                160
            ),
            'email'=>user()->info->email,
            'full_name'=>user()->profile->full_name,
            'about'=>user()->profile->about,
        ]);
    }



}