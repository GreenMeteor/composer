<?php

namespace humhub\modules\composer\helpers;

use Yii;

/**
 * Class IDEHelper
 *
 * This class provides IDE helper methods for better code autocompletion and type hinting in your IDE.
 */
class IDEHelper
{   
    /**
     * Get the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    public static function getThemePath()
    {
        return Yii::getAlias('@webroot/themes');
    }

    /**
     * Get the path to a specific theme's directory.
     *
     * @param string $themeName The name of the theme.
     * @return string The path to the theme's directory.
     */
    public static function getThemeDirectoryPath($themeName)
    {
        return self::getThemePath() . DIRECTORY_SEPARATOR . $themeName; //@todo: Add wrapper for $themeName
    }

    /**
     * Get the path to a specific theme's composer.json directory.
     *
     * @param string $themeName The name of the theme.
     * @return string The path to the theme's composer.json directory.
     */
    public static function getThemeComposerPath($themeName)
    {
        return self::getThemeDirectoryPath($themeName) . 'composer.json';
    }

    /**
     * Get the path to a specific module's directory.
     *
     * @param string $moduleName The name of the module.
     * @return string The path to the module's directory.
     */
    public static function getModulePath($moduleName)
    {
        return Yii::getAlias('@app/modules/' . $moduleName); // @todo: Add wrapper for $moduleName
    }

    /**
     * Get the path to a specific module's composer.json directory.
     *
     * @param string $moduleName The name of the module.
     * @return string The path to the module's composer.json directory.
     */
    public static function getModuleComposerPath($moduleName)
    {
        return self::getModulePath($moduleName) . 'composer.json';
    }
}
