<?php

use yii\helpers\Html;
use app\models\Request;
use app\models\RequestToProduct;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $request app\models\Request */

$this->title = Yii::$app->name . '. Новый запрос: ' . $request->id;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
    <title><?= Html::encode($this->title) ?></title>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-spacing: 0;
        }

        table td {
            border-collapse: collapse;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .ReadMsgBody {
            width: 100%;
            background-color: #ebebeb;
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        .yshortcuts a {
            border-bottom: none !important;
        }

        @media screen and (max-width: 599px) {
            .force-row,
            .container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        @media screen and (max-width: 400px) {
            .container-padding {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }
        .ios-footer a {
            color: #aaaaaa !important;
            text-decoration: underline;
        }
    </style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- 100% background wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
    <tr>
        <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

            <br>

            <!-- 800px container (white background) -->
            <table border="0" width="800" cellpadding="0" cellspacing="0" class="container" style="width:800px;max-width:800px">
                <tr>
                    <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px">
                        <span><img src="http://skladboltov.ru/favicon.ico" alt="<?= Yii::$app->name ?>" title="<?= Yii::$app->name ?>" style="width:18px;margin-right:10px;"><?= Yii::$app->name ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
                        <br>
                        <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Новый запрос № <?= $request->id ?></div>
                        <br>

                        <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                            Появлися новый запрос с сайта <?= Html::a(Yii::$app->params['domain'], Yii::$app->params['domain']) ?>.
                            <br><br>
                        </div>

                        <div class="hr" style="height:1px;border-bottom:1px solid #cccccc">&nbsp;</div>
                        <br>

                        <!-- example: two columns (simple) -->

                        <!--[if mso]>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr><td width="50%" valign="top"><![endif]-->

                        <table width="264" border="0" cellpadding="0" cellspacing="0" align="left" class="force-row">
                            <tr>
                                <td class="col" valign="top" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333;width:100%">
                                    <?= DetailView::widget([
                                        'model' => $request,
                                        'attributes' => [
                                            'id',
                                            'created_at:date',
                                            [
                                                'attribute' => 'customer',
                                                'value' => Html::a($request->getCustomerName(), Yii::$app->params['domain'] . '/customer/' . $request->customer),
                                                'format' => 'raw',
                                            ],
                                            [
                                                'attribute' => 'entity',
                                                'value' => Html::a($request->getEntityName(), Yii::$app->params['domain'] . '/entity/' . $request->entity),
                                                'format' => 'raw',
                                            ],
                                        ],
                                    ]) ?>
                                    <!--<strong>Herman Melville</strong>
                                    <br><br>
                                    It's worse than being in the whirled woods, the last day of the year! Who'd go climbing after chestnuts now? But there they go, all cursing, and here I don't.
                                    <br><br> -->
                                </td>
                            </tr>
                        </table>

                        <!--[if mso]></td><td width="50%" valign="top"><![endif]-->

                        <table width="264" border="0" cellpadding="0" cellspacing="0" align="right" class="force-row">
                            <tr>
                                <td class="col" valign="top" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333;width:100%">
                                    <p><?= Html::a('Детали запроса', Yii::$app->params['domain']. '/request/' . $request->id) ?></p>
                                    <p><?= Html::a('Добавить предложение', Yii::$app->params['domain'] . '/quotation/create/' . $request->id) ?></p>
                                    <!--<strong>I am Ishmael</strong>
                                    <br><br>
                                    White squalls? white whale, shirr! shirr! Here have I heard all their chat just now, and the white whale—shirr! shirr!—but spoken of once! and…
                                    <br><br> -->
                                </td>
                            </tr>
                        </table>

                        <!--[if mso]></td></tr></table><![endif]-->


                        <!--/ end example -->

                        <div class="hr" style="height:1px;border-bottom:1px solid #cccccc;clear: both;">&nbsp;</div>
                        <br>

                        <div class="subtitle" style="font-family:Helvetica, Arial, sans-serif;font-size:16px;font-weight:600;color:#2469A0;height:50px;">
                            Перечень товаров:
                        </div>

                        <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'summary' => '',
                                'columns' => [
                                    [
                                        'attribute' => 'product.code',
                                        'value' => function(RequestToProduct $requestToProduct) {
                                            return $requestToProduct->getProductOne()->code;
                                        },
                                    ],
                                    [
                                        'attribute' => 'product.name',
                                        'value' => function(RequestToProduct $requestToProduct) {
                                            return $requestToProduct->getProductOne()->name;
                                        },
                                    ],
                                    [
                                        'attribute' => 'product.material',
                                        'value' => function(RequestToProduct $requestToProduct) {
                                            return $requestToProduct->getProductOne()->getMaterialName();
                                        },
                                    ],
                                    [
                                        'attribute' => 'product.dia',
                                        'value' => function(RequestToProduct $requestToProduct) {
                                            return $requestToProduct->getProductOne()->dia;
                                        },
                                    ],
                                    [
                                        'attribute' => 'product.thread',
                                        'value' => function(RequestToProduct $requestToProduct) {
                                            return $requestToProduct->getProductOne()->thread;
                                        },
                                    ],
                                    'quantity',
                                ],
                            ]) ?>
                        </div>

                        <br>
                    </td>
                </tr>
                <tr>
                    <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
                        <br><br>
                        Все права защищены © 2016
                        <br><br>

                        Вы полчили это письмо потому, что зарегистрированы на сайте <?= Html::a(Yii::$app->params['domain'], Yii::$app->params['domain']) ?>.<br>
                        <a href="#" style="color:#aaaaaa">Изменить подписку</a>. <a href="#" style="color:#aaaaaa">Отписаться</a>.
                        <br><br>

                        <strong><?= Yii::$app->name ?>.</strong><br>
            <!--<span class="ios-footer">
              123 Main St.<br>
              Springfield, MA 12345<br>
            </span> -->
                        <?= Html::a(Yii::$app->params['domain'], Yii::$app->params['domain']) ?><br>

                        <br><br>

                    </td>
                </tr>
            </table>
            <!--/800px container -->


        </td>
    </tr>
</table>
<!--/100% background wrapper-->

</body>
</html>