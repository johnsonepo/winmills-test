<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
    .bg-light {
        background-color: #f0f0f0; 
    }
    .border {
        border: 1px solid #ccc;
    }
    .bg-light {
        background-color: #f0f0f0; 
    }
    .bg-dark {
        background-color: #F0F4F9; 
    }
    .shadow {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body class="d-flex flex-column h-100 bg-light">
<?php $this->beginBody() ?>

<main role="main ">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
