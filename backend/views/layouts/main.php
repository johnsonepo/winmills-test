<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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
    <link href="/backend/web/css/tailwind.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="z-999">
    <?php


    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top border-b border-gray-500',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $dropdownItems = [
            [
                'label' => 'My Profile',
                'url' => ['/user/profile'], 
                'linkOptions' => ['class' => 'dropdown-item'],
            ],
            '<div class="dropdown-divider"></div>',
            [
                'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                'url' => ['/site/logout'],
                'linkOptions' => [
                    'class' => 'dropdown-item',
                    'data-method' => 'post', 
                ],
            ],
        ];

        $menuItems[] = [
            'label' => 'Welcome '. Yii::$app->user->identity->username, 
            'items' => $dropdownItems, 
            'encode' => false, 
            'options' => ['class' => 'nav-item dropdown'], 
            'linkOptions' => [
                'class' => 'nav-link dropdown-toggle',
                'id' => 'navbarDropdown',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false',
            ],
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>
</header>


<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
     
        <div class="absolute left-0 top-11 bottom-0 overflow-x-hidden w-full">
            <div class="flex h-full">
                <div class="w-[200px] bg-[#212529] pt-8 text-white px-2 relative">
                    <ul class="">
                        
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'admin')): ?>
                            <li class="hover:bg-gray-600 p-2 rounded-xl relative group">
                                <a class="flex justify-start items-center space-x-2 cursor-pointer">
                                    <svg class="w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    <span>Users</span>
                                    <svg class="w-4 h-4 ml-auto group-hover:-rotate-90 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l-7-7h14l-7 7z" />
                                    </svg>
                                </a>
                                <ul class="hidden absolute left-full top-0 bg-[#212529] shadow-md overflow-hidden group-hover:block w-40">
                                    <li>
                                        <a class="block hover:bg-gray-500 p-2 " href="<?= \yii\helpers\Url::to(['user/users']) ?>">All Users</a>
                                    </li>    
                                    <li>
                                        <a class="block hover:bg-gray-500 p-2 " href="<?= \yii\helpers\Url::to(['user/new']) ?>">Add New User</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="w-full p-4 overflow-y-auto">
                    <h1 class="text-2xl font-bold mb-4"><?= Html::encode($this->title) ?></h1>

                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted z-10 fixed-bottom">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
