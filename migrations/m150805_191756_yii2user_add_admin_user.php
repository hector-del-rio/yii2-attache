<?php

use yii\db\Migration;
use dektrium\user\models\User;
use dektrium\user\models\LoginForm;

class m150805_191756_yii2user_add_admin_user extends Migration
{
    public function safeUp()
    {
        $app = Yii::$app;

        $app->setModule('user', [
            'class' => 'dektrium\user\Module'
        ]);

        if (!isset($app->get('i18n')->translations['user*'])) {
            $app->get('i18n')->translations['user*'] = [
                'class'    => \yii\i18n\PhpMessageSource::className(),
                'basePath' => Yii::getAlias('@dektrium/user/migrations'),
            ];
        }

        $controller = Yii::$app->controller;
        $user = new User(['scenario' => 'register']);

        echo $controller->ansiFormat("\n\n ==> Create Admin User\n", \yii\helpers\Console::FG_CYAN);

        do {

            if ($user->hasErrors()) {
                $this->showErrors($user);
            }

            // get email
            $email = $controller->prompt(
                $controller->ansiFormat("\tE-mail: ",
                    \yii\helpers\Console::FG_BLUE)
            );

            // get username
            $username = $controller->prompt($controller->ansiFormat("\tUsername: ", \yii\helpers\Console::FG_BLUE));

            // get password
            echo $controller->ansiFormat("\tPassword: ", \yii\helpers\Console::FG_BLUE);
            system('stty -echo');
            $password = trim(fgets(STDIN));
            system('stty echo');
            echo "\n";

            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            $user->confirmed_at = new \yii\db\Expression('UNIX_TIMESTAMP()');

        } while (!$user->save());

        do {
            // get realname
            $name = $controller->prompt($controller->ansiFormat("\tFull name: ",
                \yii\helpers\Console::FG_BLUE));

            echo "\n\n";

        } while (empty($name));

        $this->update('profile', ['name' => $name], 'user_id=:user_id',[
            ':user_id' => $user->primaryKey
        ]);

        $this->insert('auth_assignment', [
            'item_name' => 'admin',
            'user_id' => $user->primaryKey,
            'created_at' => new \yii\db\Expression('UNIX_TIMESTAMP()')
        ]);
    }

    public function safeDown()
    {
        $controller = Yii::$app->controller;
        $model = \Yii::createObject(LoginForm::className());

        do {

            if ($model->hasErrors()) {
                $this->showErrors($model);
            }

            // get username
            $username = $controller->prompt($controller->ansiFormat("\tUsername: ", \yii\helpers\Console::FG_BLUE));

            // get password
            echo $controller->ansiFormat("\tPassword: ", \yii\helpers\Console::FG_BLUE);
            system('stty -echo');
            $password = trim(fgets(STDIN));
            system('stty echo');
            echo "\n";

            $model->login = $username;
            $model->password = $password;

        } while (!$model->validate());

        $user = User::findOne(['username' => $username]);

        if (empty($user)) {
            throw new \yii\console\Exception("Unable to find user {$username}");
        }

        $this->delete('auth_assignment', [
            'item_name' => 'admin',
            'user_id' => $user->primaryKey,
        ]);

        $user->delete();
    }

    protected function showErrors($model)
    {
        $controller = Yii::$app->controller;

        if ($model->hasErrors()) {
            $msg = "\n ==> Some fields are incorrect, please try again.\n";
            echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_RED);

            $msg = '';
            foreach ($model->errors as $errorField => $errors) {
                $msg .= "\t{$errorField}: " . implode(", ", $errors) . "\n";
            }

            echo $controller->ansiFormat($msg, \yii\helpers\Console::FG_RED) . "\n";
        }
    }

}
