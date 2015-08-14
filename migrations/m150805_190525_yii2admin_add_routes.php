<?php

namespace hectordelrio\attache\migrations;

use yii\db\Migration;

class m150805_190525_yii2admin_add_routes extends Migration
{
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` VALUES ('/*',2,NULL,NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP())");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM `auth_item` WHERE `name`='/*'");
    }
}
