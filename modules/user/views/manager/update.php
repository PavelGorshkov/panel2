<?php
/* @var $this  View */
/* @var $modelForm UserFormModel */
/* @var $model User */
/* @var $module Module */

use app\modules\core\components\View;
use app\modules\user\forms\UserFormModel;
use app\modules\user\models\User;
use app\modules\user\Module;

$this->setSmallTitle($modelForm->email);

printr($modelForm);
