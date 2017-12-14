<?php
/* @var $this \app\modules\core\components\View */
/* @var $content string */

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
            <?= $content;?>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</body>
<?php $this->endContent(); ?>