<?php

namespace app\modules\user\widgets;

use app\modules\core\widgets\Widget;
use app\modules\user\helpers\ModuleTrait;

/**
 * Class InfoProfileWidget
 * @package app\modules\user\widgets
 */
class InfoProfileWidget extends Widget
{
    use ModuleTrait;

    public $view = 'profile';

    /**
     * @return null|string
     * @throws \yii\base\Exception
     */
    public function run()
    {
        $user = user()->info;

        if ($user === null) return null;

        $avatar = $user->avatar ? $user->avatar : $this->module->defaultAvatar;

        return $this->render(
            $this->view,
            [
                'icon' => app()->thumbNailer->thumbnail($this->module->avatarDirs . $avatar,
                    $this->module->avatarDirs,
                    128,
                    128
                ),
                'email' => $user->email,
                'full_name' => $user->full_name,
                'about' => $user->about,
                'phone' => $user->phone,
            ]
        );
    }
}