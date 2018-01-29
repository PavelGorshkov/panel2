<?php
/**
 * This is the template for generating a module config file.
 */

/* @var $this yii\web\View */
/* @var $generator \app\modules\core\generators\module\Generator */

$className = $generator->moduleClass;

echo "<?php\n";
?>
return [
    'module' => [
        'class' => '<?= $className ?>',
    ],
];
