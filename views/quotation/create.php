<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Создать предложение';
$this->params['breadcrumbs'][] = ['label' => 'Предложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$this->registerJs('
    var url = "'.Url::toRoute('/quotation/create/').'";
    $("#quotation-request-input").on("change", function () {
        window.location.href = url + "/" + this.value;
    });
');

?>
<div class="quotation-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
