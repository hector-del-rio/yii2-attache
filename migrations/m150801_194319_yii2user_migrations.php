<?php

namespace hectordelrio\attache\migrations;

use yii\db\Migration;

class m150801_194319_yii2user_migrations extends Migration
{
    public function up()
    {
        $controller = Yii::$app->controller;
        $msg = "This migration will run all migrations in @vendor/dektrium/yii2-user/migrations.";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        if ($controller->confirm("Do you want to continue?")) {
            passthru(Yii::getAlias("@app/yii") . " migrate/up  --migrationPath=@vendor/dektrium/yii2-user/migrations", $errorCode);

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
        $msg = "This migration will run all migrations in @vendor/dektrium/yii2-user/migrations.";

        echo "\n\n";
        echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_GREEN) . "\n";

        if ($controller->confirm("Do you want to continue?")) {
            passthru(Yii::getAlias("@app/yii") . " migrate/down  --migrationPath=@vendor/dektrium/yii2-user/migrations", $errorCode);

            if ($errorCode !== 0) {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
