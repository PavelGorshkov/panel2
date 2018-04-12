<?php

    use app\modules\finance\interfaces\FinanceObserverInterface;

    /* @var $model FinanceObserverInterface */

    $tableData = $model->getTableData();
?>

<?php if($tableData): ?>
    <br/>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <?php foreach ($tableData as $row): ?>
                    <td class="<?=$row['class']?>"><?=$row['label']?></td>
                <?php endforeach ?>
            </tr>
        </tbody>
    </table>
<?php endif; ?>