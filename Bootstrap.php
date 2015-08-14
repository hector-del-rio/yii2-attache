<?php
/**
 * Created by PhpStorm.
 * User: Hector
 * Date: 14.08.15
 * Time: 21:41
 */

namespace hectordelrio\attache;


use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['attache'] = 'hectordelrio\attache\commands\AttacheController';
        }
    }
}
