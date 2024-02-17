<?php

namespace humhub\modules\composer;

class Module extends \humhub\components\Module
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    public function getConfigUrl()
    {
        return '/composer/composer/index';
    }
}
