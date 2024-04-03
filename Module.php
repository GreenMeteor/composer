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

    public $controllerNamespace = 'humhub\modules\composer\controllers';

    public function init()
    {
        parent::init();
    }

    public function actions()
    {
        return [
            'theme/compile' => 'composer\commands\ThemeController',
            'refresh-assets' => 'composer\commands\RefreshAssetsController',
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
