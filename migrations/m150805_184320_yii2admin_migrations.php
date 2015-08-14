<?php

namespace hectordelrio\attache\migrations;

use yii\db\Migration;

class m150805_184320_yii2admin_migrations extends Migration
{
    public function up()
    {
        $controller = Yii::$app->controller;
        $msg = "This migration will run all migrations in @mdm/admin/migrations and in @yii/rbac/migrations.";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        if ($controller->confirm("Do you want to continue?")) {
            passthru(Yii::getAlias("@app/yii") . " migrate/up  --migrationPath=@mdm/admin/migrations", $errorCode);

            if ($errorCode !== 0) {
                return false;
            }

            passthru(Yii::getAlias("@app/yii") . " migrate/up  --migrationPath=@yii/rbac/migrations", $errorCode);

            if ($errorCode !== 0) {
                return false;
            }

        } else {
            return false;
        }
    }

    public function down()
    {
        $controller = Yii::$app->controller;
        $msg = "This migration will run all migrations in @mdm/admin/migrations and in @yii/rbac/migrations.";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        if ($controller->confirm("Do you want to continue?")) {
            passthru(Yii::getAlias("@app/yii") . " migrate/down  --migrationPath=@mdm/admin/migrations", $errorCode);

            if ($errorCode !== 0) {
                return false;
            }

            passthru(Yii::getAlias("@app/yii") . " migrate/down  --migrationPath=@yii/rbac/migrations", $errorCode);

            if ($errorCode !== 0) {
                return false;
            }

        } else {
            return false;
        }
    }
}
