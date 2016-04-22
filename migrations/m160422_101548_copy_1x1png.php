<?php

use yii\db\Migration;
use yii\helpers\FileHelper;

class m160422_101548_copy_1x1png extends Migration
{
    public function up()
    {
        $imagesDirectory = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'images';
        FileHelper::createDirectory($imagesDirectory);
        file_put_contents($imagesDirectory . DIRECTORY_SEPARATOR . '1x1.png', base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII="));        
    }

    public function down()
    {
        $imagesDirectory = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'images';
        FileHelper::removeDirectory($imagesDirectory);
    }

}
