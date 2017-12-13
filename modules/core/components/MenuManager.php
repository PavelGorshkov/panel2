<?php
namespace app\modules\core\components;

use yii\base\Component;

class MenuManager extends Component {

    const MENU_DATABASE = 'database';

    const MENU_MODULE = 'module';

    const MENU_STATIC = 'file';

    protected $_menu = null;

}