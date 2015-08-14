<?php

namespace hectordelrio\attache;


use yii\helpers\FileHelper;

class AttacheController extends \yii\console\Controller
{
    public $defaultAction = 'up';

    protected $migrationClasses = [];

    public function init()
    {
        parent::init();

        $migrations = FileHelper::findFiles(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations', [
            'only' => ['*.php'],
            'recursive' => false
        ]);

        foreach ($migrations as $migration) {
            require_once($migration);
        }

        $this->migrationClasses = array_filter(get_declared_classes(), function($className){
            return (stripos($className, __NAMESPACE__ . '\migrations') !== false);
        });
    }

    public function actionUp()
    {
        foreach ($this->migrationClasses as $migrationClass) {
            $migration = new $migrationClass;
            $migration->up();
        }
    }

    public function actionDown()
    {
        foreach ($this->migrationClasses as $migrationClass) {
            $migration = new $migrationClass;
            $migration->down();
        }
    }
}
