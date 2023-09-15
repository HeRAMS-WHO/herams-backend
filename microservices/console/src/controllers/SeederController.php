<?php
namespace herams\console\controllers;


use herams\console\seeders\SeederBase;
use yii\console\Controller;
use yii\helpers\Console;

class SeederController extends Controller
{
    public $seed;

    public function options($actionID)
    {
        return ['seed'];
    }

    public function optionAliases()
    {
        return ['seed' => 'seed'];
    }

    public function actionIndex()
    {
        //check if class $seed exists in seeders folder and if it a subclass of SeederBase
        //if it does, run the run() method
        //if it doesn't, throw an error
        Console::output("SeederController");
        $className = 'herams\\console\\seeders\\' . $this->seed . 'Seeder';
        Console::output($className);
        if (class_exists($className)) {
            $seeder = new $className();
            if ($seeder instanceof SeederBase) {
                $seeder->run();
            } else {
                Console::output("Seeder class must be a subclass of SeederBase");
            }
        } else {
            Console::output("Seeder class does not exist");
        }
    }

}
