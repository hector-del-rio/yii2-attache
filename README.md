# yii2-attache
Will ease up the pains of combining yii2-user and yii2-admin extensions.

Usage
-----

Add in config/common.php (or config file that applies to web and console applications):

```php
    'components' => [
        ...
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        ...
    ],
    ...
```

in config/web.php (or config file that applies ONLY to web application)

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
            // yii2-user options
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            // yii2-admin options
        ],
        ...
    ],
```

Then run:

```shell
    $ ./yii migrate --migrationPath="@app/vendor/hector-del-rio/yii2-attache/migrations"
```

Options
-------

 * enableAdminsFromDatabase : defaults ```true```. It will list all users with admin role to yii2-user's ```admins``` array.
 * enableStoreProfileInSession : defaults ```true```. Allows you to access user profile via session: ```Yii::$app->session['profile']['name']```.
 * db: defaults to ```'db'```. If your database component is called differently, you can specify it here.
