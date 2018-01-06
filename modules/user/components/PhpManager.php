<?php
namespace app\modules\user\components;

use app\modules\core\helpers\File;
use Yii;
use yii\rbac\Item;
use yii\rbac\PhpManager as AuthPhpManager;
use yii\rbac\Role;

/**
 * Class PhpManager
 * @package app\modules\user\components
 */
class PhpManager extends AuthPhpManager
{
    public $itemFile = '@app/runtime/rbac/items.php';

    public $assignmentFile = '@app/runtime/rbac/assignments.php';

    public $ruleFile = '@app/runtime/rbac/rules.php';

    /**
     * @throws \yii\base\Exception
     */
    public function init()
    {
        File::checkPath(Yii::getAlias('@app/runtime/rbac/'));

        if (!file_exists(Yii::getAlias($this->itemFile))) {

            app()->buildAuthManager->createAuthFiles();
        }

        parent::init();

        $this->assignments = [];

        if (!user()->isGuest) {

            if (($role = user()->getRole())!== null) {

                $this->assign($this->createObjRole($role), user()->id);
            } else {

                foreach (user()->getAccessData() as $access => $temp) {

                    if ($this->getItem($access)!== null) $this->assign($this->createObjItem($access), user()->id);
                }
            }
        }
    }


    protected function createObjRole($role) {

        $class = new Role();
        $class->name = $role;

        return $class;
    }

    protected function createObjItem($item) {

        $class = new Item();
        $class->name = $item;

        return $class;
    }


    public static function flush() {

        File::rmDir(Yii::getAlias('@app/runtime/rbac/*'));


    }
}