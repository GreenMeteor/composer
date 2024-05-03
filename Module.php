<?php

namespace humhub\modules\composer;

use Yii;
use yii\helpers\Url;
use humhub\components\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    // Define the namespace for web controllers
    public $controllerNamespace = 'humhub\modules\composer\controllers';

    // Define the namespace for console commands
    public $commandNamespace = 'humhub\modules\composer\commands';

    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            // Set controller namespace to default web controllers namespace for console application
            $this->controllerNamespace = 'humhub\modules\composer\controllers';
            // Define console command mappings
            $this->controllerMap = [
                'theme' => 'humhub\modules\composer\commands\ThemeController',
            ];
        }
    }

    public function actions()
    {
        return [
            'theme/compile' => 'humhub\modules\composer\commands\ThemeController',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/composer/composer']);
    }
}
