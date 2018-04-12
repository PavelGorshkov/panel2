<?php

namespace app\modules\core\widgets;

use yii\widgets\Breadcrumbs as YiiBreadcrumbs;

/**
 * Class Breadcrumbs
 * @package app\modules\core\widgets
 */
class Breadcrumbs extends YiiBreadcrumbs
{
    /**
     * @inheritdoc
     * @return void
     */
    public function run()
    {
        $last = count($this->links) - 1;
        if (isset($this->links[$last])) {

            $last_link = $this->links[$last];

            if (empty($last_link['url'])) {

                $this->links[$last] = [
                    'label'=>$this->links[$last]['label']??'',
                    'url'=>['/core/settings/start-page', 'page'=>app()->request->getUrl()],
                    'encode'=>$this->links[$last]['encode']??false,
                    'title'=>'Сделать стартовой',
                ];
            }
        }

        parent::run();
    }
}