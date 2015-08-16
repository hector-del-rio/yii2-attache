# yii2-attache
Will ease up the pains of combining yii2-user and yii2-admin extensions.

Usage
-----

First, run:

```shell
    $ ./yii migrate --migrationPath="@app/vendor/hector-del-rio/yii2-attache/migrations"
```

Then in config/common.php

```php
    'bootstrap' => [
        ...
        'hectordelrio\attache\Bootstrap'
        // or if you need to configure something:
        [
            'class' => 'hectordelrio\attache\Bootstrap',
            'option1' => 'value1',
            'option2' => 'value2',
            ...
        ]
        ...
    ],
    'modules' => [
        ...
        'user' => [
            'class' => 'dektrium\user\Module',
        ],
        ...
    ],
    'components' => [
        ...
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        ...
    ],
    ...
```

in config/web.php

```php
    'components' => [
        ...
        'user' => [
            'on afterLogin' => ['hectordelrio\attache\EventHandler', 'storeProfileInSession'],
            'on afterLogout' => ['hectordelrio\attache\EventHandler', 'destroyProfileFromSession'],
        ]
        ...
    ],
    'as access' => 'mdm\admin\components\AccessControl',
```

Options
-------

 * enableAdminsFromDatabase : defaults ```true```. It will list all users with admin role to yii2-user's ```admins``` array.
 * enableStoreProfileInSession : defaults ```true```. Allows you to access user profile via session: ```Yii::$app->session['profile']['name']```.
 * db: defaults to ```'db'```. If your database component is called differently, you can specify it here.
