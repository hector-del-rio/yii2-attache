<?php

use yii\db\Migration;

class m150805_192556_yii2admin_add_menu_items extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->execute(
            "INSERT INTO {{%menu}} VALUES"
            . " ('1', 'Admin', NULL, NULL, '0', 'return [\'icon\' => \'fa-terminal\'];'),"
            . " ('9', 'Authentication', '1', NULL, '1', 'return [\'icon\' => \'fa-key\'];'),"
            . " ('10', 'Gii', '1', '/gii', '3', 'return [\'icon\' => \'fa-code\'];'),"
            . " ('2', 'Roles', '9', '/admin/role/index', '1', NULL),"
            . " ('3', 'Menu', '1', '/admin/menu/index', '2', 'return [\'icon\' => \'fa-list\'];'),"
            . " ('4', 'Assignments', '9', '/admin/assignment/index', '3', NULL),"
            . " ('6', 'Routes', '9', '/admin/route/index', '0', NULL),"
            . " ('7', 'Permissions', '9', '/admin/permission/index', '2', NULL),"
            . " ('8', 'Users', '1', '/user/admin/index', '0', 'return [\'icon\' => \'fa-users\'];')"
        );
    }

    public function safeDown()
    {
        $this->execute(
            "DELETE FROM {{%menu}} WHERE `id` IN (1,2,3,4,6,7,8,9,10);"
        );
    }
}
