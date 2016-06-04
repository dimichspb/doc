<?php

/* @var $model \app\models\Order */

?>
<body style="width: 210mm; margin-left: auto; margin-right: auto; border: 1px #efefef solid; font-size: 11pt;">
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td style="width: 70mm">
            <div style="width:70mm; font-size: 6pt;">Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате  обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.</div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div style="text-align:center;  font-weight:bold;">
                Образец заполнения платежного поручения                                                                                                                                            </div>
        </td>
    </tr>
</table>


<table width="100%" cellpadding="2" cellspacing="2" style="border-collapse: collapse; border: 1px solid; ">
    <tr>
        <td colspan="2" rowspan="2" style="min-height:13mm; width: 105mm; border: 1px solid; ">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="height: 13mm;">
                <tr>
                    <td valign="top">
                        <div><?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getAccountOne()->getBankName() ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size:8pt;">Банк получателя        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="min-height:7mm;height:auto; width: 25mm; border: 1px solid; ">
            <div>БИK</div>
        </td>
        <td rowspan="2" style="vertical-align: top; width: 60mm; border: 1px solid; ">
            <div style=" height: 7mm; line-height: 7mm; vertical-align: middle;"><?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getAccountOne()->getBankCode() ?></div>
            <div><?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getAccountOne()->getBankAccount() ?></div>
        </td>
    </tr>
    <tr>
        <td style="width: 25mm; border: 1px solid; ">
            <div>Сч. №</div>
        </td>
    </tr>
    <tr>
        <td style="min-height:6mm; height:auto; width: 50mm; border: 1px solid; ">
            <div>ИНН <?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->inn ?></div>
        </td>
        <td style="min-height:6mm; height:auto; width: 55mm; border: 1px solid; ">
            <div>КПП <?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->kpp ?></div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 25mm; border: 1px solid; ">
            <div>Сч. №</div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 60mm; border: 1px solid; ">
            <div><?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getAccountOne()->number ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="min-height:13mm; height:auto; border: 1px solid; ">

            <table border="0" cellpadding="0" cellspacing="0" style="height: 13mm; width: 105mm;">
                <tr>
                    <td valign="top" style="">
                        <div><?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getFull() ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size: 8pt;">Получатель</div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<br/>

<div style="font-weight: bold; font-size: 16pt; padding-left:5px;">
    Счет № <?= $model->id ?> от <?= Yii::$app->formatter->format($model->created_at, 'date') ?></div>
<br/>

<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

<table width="100%">
    <tr>
        <td style="width: 30mm;">
            <div style=" padding-left:2px;">Поставщик:    </div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
                <?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getFull() ?>            </div>
        </td>
    </tr>
    <tr>
        <td style="width: 30mm;">
            <div style=" padding-left:2px;">Покупатель:    </div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
                <?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getFull() ?>            </div>
        </td>
    </tr>
</table>


<table style="border: 1px solid; border-collapse: collapse;" width="100%" cellpadding="2" cellspacing="2">
    <thead>
    <tr>
        <th style="width:13mm; border: 1px solid;">№</th>
        <th style="width:20mm; border: 1px solid;">Код</th>
        <th style="border: 1px solid;">Товар</th>
        <th style="width:20mm; border: 1px solid;">Кол-во</th>
        <th style="width:17mm; border: 1px solid;">Ед.</th>
        <th style="width:27mm; border: 1px solid;">Цена</th>
        <th style="width:27mm; border: 1px solid;">Сумма</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 0; $total = 0; ?>
    <?php foreach($model->getOrderToProductsAll() as $orderToProduct): ?>
    <?php $i++;?>
    <tr>
        <td><?= $i; ?></td>
        <td><?= $orderToProduct->getProductOne()->getFullCode() ?></td>
        <td><?= $orderToProduct->getProductOne()->name ?></td>
        <td><?= $orderToProduct->quantity ?></td>
        <td>шт.</td>
        <td><?= Yii::$app->formatter->format($orderToProduct->price, ['decimal', 2]) ?></td>
        <td><?= Yii::$app->formatter->format($orderToProduct->quantity * $orderToProduct->price, ['decimal', 2]) ?></td>
    </tr>
    <?php $total += $orderToProduct->quantity * $orderToProduct->price?>
    <?php endforeach; ?>
    </tbody>
</table>

<table border="0" width="100%" cellpadding="1" cellspacing="1">
    <tr>
        <td></td>
        <td style="width:27mm; font-weight:bold;  text-align:right;">Итого:</td>
        <td style="width:27mm; font-weight:bold;  text-align:right;"><?= Yii::$app->formatter->format($total, ['decimal', 2]) ?></td>
    </tr>
</table>

<br />
<div>
    Всего наименований 0 на сумму 0.00 рублей.<br />
    Ноль рублей 00 копеек</div>
<br /><br />
<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>
<br/>

<div>Руководитель ______________________ (<?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getDirectorShort() ?>)</div>
<br/>

<div>Главный бухгалтер ______________________ (<?= $model->getQuotationOne()->getRequestOne()->getEntityOne()->getAccountantShort() ?>)</div>
<br/>

<div style="width: 85mm;text-align:center;">М.П.</div>
<br/>

<div style="width:800px;text-align:left;font-size:10pt;">Счет действителен к оплате в течении трех дней.</div>
</body>