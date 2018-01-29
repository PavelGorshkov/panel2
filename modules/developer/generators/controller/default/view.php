<?php
/**
 * This is the template for generating an action view file.
 */

/* @var $this yii\web\View */
/* @var $generator \app\modules\developer\generators\controller\Generator */
/* @var $action string the action ID */

echo "<?php\n";
?>
/* @var $this app\modules\core\components\View */

$this->setSmallTitle('<?= $action ?>');

/*
$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url' => ['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);
*/

<?= "?>" ?>

<h1><?= $generator->getControllerSubPath() . $generator->getControllerID() . '/' . $action ?></h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= '<?=' ?> __FILE__; ?></code>.
</p>
