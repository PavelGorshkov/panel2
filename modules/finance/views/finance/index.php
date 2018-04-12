<?php
    use app\modules\core\components\View;

    /* @var $this View */
    /* @var $content array */
?>

<?php if($content): ?>
    <?php foreach ($content as $action => $data): ?>
        <?=$data?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-warning">Данные показателей отсутствуют!</div>
<?php endif; ?>