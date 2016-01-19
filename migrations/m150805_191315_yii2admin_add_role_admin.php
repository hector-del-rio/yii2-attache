<?php

use yii\db\Migration;

class m150805_191315_yii2admin_add_role_admin extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->execute("INSERT INTO {{%auth_item}} VALUES ('admin', '1', 'Has access to everything.', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())");
        $this->execute("INSERT INTO {{%auth_item_child}} VALUES ('admin', '/*');");
    }

    public function safeDown()
    {
        $this->execute("DELETE FROM {{%auth_item_child}} WHERE `parent`='admin' and `child`='/*';");
        $this->execute("DELETE FROM {{%auth_item}} WHERE `name`='admin';");
    }
}
