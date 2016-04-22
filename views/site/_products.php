<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider DataProviderInterface */

?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'row',
                'id' => 'products-list-view',
            ],
            'layout' => "{pager}\n{items}",
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_product',['model' => $model]);

                // or just do some echo
                // return $model->title . ' posted by ' . $model->author;
            },
            'itemOptions' => [
                'tag' => false,
            ],
            'pager' => [
                'firstPageLabel' => 'first',
                'lastPageLabel' => 'last',
                'nextPageLabel' => 'next',
                'prevPageLabel' => 'previous',
                'maxButtonCount' => 3,
            ],
        ]) ?>

                
