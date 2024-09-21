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
     * Get the root directory of the application (webroot).
     *
     * @return string The root directory path.
     */
    public static function getRootPath(): string
    {
        return Yii::getAlias('@webroot');
    }

    /**
     * Get the path to the root composer.json file.
     *
     * @return string The path to the root composer.json file.
     */
    public static function getRootComposerPath(): string
    {
        return self::getRootPath() . DIRECTORY_SEPARATOR . 'composer.json';
    }

    /**
     * Get the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    public static function getThemePath(): string
    {
        return Yii::getAlias('@webroot/themes');
    }

    /**
     * Get the path to a specific theme's directory.
     *
     * @param string $themeName The name of the theme.
     * @return string The path to the theme's directory.
     */
    public static function getThemeDirectoryPath(string $themeName): string
    {
        return self::getThemePath() . DIRECTORY_SEPARATOR . $themeName;
    }

    /**
     * Get the path to a specific theme's composer.json file.
     *
     * @param string $themeName The name of the theme.
     * @return string The path to the theme's composer.json file.
     */
    public static function getThemeComposerPath(string $themeName): string
    {
        return self::getThemeDirectoryPath($themeName) . DIRECTORY_SEPARATOR . 'composer.json';
    }

    /**
     * Get the path to a specific module's directory.
     *
     * @param string $moduleName The name of the module.
     * @return string The path to the module's directory.
     */
    public static function getModulePath(string $moduleName): string
    {
        return Yii::getAlias('@webroot/protected/modules/' . $moduleName);
    }

    /**
     * Get the path to a specific module's composer.json file.
     *
     * @param string $moduleName The name of the module.
     * @return string The path to the module's composer.json file.
     */
    public static function getModuleComposerPath(string $moduleName): string
    {
        return self::getModulePath($moduleName) . DIRECTORY_SEPARATOR . 'composer.json';
    }
}
