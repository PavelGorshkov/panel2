<?php echo "<?php\n"; ?>

namespace app\modules\{module}\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


/**
* Задача {title}
*
* Class {ClassName}
* @package app\modules\{module}\auth
*/
class {ClassName} extends RBACItem
{
    const TASK = '/{module}/{url}';

    const OPERATION_CREATE = '/{module}/{url}/create';

    const OPERATION_UPDATE = '/{module}/{url}/update';

    const OPERATION_DELETE = '/{module}/{url}/delete';

    const OPERATION_READ = '/{module}/{url}/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_UPDATE => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
    ];


    /**
    * @return array
    */
    public function titleList()
    {
        return [
            self::TASK => '{title}',
            self::OPERATION_READ => 'Просмотр',
            self::OPERATION_CREATE => '',
            self::OPERATION_UPDATE => '',
            self::OPERATION_DELETE => '',
        ];
    }


    /**
     * @return array
     */
    public function getTree()
    {

        return [
            Roles::ADMIN => [
                self::TASK,
            ],
            self::TASK => [
                self::OPERATION_READ,
                self::OPERATION_CREATE,
                self::OPERATION_UPDATE,
                self::OPERATION_DELETE,
            ],
        ];
    }
}