<?php
namespace app\modules\user\components;

use app\modules\core\helpers\RouterUrlHelper;
use app\modules\user\interfaces\RBACItemInterface;
use yii\base\BaseObject;
use yii\rbac\Item;
use yii\web\ServerErrorHttpException;

/**
 * Class RBACItem
 * @package app\modules\user\components
 *
 */
abstract class RbacItem extends BaseObject implements RBACItemInterface
{
    const TASK = '';

    public $types = null;

    /**
     * @return null
     */
    public function getRuleNames()
    {
        return null;
    }


    /**
     * @param string $item
     * @return string
     */
    public function getTitle($item)
    {
        $list = $this->titleList();

        return isset($list[$item]) ? $list[$item] : '';
    }


    /**
     * @param $role
     * @return null|string
     */
    public static function getDescription($role)
    {
        $class = get_called_class();

        $class = new $class;

        if (!($class instanceof RBACItemInterface)) return null;

        return $class->getTitle($role);
    }


    /**
     * @return string
     * @throws ServerErrorHttpException
     */
    public function getTitleTask()
    {
        $class = get_called_class();

        if ($class::TASK) {

            if ($title = $this->getTitle($class::TASK)) {

                return $title;
            }
        }

        throw new ServerErrorHttpException('Create method "public function getTitleTask()" in class "' . get_called_class() . '"');
    }


    /**
     * @return array
     */
    public static function createRulesController()
    {

        $className = get_called_class();
        /* @var RBACItem $class */
        $class = new $className;
        $rules = [];

        foreach ($class->types as $type => $item) {

            if ($item !== Item::TYPE_PERMISSION) continue;

            $action = RouterUrlHelper::getAction($type);

            if ($action === null) continue;

            $rules[] = [
                'allow' => true,
                'actions' => [$action],
                'roles' => [$type],
            ];
        }

        return $rules;
    }


    /**
     * @return array|null
     */
    public function getTypes()
    {
        return $this->types;
    }
}

