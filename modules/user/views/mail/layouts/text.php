<?php
/**
 * @var string $content main view render result
 */
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
С уважением, администрация сайта "<?=app()->name?>"!
<?php $this->endBody() ?>
<?php $this->endPage() ?>
