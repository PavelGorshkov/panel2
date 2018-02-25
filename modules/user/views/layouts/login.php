<?php
/* @var $this View */
/* @var $content string */

use app\modules\core\components\View;
use app\modules\user\widgets\FlashMessages;
use yii\helpers\Html;

$this->beginContent('@app/modules/core/views/layouts/login.php');
?>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <?= Html::a(app()->name, ['/']) ?>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <?php
            try {
                echo FlashMessages::widget();
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
            <?= $content;?>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</body>
<?php
$this->endContent();