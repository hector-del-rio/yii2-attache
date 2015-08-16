<?php

namespace hectordelrio\attache;

use Yii;

class RequestHandler
{
    public static function addAdminsFromDatabase($event)
    {
        $db = 'db';
        if (isset($event->data['dbComponent'])) {
            $db = $event->data['dbComponent'];
        }

        $app = Yii::$app;
        $db = $app->get($db);
        $authManager = $app->getAuthManager();

        $tableNames = $db->schema->tableNames;
        $userTableName = $db->schema->getRawTableName('{{%user}}');
        $authAssignmentTableName = $db->schema->getRawTableName($authManager->assignmentTable);

        if (empty(array_diff([$userTableName, $authAssignmentTableName], $tableNames))) {
            $app->getModule('user')->admins = (new \yii\db\Query())
                ->select('username')
                ->from($userTableName)
                ->leftJoin($authAssignmentTableName, 'user_id = id')
                ->where("item_name = 'admin'")
                ->column($db);
        } else {
            throw new \yii\db\Exception("Unable to find table {$userTableName} or {$authAssignmentTableName}");
        }
    }
}