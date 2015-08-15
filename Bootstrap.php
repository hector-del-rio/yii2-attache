<?php

namespace hectordelrio\attache;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\web\Application as WebApplication;

class Bootstrap implements BootstrapInterface
{
    /**
     * @var array yii2-user extension options
     */
    public $user = [];
    /**
     * @var array yii2-admin extension options
     */
    public $admin = [];
    /**
     * @var string user table name
     */
    public $userTableName = '{{%user}}';
    /**
     * @var string database connection instance or name
     */
    public $db = 'db';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (!($app instanceof WebApplication)) {
            return;
        }

        $db = $app->get($this->db);

        $tableNames = Yii::$app->db->getSchema()->tableNames;
        $userModule = $app->getModule('user');
        $adminModule = $app->getModule('admin');
        $app->set('authManager', [
            'class' => 'yii\rbac\DbManager'
        ]);
        $authManager = $app->getAuthManager();

        if (
            ($userModule !== null and !($userModule instanceof \dektrium\user\Module)) or
            ($adminModule !== null and !($adminModule instanceof \mdm\admin\Module))
        ) {
            // yii2-attache does not do anything if other user or admin modules are defined
            Yii::warning(
                'yii2-attache will not be performing any action because:'
                . ' a) module "user" is already defined in your application.'
                . ' b) module "admin" is already defined in your application.'
            );
            return;
        }

        // yii2-user
        if (empty($userModule)) {
            $app->setModule('user', ArrayHelper::merge($this->admin, [
                'class' => 'dektrium\user\Module',
            ]));
        }

        $app->set('user', ArrayHelper::merge($this->admin, [
            'class' => 'yii\web\User',
            'identityClass' => 'dektrium\user\models\User',
            'on afterLogin' => function () {
                // open session and store user profile in it
                $session = Yii::$app->session;
                $session->open();
                $session->set('profile', Yii::$app->user->identity->getProfile()->one()->attributes);
                $session->close();
            },
            'on afterLogout' => function () {
                // close and destroy session data
                Yii::$app->session->destroy();
            },
        ]));

        $userTableName = $db->schema->getRawTableName($this->userTableName);
        $authAssignmentTableName = $db->schema->getRawTableName($authManager->assignmentTable);
        // all users with admin role to has full access to yii2-user user management interface
        if (!empty(array_diff([$userTableName, $authAssignmentTableName], $tableNames))) {
            throw new \yii\db\Exception("Unable to find table {$userTableName} or {$authAssignmentTableName}");
        }

        Yii::$app->getModule('user')->admins = (new \yii\db\Query())
            ->select('username')
            ->from($this->userTableName)
            ->leftJoin($authManager->assignmentTable, 'user_id = id')
            ->where("item_name LIKE 'admin'")
            ->column($this->db);

        // yii2-admin
        if (empty($adminModule)) {
            $app->setModule('admin', ArrayHelper::merge($this->admin, [
                'class' => 'mdm\admin\Module'
            ]));
        }

        // attach yii2-admin access behavior
        if (empty($app->getBehavior('access'))) {
            $app->attachBehavior('access', [
                'class' => 'mdm\admin\components\AccessControl',
            ]);
        }
    }
}