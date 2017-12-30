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

    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function run() {

        $user = user()->info;

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
            'email'=>$user->email,
            'full_name'=>$user->full_name,
            'about'=>user()->$user->about,
        ]);
    }



}