<?php
namespace app\modules\user\components;

use app\modules\core\helpers\File;
use Yii;
use yii\rbac\PhpManager as AuthPhpManager;

class PhpManager extends AuthPhpManager
{
    public $itemFile = '@app/runtime/rbac/items.php';

    public $assignmentFile = '@app/runtime/rbac/assignments.php';

    public $ruleFile = '@app/runtime/rbac/rules.php';


    public function init()
    {
        File::checkPath(Yii::getAlias('@app/runtime/rbac/'));

        if (!file_exists(Yii::getAlias($this->itemFile))) {

            app()->buildAuthManager->createAuthFiles();
        }

        parent::init();

        if (!user()->isGuest) {

            if (($role = user()->getRole())!== null) {

                $this->assign($role, user()->id);
            } else {

                foreach (user()->getAccessData() as $access => $temp) {

                    if ($this->getItem($access)!== null) $this->assign($access, user()->id);
                }
            }
        }
    }


    public static function clearAll() {

        File::rmDir(Yii::getAlias('@app/runtime/rbac/*'));
    }
}