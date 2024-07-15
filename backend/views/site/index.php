<?php

/** @var yii\web\View $this */
/** @var common\models\ActivityLog $model */
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Dashboard';
?>

<div class="w-full">
    <div class="activity-log-table mt-3">
    <h1 class="my-4 text-2xl font-bold">Activity logs</h1>
        <?= GridView::widget([
            'dataProvider' => $data_provider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Username',
                    'value' => function ($model) {
                        $user = \common\models\User::findOne($model->user_id);
                        return $user ? $user->username : 'N/A';
                    },
                ],
                'action',
                'model',
                'records',
                'user_id',
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                ],
            ],
            'tableOptions' => ['class' => 'table table-striped'],
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'options' => ['class' => 'pagination justify-content-center'],
            ],
        ]); ?>
    </div>
</div>
          
        
