<?php

namespace humhub\modules\composer;

use Yii;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\models\User;

/**
 * Events is a class that handles event hooks related to the composer module.
 */
class Events
{
    /**
     * Event handler for initializing the top menu.
     * Adds an entry to the top menu for editing the composer.json file, visible to administrators.
     * 
     * @param \yii\base\Event $event the event parameter
     */
    public static function onTopMenuInit($event)
    {
        $menu = $event->sender;

        // Check if the current user is an admin
        if (Yii::$app->user->isAdmin()) {
            $menu->addEntry(new MenuLink([
                'icon' => 'fa-code',
                'label' => Yii::t('ComposerModule.base', 'Edit Composer'),
                'url' => '#',
                'htmlOptions' => [
                    'data-action-click' => 'ui.modal.load',
                    'data-action-click-url' => '/composer/edit/composer',
                    'data-pjax-prevent' => ''
                ],
                'sortOrder' => 1000,
            ]));
        }
    }
}
