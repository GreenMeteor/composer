<?php

namespace humhub\modules\composer\commands;

use humhub\modules\ui\view\helpers\ThemeHelper;
use yii\console\Controller;
use Yii;

/**
 * ThemeController is a console command controller for compiling theme LESS files into CSS.
 */
class ThemeController extends Controller
{
    /**
     * Compiles the specified theme's LESS files into a single CSS file using oyejorge/less.php's lessc command.
     *
     * This command compiles the specified theme's LESS files into a single `theme.css` file using lessc command.
     * It also clears the cache after compilation.
     *
     * Usage:
     * ```
     * php yii composer/theme/compile ThemeName
     * ```
     * OR
     * ```
     * php yii composer:theme:compile ThemeName
     * ```
     *
     * @param string $themeName The name of the theme to compile.
     * @return int The exit status code
     */
    public function actionCompile($themeName)
    {
        // Get all available themes
        $themes = ThemeHelper::getThemes();
        
        // Check if the specified theme exists
        if (!isset($themes[$themeName])) {
            $this->stderr("Error: Theme '$themeName' not found.\n", \yii\helpers\Console::FG_RED);
            return self::EXIT_CODE_ERROR;
        }
        
        // Get the path to the specified theme's directory
        $themePath = $themes[$themeName]->getBasePath();

        // Define the paths for the LESS and CSS files
        $lessDir = "$themePath/less";
        $cssFile = "$themePath/css/theme.css";

        // Get the path to the lessc binary
        $lesscBinary = Yii::getAlias('@app/modules/composer/vendor/oyejorge/less.php/bin/lessc');

        // Execute lessc command to compile the LESS files into CSS
        $lesscCommand = "$lesscBinary $lessDir/build.less $cssFile";
        exec($lesscCommand, $output, $returnVar);

        // Check if the compilation was successful
        if ($returnVar !== 0) {
            $this->stderr("Error: Compilation failed.\n", \yii\helpers\Console::FG_RED);
            return self::EXIT_CODE_ERROR;
        }

        // Clear caches
        Yii::$app->cache->flush();

        // Output success message
        $this->stdout("Theme '$themeName' compiled successfully and cache cleared.\n", \yii\helpers\Console::FG_GREEN);

        return self::EXIT_CODE_NORMAL;
    }
}