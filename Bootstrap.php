<?php

namespace hectordelrio\attache;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;
use yii\web\Application as WebApplication;
use yii\web\User;

class Bootstrap implements BootstrapInterface
{
    public $enableAdminsFromDatabase = true;
    public $enableStoreProfileInSession = true;
    public $db = 'db';

    public function bootstrap($app)
    {
        if ($this->enableAdminsFromDatabase) {
            $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'addAdminsFromDatabase']);
        }

        if (($app instanceof WebApplication) and $this->enableStoreProfileInSession) {
            $app->on(User::EVENT_AFTER_LOGIN, [$this, 'storeProfileInSession']);
            $app->on(User::EVENT_AFTER_LOGOUT, [$this, 'destroyProfileFromSession']);
        }

    }

    public function addAdminsFromDatabase($event)
    {
        $app = Yii::$app;
        $db = $app->get($this->db);
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

    public function storeProfileInSession($event)
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('profile', Yii::$app->user->identity->getProfile()->one()->attributes);
        $session->close();
    }

    public function destroyProfileFromSession($event)
    {
        Yii::$app->session->destroy();
    }
}