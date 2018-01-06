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
abstract class RBACItem extends BaseObject implements RBACItemInterface
{
    const TASK = '';

    public $types = null;

    public function getRuleNames()
    {
        return null;
    }

    public function getTitle($item)
    {
        $list = $this->titleList();

        return isset($list[$item]) ? $list[$item] : '';
    }


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
        if (self::TASK) return self::TASK;

        else throw new ServerErrorHttpException('Create method "public function getTitleTask()" in class "' . get_called_class() . '"');
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

