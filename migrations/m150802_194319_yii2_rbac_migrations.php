<?php

use yii\console\Controller;
use yii\db\Migration;

class m150802_194319_yii2_rbac_migrations extends Migration
{
    public function up()
    {
        $controller = Yii::$app->controller;
        $msg = " => Migrating up yii2-user's migrations in @yii/rbac/migrations";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        system(Yii::getAlias("@app/yii") . " migrate/up  --interactive=0 --migrationPath=@yii/rbac/migrations", $errorCode);

        if ($errorCode == "0") {
            return Controller::EXIT_CODE_NORMAL;
        } else {
            return Controller::EXIT_CODE_ERROR;
        }
    }

    public function down()
    {
        $controller = Yii::$app->controller;
        $msg = " => Migrating down yii2-user's migrations in @yii/rbac/migrations";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        system(Yii::getAlias("@app/yii") . " migrate/down  --interactive=0 --migrationPath=@yii/rbac/migrations", $errorCode);

        if ($errorCode == "0") {
            return Controller::EXIT_CODE_NORMAL;
        } else {
            return Controller::EXIT_CODE_ERROR;
        }
    }
}
