<?php
namespace app\modules\user\widgets;

use app\modules\core\widgets\Widget;
use app\modules\user\helpers\ModuleTrait;


class InfoProfileWidget extends Widget {

    use ModuleTrait;

    public  $view = 'profile';

    /**
     * @return null|string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function run() {

        $user = user()->info;

        if ($user === null) return null;

        $profile = user()->profile;

        $avatar = $profile->avatar?$profile->avatar:$this->module->defaultAvatar;

        return $this->render(
            $this->view,
            [
                'icon'=>app()->thumbNailer->thumbnail($this->module->avatarDirs. $avatar,
                    $this->module->avatarDirs,
                    128,
                    128
                ),
                'email'=>$user->email,
                'full_name'=>$profile->full_name,
                'about'=>$profile->about,
                'phone'=>$profile->phone,
            ]
        );
    }

}