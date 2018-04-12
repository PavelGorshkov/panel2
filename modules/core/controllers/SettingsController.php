<?php

namespace app\modules\core\controllers;

use app\modules\core\components\WebController;
use app\modules\user\helpers\UserSettings;
use yii\helpers\Url;
use yii\web\Request;

/**
 * Class SettingsController
 * @package app\modules\core\controllers
 */
class SettingsController extends WebController
{


    /**
     * @param string|null $skin
     * @return string|\yii\web\Response
     */
    public function actionSkins($skin = null)
    {
        if ($skin !== null) {

            UserSettings::model()->skinTemplate = $skin;
        }

        if (app()->request->isAjax) {

            return '';
        } else {

            return $this->redirect(app()->request->referrer);
        }
    }


    /**
     * @param $sidebar
     * @return string|\yii\web\Response
     */
    public function actionSidebar($sidebar)
    {
        if (in_array($sidebar, ['remove', 'add'])) {

            UserSettings::model()->sideBar = $sidebar == 'add' ? 'sidebar-collapse' : "s";
        }

        if (app()->request->isAjax) {

            return '';
        } else {

            return $this->redirect(app()->request->referrer);
        }
    }


    public function actionStartPage($page = null)
    {
        if ($page !== null) {

            UserSettings::model()->startPage = $page;
        }

        user()->setSuccessFlash('Установлена стартовая страница '.$page);

        if (app()->request->isAjax) {

            return '';
        } else {

            return $this->redirect($page);
        }
    }
}