<?php

namespace app\modules\developer\generators\crud;

use app\modules\core\helpers\File;
use app\modules\user\components\RBACItem;
use SplFileInfo;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property string $nameAttribute This property is read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property bool|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    const SEARCH_INTERFACE = 'app\modules\core\interfaces\SearchModelInterface';
    const SAVE_INTERFACE = 'app\modules\core\interfaces\SaveModelInterface';

    public $module;
    public $controllerClass;
    public $taskClass;
    public $modelClass;
    public $formModelClass = '';
    public $searchModelClass = '';
    public $baseControllerClass = 'app\modules\core\components\WebController';
    public $viewPath;


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'controllerClass',
                    'modelClass',
                    'searchModelClass',
                    'formModelClass',
                    'taskClass',
                    'baseControllerClass'
                ],
                'filter', 'filter' => 'trim'
            ],
            [
                [
                    'module',
                    'controllerClass',
                    'modelClass',
                    'searchModelClass',
                    'formModelClass',
                    'taskClass',
                    'baseControllerClass',
                ],
                'required'
            ],
            [
                'module', 'in', 'range' => app()->moduleManager->getListAllModules()
            ],
            [
                [
                    'searchModelClass'
                ],
                'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==',
                'message' => 'Search Model Class must not be equal to Model Class.'
            ],
            [
                [
                    'formModelClass'
                ],
                'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==',
                'message' => 'Form Model Class must not be equal to Model Class.'
            ],
            [
                [
                    'modelClass',
                    'controllerClass',
                    'formModelClass',
                    'taskClass',
                    'baseControllerClass',
                    'searchModelClass'
                ],
                'match', 'pattern' => '/^[\w\\\\]*$/',
                'message' => 'Only word characters and backslashes are allowed.'
            ],
            [
                [
                    'modelClass',
                ],
                'validateClass',
                'params' => ['extends' => Model::class]
            ],
            [
                [
                    'taskClass',
                ],
                'validateClass',
                'params' => ['extends' => RBACItem::class]
            ],
            [
                [
                    'searchModelClass'
                ],
                'validateClass',
                'params' => [
                    'create' => true,
                    'extends' => Model::class,
                    'implements' => self::SEARCH_INTERFACE
                ]
            ],
            [
                [
                    'formModelClass'
                ],
                'validateClass',
                'params' => [
                    'create' => true,
                    'extends' => Model::class,
                    'implements' => self::SAVE_INTERFACE
                ]
            ],
            [
                [
                    'baseControllerClass'
                ],
                'validateClass', 'params' => ['extends' => Controller::class],
            ],
            [
                [
                    'controllerClass'
                ],
                'match', 'pattern' => '/Controller$/',
                'message' => 'Controller class name must be suffixed with "Controller".'
            ],
            [
                [
                    'controllerClass'
                ],
                'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/',
                'message' => 'Controller class name must start with an uppercase letter.'
            ],
            [
                ['controllerClass',],'validateNewClass'
            ],
            [
                ['modelClass'], 'validateModelClass'
            ],
            ['viewPath', 'safe'],
        ]);
    }


    /**
     * An inline validator that checks if the attribute value refers to a valid namespaced class name.
     * The validator will check if the directory containing the new class file exist or not.
     * @param string $attribute the attribute being validated
     * @param array $params the validation options
     */
    public function validateNewClass($attribute, $params)
    {
        $class = ltrim($this->$attribute, '\\');

        if ($attribute == 'controllerClass') {

            if (($pos = strrpos($class, '\\')) === false) {

                $class = ltrim($this->getControllerNamespace(), '\\') . '\\' . $class;
            }
        }

        if (($pos = strrpos($class, '\\')) === false) {

            $this->addError($attribute, "The class name must contain fully qualified namespace name.");
        } else {
            $ns = substr($class, 0, $pos);
            $path = \Yii::getAlias('@' . str_replace('\\', '/', $ns), false);
            if ($path === false) {
                $this->addError($attribute, "The class namespace is invalid: $ns");
            } elseif (!File::checkPath($path)) {
                $this->addError($attribute, "Please make sure the directory containing this class exists: $path");
            }
        }
    }


    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateClass($attribute, $params)
    {
        $class = $this->$attribute;

        try {
            if (class_exists($class)) {

                if (isset($params['extends'])) {
                    if (ltrim($class, '\\') !== ltrim($params['extends'], '\\') && !is_subclass_of($class, $params['extends'])) {
                        $this->addError($attribute, "'$class' must extend from {$params['extends']} or its child class.");
                    }
                }

                if (isset($params['implements'])) {

                    if (!((new $class) instanceof $params['implements'])) {

                        $this->addError($attribute, "'$class' must implement from '{$params['implements']}' interface.");
                    }
                }

            } else {

                if (!isset($params['create'])) {

                    $this->addError($attribute, "Class '$class' does not exist or has syntax error.");
                }
            }
        } catch (\Exception $e) {
            $this->addError($attribute, "Class '$class' does not exist or has syntax error.");
        }
    }


    /**
     * @return array
     */
    public function autoCompleteData()
    {
        return [
            'modelClass' => function () {

                $classes = [];

                if (!empty($this->module)) {

                    $listModules = [$this->module];
                } else {

                    $listModules = app()->moduleManager->getListAllModules();
                }

                foreach ($listModules as $module) {

                    $ns = '\\app\\modules\\' . $module . '\\models\\';
                    $path = '@app/modules/' . $module . '/models/';

                    /* @var $item SplFileInfo */
                    foreach (new \GlobIterator(\Yii::getAlias($path . '*.php')) as $item) {

                        $classes[] = $ns . $item->getBasename('.php');
                    }
                }

                return $classes;
            },
            'searchModelClass' => function () {

                $classes = [];

                if (!empty($this->module)) {

                    $listModules = [$this->module];
                } else {

                    $listModules = app()->moduleManager->getListAllModules();
                }

                foreach ($listModules as $module) {
                    $ns = '\\app\\modules\\' . $module . '\\models\\';
                    $path = '@app/modules/' . $module . '/models/';

                    /* @var $item SplFileInfo */
                    foreach (new \GlobIterator(\Yii::getAlias($path . '*.php')) as $item) {

                        $class = $ns . $item->getBasename('.php');
                        $reflection = new \ReflectionClass($class);

                        if ($reflection->implementsInterface(self::SEARCH_INTERFACE)) {

                            $classes[] = $class;
                        }
                    }
                }

                return $classes;
            },
            'taskClass'=>function() {

                $classes = [];

                if (!empty($this->module)) {

                    $listModules = [$this->module];
                } else {

                    $listModules = app()->moduleManager->getListAllModules();
                }

                foreach ($listModules as $module) {
                    $ns = '\\app\\modules\\' . $module . '\\auth\\';
                    $path = '@app/modules/' . $module . '/auth/';

                    /* @var $item SplFileInfo */
                    foreach (new \GlobIterator(\Yii::getAlias($path . '*Task.php')) as $item) {

                        $class = $ns . $item->getBasename('.php');
                        $reflection = new \ReflectionClass($class);

                        if ($reflection->isSubclassOf(RBACItem::class)) {

                            $classes[] = $class;
                        }
                    }
                }


                return $classes;
            }
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'controllerClass' => 'Controller Class',
            'viewPath' => 'View Path',
            'baseControllerClass' => 'Base Controller Class',
            'indexWidgetType' => 'Widget Used in Index Page',
            'searchModelClass' => 'Search Model Class',
            'enablePjax' => 'Enable Pjax',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase with an uppercase first letter. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'viewPath' => 'Specify the directory for storing the view scripts for the controller. You may use path alias here, e.g.,
                <code>/var/www/basic/controllers/views/post</code>, <code>@app/views/post</code>. If not set, it will default
                to <code>@app/views/ControllerID</code>',
            'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
                You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
            'indexWidgetType' => 'This is the widget type to be used in the index page to display list of the models.
                You may choose either <code>GridView</code> or <code>ListView</code>',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a fully
                qualified namespaced class name, e.g., <code>app\models\PostSearch</code>.',
            'enablePjax' => 'This indicates whether the generator should wrap the <code>GridView</code> or <code>ListView</code>
                widget on the index page with <code>yii\widgets\Pjax</code> widget. Set this to <code>true</code> if you want to get
                sorting, filtering and pagination without page refreshing.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }


    /**
     * @return string the namespace of the controller class
     */
    public function getControllerNamespace()
    {
        return '\\app\\modules\\'.$this->module.'\\controllers';
    }

    /**
     * @return string the namespace of the controller class
     */
    public function getModelNamespace()
    {
        return '\\app\\modules\\'.$this->module.'\\models';
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        /* @var $class ActiveRecordInterface */
        $class = $this->modelClass;
        $pk = $class::primaryKey();
        if (empty($pk)) {
            $this->addError('modelClass', "The table associated with $class must have primary key(s).");
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if (($pos = strrpos($this->controllerClass, '\\')) === false) {

            $this->controllerClass = $this->getControllerNamespace().'\\'.$this->controllerClass;
        }

        if (($pos = strrpos($this->searchModelClass, '\\')) === false) {

            $this->searchModelClass = $this->getModelNamespace().'\\'.$this->searchModelClass;
        }

        if (($pos = strrpos($this->formModelClass, '\\')) === false) {

            $this->searchModelClass = $this->getModelNamespace().'\\'.$this->formModelClass;
        }

        $controllerFile = \Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {

            $searchModel = \Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));

            if (!file_exists($searchModel)) {

                $files[] = new CodeFile($searchModel, $this->render('search.php'));
            }
        }

        $formModel = \Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->formModelClass, '\\') . '.php'));

        if (!file_exists($formModel)) {

            $files[] = new CodeFile($formModel, $this->render('form.php'));
        }

        File::checkPath(\Yii::getAlias('@app/modules/'.$this->module.'/helpers'));
        $traitFile = \Yii::getAlias('@app/modules/'.$this->module.'/helpers').'/ModuleTrait.php';
        if (!file_exists($traitFile)) {

            $files[] = new CodeFile($traitFile, $this->render('trait.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }


    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class);
    }


    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return \Yii::getAlias('@app/modules/'.$this->module.'/views/' . $this->getControllerID());
        }

        return \Yii::getAlias(str_replace('\\', '/', $this->viewPath));
    }


    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            }

            return "'id' => \$model->{$pks[0]}";
        }

        $params = [];
        foreach ($pks as $pk) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                $params[] = "'$pk' => (string)\$model->$pk";
            } else {
                $params[] = "'$pk' => \$model->$pk";
            }
        }

        return implode(', ', $params);
    }
}
