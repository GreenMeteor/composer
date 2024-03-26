<?php

namespace humhub\modules\composer;

class Module extends \humhub\components\Module
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    public $controllerNamespace = 'humhub\modules\composer\commands';

    public function init()
    {
        parent::init();
    }

    public function actions()
    {
        return [
            'theme/compile' => 'composer\commands\ThemeController',
        ];
    }

    public function getConfigUrl()
    {
        return '/composer/composer/index';
    }
}
