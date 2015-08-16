<?php

use yii\db\Migration;

class m150805_184320_yii2admin_migrations extends Migration
{
    public function up()
    {
        $controller = Yii::$app->controller;
        $msg = " => Migrating up yii2-user's migrations in @vendor/dektrium/yii2-user/migrations";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        system(Yii::getAlias("@app/yii") . " migrate/up  --interactive=0 --migrationPath=@mdm/admin/migrations", $errorCode);

        if ($errorCode == "0") {
            return true;
        } else {
            return false;
        }
    }

    public function down()
    {
        $controller = Yii::$app->controller;
        $msg = " => Migrating down yii2-user's migrations in @vendor/dektrium/yii2-user/migrations";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        system(Yii::getAlias("@app/yii") . " migrate/down  --interactive=0 --migrationPath=@mdm/admin/migrations", $errorCode);

        if ($errorCode == "0") {
            return true;
        } else {
            return false;
        }
    }
}
