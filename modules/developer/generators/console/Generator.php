<?php
namespace app\modules\developer\generators\console;

use app\modules\core\helpers\File;
use app\modules\developer\components\Generator as DevGenerator;
use \SplFileInfo;
use \Yii;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate a controller and one or a few action view files.
 *
 * @property array $actionIDs An array of action IDs entered by the user. This property is read-only.
 * @property string $controllerFile The controller class file path. This property is read-only.
 * @property string $controllerID The controller ID. This property is read-only.
 * @property string $controllerNamespace The namespace of the controller class. This property is read-only.
 * @property string $controllerSubPath The controller sub path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends DevGenerator
{
    const APPLICATION = 'app';

    /**
     * @var string the module
     */
    public $module;

    /**
     * @var string the controller class name
     */
    public $controllerClass;

    /**
     * @var string the base class of the controller
     */
    public $baseClass = '\\yii\\console\\Controller';

    /**
     * @var string list of action IDs separated by commas or spaces
     */
    public $actions = 'index';

    /**
     * @var bool
     */
    public $hasCron = false;


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Console Controller Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new console controller class with
            one or several controller actions.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'actions', 'baseClass', 'module'], 'filter', 'filter' => 'trim'],
            [['controllerClass', 'baseClass'], 'required'],
            ['module', 'in', 'range'=>$this->getListModule()],
            ['controllerClass', 'match', 'pattern' => '/^[\w]*Controller$/', 'message' => 'Only word characters and backslashes are allowed, and the class name must end with "Controller".'],
            ['controllerClass', 'validateNewClass'],
            ['baseClass', 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            ['actions', 'match', 'pattern' => '/^[a-z][a-z0-9\\-,\\s]*$/', 'message' => 'Only a-z, 0-9, dashes (-), spaces and commas are allowed.'],
            ['hasCron', 'boolean'],

        ]);
    }

    /**
     * @return array
     */
    public function getListModule()
    {
        return ArrayHelper::merge(
            [self::APPLICATION],
            app()->moduleManager->getListAllModules()
        );
    }


    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateNewClass($attribute, $params)
    {
        $class = ltrim($this->$attribute, '\\');
        $class = ltrim($this->getControllerNamespace(), '\\').'\\'.$class;

        if (($pos = strrpos($class, '\\')) === false) {
            $this->addError($attribute, "The class name must contain fully qualified namespace name.");
        } else {
            $ns = substr($class, 0, $pos);
            $path = Yii::getAlias('@' . str_replace('\\', '/', $ns), false);
            if ($path === false) {
                $this->addError($attribute, "The class namespace is invalid: $ns : $path : $class");
            } elseif (!is_dir($path)) {

                if (!File::checkPath($path)) {

                    $this->addError($attribute, "Please make sure the directory containing this class exists: $path");
                }
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'baseClass' => 'Base Class',
            'controllerClass' => 'Console Controller Class',
            'actions' => 'Action IDs',
            'hasCron' => 'Has cron command',
        ];
    }


    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [
            'controller.php',
        ];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return ['baseClass'];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'controllerClass' => 'This is the name of the console controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>ConsoleController</code>),
                and class name should be in CamelCase ending with the word <code>Controller</code>. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'actions' => 'Provide one or multiple action IDs to generate empty action method(s) in the controller. Separate multiple action IDs with commas or spaces.
                Action IDs should be in lower case. For example:
                <ul>
                    <li><code>index</code> generates <code>actionIndex()</code></li>
                    <li><code>create-order</code> generates <code>actionCreateOrder()</code></li>
                </ul>',
            'baseClass' => 'This is the class that the new controller class will extend from. Please make sure the class exists and can be autoloaded.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        return 'The controller has been generated successfully.' . $this->getLinkToTry();
    }

    /**
     * This method returns a link to try controller generated
     * @see https://github.com/yiisoft/yii2-gii/issues/182
     * @return string
     * @since 2.0.6
     */
    private function getLinkToTry()
    {
        if (strpos($this->controllerNamespace, Yii::$app->controllerNamespace) !== 0) {
            return '';
        }

        $actions = $this->getActionIDs();
        if (in_array('index', $actions, true)) {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/index';
        } else {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/' . reset($actions);
        }
        return ' You may ' . Html::a('try it now', Yii::$app->getUrlManager()->createUrl($route), ['target' => '_blank', 'rel' => 'noopener noreferrer']) . '.';
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getControllerFile(),
            $this->render('controller.php')
        );

        return $files;
    }

    /**
     * Normalizes [[actions]] into an array of action IDs.
     * @return array an array of action IDs entered by the user
     */
    public function getActionIDs()
    {
        $actions = array_unique(preg_split('/[\s,]+/', $this->actions, -1, PREG_SPLIT_NO_EMPTY));
        sort($actions);

        return $actions;
    }

    /**
     * @return string the controller class file path
     */
    public function getControllerFile()
    {
        $class = ltrim($this->getControllerNamespace(), '\\').'\\'.$this->controllerClass;

        return Yii::getAlias('@' . str_replace('\\', '/', $class)) . '.php';
    }

    /**
     * @return string the controller ID
     */
    public function getControllerID()
    {
        $name = StringHelper::basename($this->controllerClass);
        return Inflector::camel2id(substr($name, 0, strlen($name) - 10));
    }

    /**
     * This method will return sub path for controller if it
     * is located in subdirectory of application controllers dir
     * @see https://github.com/yiisoft/yii2-gii/issues/182
     * @since 2.0.6
     * @return string the controller sub path
     */
    public function getControllerSubPath()
    {
        $subPath = '';
        $controllerNamespace = $this->getControllerNamespace();
        if (strpos($controllerNamespace, Yii::$app->controllerNamespace) === 0) {
            $subPath = substr($controllerNamespace, strlen(Yii::$app->controllerNamespace));
            $subPath = ($subPath !== '') ? str_replace('\\', '/', substr($subPath, 1)) . '/' : '';
        }
        return $subPath;
    }


    /**
     * @inheritdoc
     */
    public function autoCompleteData()
    {
        return [
            'taskClass' => function() {

                $classes = [];

                foreach (app()->moduleManager->getListAllModules() as $module) {
                    $ns = '\\app\\modules\\' . $module . '\\auth\\';
                    $path = '@app/modules/' . $module . '/auth/';

                    /* @var $item SplFileInfo */
                    foreach (new \GlobIterator(Yii::getAlias($path . '*Task.php')) as $item) {

                        $classes[] = $ns . $item->getBasename('.php');
                    }
                }

                return $classes;
            },
        ];
    }


    /**
     * @param string $action the action ID
     * @return string the action view file path
     */
    public function getViewFile($action)
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/modules/'.$this->module.'/views/' . $this->getControllerSubPath() . $this->getControllerID() . "/$action.php");
        }

        return Yii::getAlias(str_replace('\\', '/', $this->viewPath) . "/$action.php");
    }


    /**
     * @return string the namespace of the controller class
     */
    public function getControllerNamespace()
    {
        if ($this->module == self::APPLICATION) {

            return '\\app\\commands';
        } else {

            return '\\app\\modules\\'.$this->module.'\\commands';
        }
    }
}
