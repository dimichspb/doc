<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Изменить Предложение: ' . $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Предложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';

/*
$this->registerJs('
    var url = "'.Url::toRoute('/quotation/update?id=' . $model->id).'";
    $("#quotation-request-input").on("change", function () {
        window.location.href = url + "&request=" + this.value;
    });
');
*/

?>
<div class="quotation-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
