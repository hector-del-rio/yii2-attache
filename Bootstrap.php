<?php

namespace hectordelrio\attache;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

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
    public $userTableName = 'user';
    /**
     * @var string|\yii\db\Connection database connection instance or name
     */
    public $db = null;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $tableNames = Yii::$app->db->getSchema()->tableNames;
        $userModule = $app->getModule('user');
        $adminModule = $app->getModule('admin');
        $authManager = $app->get('authManager', false);
        $userComponent = $app->get('user', false);

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

        if (!empty(array_diff([$this->userTableName, $authManager->assignmentTable], $tableNames))) {
            throw new \yii\db\Exception("Unable to find table {$this->userTableName} or {$authManager->assignmentTable}");
        }

        // yii2-user
        if (empty($userModule)) {
            $app->setModule('user', ArrayHelper::merge($this->admin, [
                'class' => 'dektrium\user\Module',
            ]));
        }

        if (!empty($userComponent)) {
            $userComponent->on('afterLogin', function () {
                // open session and store user profile in it
                $session = Yii::$app->session;
                $session->open();
                $session->set('profile', Yii::$app->user->identity->getProfile()->one()->attributes);
                $session->close();
            });
            $userComponent->on('afterLogout', function () {
                // close and destroy session data
                Yii::$app->session->destroy();
            });
        }

        // all users with admin role to has full access to yii2-user user management interface
        if (($authManager instanceof \yii\rbac\DbManager)) {
            if (empty(array_diff([$this->userTableName, $authManager->assignmentTable], $tableNames))) {
                Yii::$app->getModule('user')->admins = (new \yii\db\Query())
                    ->select('username')
                    ->from($this->userTableName)
                    ->leftJoin($authManager->assignmentTable, 'user_id = id')
                    ->where("item_name LIKE 'admin'")
                    ->column($this->db);
            }
        }

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