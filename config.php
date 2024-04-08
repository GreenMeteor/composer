<?php

use humhub\modules\composer\Module;
use humhub\modules\composer\Events;
use humhub\widgets\TopMenu;

return [
    'id' => 'composer',
    'class' => Module::class,
    'namespace' => 'humhub\modules\composer',
    'events' => [
        ['class' => TopMenu::class, 'event' => TopMenu::EVENT_INIT, 'callback' => [Events::class, 'onTopMenuInit']],
    ],
];
