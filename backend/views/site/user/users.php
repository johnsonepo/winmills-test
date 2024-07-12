<?php
/** @var yii\web\View $this */
/** @var common\models\User $model */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Users';
?>

<div class="user-index w-3/4 mb-14">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            'email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, $model, $key) {
                        return '<div class="flex justify-center items-center space-x-4">
                            ' . Html::a(
                                '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
                                </svg>',
                                ['/user/edit', 'id' => $model->id],
                                ['class' => 'text-green-600 hover:text-green-900', 'title' => Yii::t('app', 'Edit')]
                            ) . '
                            ' . Html::a(
                                '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'text-red-600 hover:text-red-900',
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post',
                                ]
                            ) . '
                        </div>';
                    },
                ],
                'contentOptions' => ['style' => 'width: 150px; text-align: center;'],
            ],
        ],
    ]); ?>
</div>
