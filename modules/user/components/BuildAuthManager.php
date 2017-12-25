<?php
namespace app\modules\user\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\rbac\Rule;

/**
 * Класс ответственный за создание файлов авторизации приложения
 *
 * Class BuildAuthManager
 * @package app\modules\user\components
 */
class BuildAuthManager extends Component {

    public $pathModuleAlias = '@app/modules/';

    /**
     * @var string
     */
    public $itemFile;

    /**
     * @var string
     */
    public $ruleFile;

    protected $rules = null;

    protected $_instances = null;

    protected $listTalk;


    public function init() {

        parent::init();

        $this->itemFile = Yii::getAlias('@app/runtime/rbac/items.php');
        $this->ruleFile = Yii::getAlias('@app/runtime/rbac/rules.php');
    }

    /**
     * Создание файла конфигурации RBAC приложения
     * @throws Exception
     */
    public function createAuthFiles() {

        $settings = $this->generateAuth(new Roles);
        $this->_instances = [];

        foreach (app()->moduleManager->getListAllModules() as $module) {

            $files = Yii::getAlias('@app/modules/'.$module.'/auth/*Task.php');
            $nameSpace = '\\app\\modules\\'.$module.'\\auth\\';

            /* @var \SplFileInfo $item */
            foreach (new \GlobIterator($files) as $item) {

                $className = $nameSpace. $item->getBasename('.php');
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
     * @param \app\modules\user\components\RbacItem $instance
     *
     * @return array
     */
    protected function generateAuth($instance) {

        $data = [];

        $tree = $instance->getTree();
        $descriptionList = $instance->titleList();
        $ruleList = $instance->getRuleNames();

        foreach ($instance->types as $type => $type_role) {

            $t = [
                'type'=>$type_role,
                'name'=>$type,
                'description'=>isset($descriptionList[$type])?$descriptionList[$type]:'',
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
    protected function searchRule($rule) {

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
    protected function getRules() {

        if ($this->rules === null) {

            if (!file_exists($this->ruleFile)) $this->rules = [];

            else $this->rules = require_once $this->ruleFile;
        }

        return $this->rules;
    }


    /**
     * @param Rule $ruleObj
     */
    protected function addRule($ruleObj) {

        $this->rules[$ruleObj->name] = serialize($ruleObj);
    }


    protected function saveRules() {

        $rules = '<?php return ' . var_export($this->getRules(), true) . ';';

        if (crc32($rules) != file_crc32($this->ruleFile)) {

            if (!@file_put_contents($this->ruleFile, $rules)) {

                throw new Exception('Error write rbac rules in '.$this->ruleFile.'...');
            }
        }
    }


    protected function saveItems($items) {

        $content = '<?php return ' . var_export($items, true) . ';';

        if (crc32($content) != file_crc32($this->itemFile)) {

            if (!@file_put_contents($this->itemFile, $content)) {

                throw new Exception('Error write rbac rules in '.$this->itemFile.'...');
            }
        }
    }
}