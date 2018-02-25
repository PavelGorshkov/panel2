<?php
namespace app\modules\user\components;

use app\modules\core\helpers\File;
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
        File::checkPath(\Yii::getAlias('@app/runtime/rbac/'));

        if (!file_exists(\Yii::getAlias($this->itemFile))) {

            app()->buildAuthManager->createAuthFiles();
        }

        parent::init();

        $this->assignments = [];

        if (!user()->isGuest) {

            if (($role = user()->getRole())!== null) {

                $this->assign(
                    \Yii::createObject([
                        'class' => Role::class,
                        'name'=>$role
                    ]),
                    user()->id
                );
            } else {

                foreach (user()->getAccessData() as $access => $temp) {

                    if ($this->getItem($access)!== null) {

                        $this->assign(
                            \Yii::createObject([
                                Item::class,
                                'name' => $access
                            ]),
                            user()->id
                        );

                    }
                }
            }
        }
    }


    /**
     *  Очистка файлов авторизации RBAC
     */
    public function flush() {

        File::rmDir(\Yii::getAlias('@app/runtime/rbac/*'));
    }
}