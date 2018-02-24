<?php
/**
 * This is the template for generating a module class file.
 */

use app\modules\developer\generators\module\Generator;

/* @var $this yii\web\View */
/* @var $generator Generator */

$className = $generator->moduleClass;
$title = $generator->moduleTitle;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>
namespace <?= $ns ?>;

use app\modules\core\components\Module as ParentModule;

/**
 * <?= $generator->moduleID ?> module definition class
 * Class Module
 * @package <?= $ns ?>
 */
class <?= $className ?> extends ParentModule
{
    /**
    * @return string
    */
    public static function Title() {

        return '<?= $title ?>';
    }
}
