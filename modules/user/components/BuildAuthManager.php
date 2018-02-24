<?php
namespace app\modules\user\components;

use app\modules\core\helpers\File;
use app\modules\user\interfaces\RBACItemInterface;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Rule;
use yii\web\ServerErrorHttpException;

/**
 * Класс ответственный за создание файлов авторизации приложения
 *
 * Class BuildAuthManager
 * @package app\modules\user\components
 */
class BuildAuthManager extends Component
{
    public $pathModuleAlias = '@app/modules/';

    /**
     * @var string
     */
    public $itemFile;

    /**
     * @var string path
     */
    public $ruleFile;

    /**
     * @var array|null
     */
    protected $rules = null;

    /**
     * @var null
     */
    protected $_instances = null;

    protected $listTalk;


    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        $this->itemFile = Yii::getAlias('@app/runtime/rbac/items.php');
        $this->ruleFile = Yii::getAlias('@app/runtime/rbac/rules.php');
    }


    /**
     * @param string $module
     * @return string
     */
    public static function getPathAuthTask($module)
    {
        return Yii::getAlias('@app/modules/' . $module . '/auth');

    }

    /**
     * Создание файла конфигурации RBAC приложения
     * @throws Exception
     */
    public function createAuthFiles()
    {

        $settings = $this->generateAuth(new Roles);
        $this->_instances = [];

        foreach (app()->moduleManager->getListAllModules() as $module) {

            $files = self::getPathAuthTask($module) . '/*Task.php';
            $nameSpace = '\\app\\modules\\' . $module . '\\auth\\';

            /* @var \SplFileInfo $item */
            foreach (new \GlobIterator($files) as $item) {

                $className = $nameSpace . $item->getBasename('.php');
                if (!class_exists($className)) continue;

                $instance = new $className;

                if (!($instance instanceof RBACItemInterface)) continue;

                $this->_instances[$module][get_class($instance)] = $instance;

                $settings = ArrayHelper::merge($settings, $this->generateAuth($instance));
            }
        }

        $this->saveRules();
        $this->saveItems($settings);
    }


    /**
     * @param RBACItemInterface $instance
     *
     * @return array
     */
    protected function generateAuth($instance)
    {
        $data = [];

        $tree = $instance->getTree();
        $descriptionList = $instance->titleList();
        $ruleList = $instance->getRuleNames();

        foreach ($instance->getTypes() as $type => $type_role) {

            $t = [
                'type' => $type_role,
                'name' => $type,
                'description' => isset($descriptionList[$type])
                    ? $descriptionList[$type]
                    : '',
            ];

            if (isset($ruleList[$type])) {

                $t['ruleName'] = $this->searchRule($ruleList[$type]);
            }

            if (isset($tree[$type])) {

                $t['children'] = $tree[$type];
            }

            $data[$type] = $t;
        }

        foreach ($tree as $type => $tr) {

            if (!isset($data[$type])) {

                $data[$type]['children'] = $tr;
            }
        }

        return $data;
    }


    /**
     * @param string $rule класс Rule
     *
     * @return string
     */
    protected function searchRule($rule)
    {
        if (!class_exists($rule)) return '';

        $class = new $rule;

        if (!($class instanceof Rule)) return '';

        $name = $class->name;

        if (!$name) return '';

        $rules = $this->getRules();

        if (!isset($rules[$name])) {

            $this->addRule($class);
        }

        return $name;
    }


    /**
     * @return array|null
     */
    protected function getRules()
    {
        if ($this->rules === null) {

            $this->rules = File::includePhpFile($this->ruleFile);
        }

        return $this->rules;
    }


    /**
     * @param Rule $ruleObj
     */
    protected function addRule($ruleObj)
    {
        $this->rules[$ruleObj->name] = serialize($ruleObj);
    }


    /**
     * @throws ServerErrorHttpException
     */
    protected function saveRules()
    {
        if (!File::savePhpFile($this->ruleFile, $this->getRules())) {

            throw new ServerErrorHttpException('Error write rbac rules in ' . $this->ruleFile . '...');
        }
    }


    /**
     * @param mixed $items
     * @throws ServerErrorHttpException
     */
    protected function saveItems($items)
    {
        if (!File::savePhpFile($this->itemFile, $items)) {

            throw new ServerErrorHttpException('Error write rbac items in ' . $this->itemFile . '...');
        }
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getListOperations()
    {
        $task = [];

        foreach (app()->moduleManager->getListEnabledModules() as $module) {

            $files = self::getPathAuthTask($module) . '/*Task.php';
            $nameSpace = '\\app\\modules\\' . $module . '\\auth\\';

            /* @var \SplFileInfo $item */
            foreach (new \GlobIterator($files) as $item) {

                $className = $nameSpace . $item->getBasename('.php');

                if (!class_exists($className)) continue;

                /** @var $instance RBACItemInterface */
                $instance = new $className;

                if (!($instance instanceof RBACItemInterface)) continue;


                list($url, $operations) = self::getOperations($instance);
                $task[$module][$url] = $operations;
            }

        }

        return $task;
    }


    /**
     * @param RBACItemInterface $instance
     * @return array[$url, $task]
     */
    protected static function getOperations(RBACItemInterface $instance)
    {
        $task = [];
        $operations = [];
        $url = '';

        foreach ($instance->getTypes() as $type => $role) {

            switch ($role) {

                case Item::TYPE_ROLE:

                    $task['label'] = $instance->getTitle($type);
                    $url = $type;

                    break;

                case Item::TYPE_PERMISSION:

                    $operations[$type] = $instance->getTitle($type);

                    break;
            }
        }

        $task['item'] = $operations;

        return [$url, $task];
    }
}
