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
     * Compiles the specified theme's LESS files into CSS files for both light and dark themes.
     *
     * This command compiles the specified theme's LESS files into CSS files for both light and dark themes.
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
        $cssFileDark = "$themePath/css/dark/dark.css";

        // Modify variables in the build.less file for dark theme
        $buildLessContent = file_get_contents("$lessDir/build.less");
        $buildLessContent = str_replace(
            ['@background-color: #ffffff;', '@text-color: #000000;'],
            ['@background-color: #000000;', '@text-color: #ffffff;'],
            $buildLessContent
        );
        file_put_contents("$lessDir/build.less", $buildLessContent);

        // Get the path to the lessc binary
        $lesscBinary = Yii::getAlias('@app/modules/composer/vendor/oyejorge/less.php/bin/lessc');

        // Execute lessc command to compile the LESS files into CSS files for light theme
        $lesscCommand = "$lesscBinary $lessDir/build.less $cssFile";
        exec($lesscCommand, $output, $returnVar);

        // Check if the compilation for light theme was successful
        if ($returnVar !== 0) {
            $this->stderr("Error: Compilation for light theme failed.\n", \yii\helpers\Console::FG_RED);
            return self::EXIT_CODE_ERROR;
        }

        // Execute lessc command to compile the LESS files into CSS files for dark theme
        $lesscCommandDark = "$lesscBinary $lessDir/build.less $cssFileDark";
        exec($lesscCommandDark, $outputDark, $returnVarDark);

        // Check if the compilation for dark theme was successful
        if ($returnVarDark !== 0) {
            $this->stderr("Error: Compilation for dark theme failed.\n", \yii\helpers\Console::FG_RED);
            return self::EXIT_CODE_ERROR;
        }

        // Restore the original content of build.less
        $buildLessContentOriginal = str_replace(
            ['@background-color: #000000;', '@text-color: #ffffff;'],
            ['@background-color: #ffffff;', '@text-color: #000000;'],
            $buildLessContent
        );
        file_put_contents("$lessDir/build.less", $buildLessContentOriginal);

        // Clear caches
        Yii::$app->cache->flush();

        // Output success message
        $this->stdout("Theme '$themeName' compiled successfully for both light and dark themes, and cache cleared.\n", \yii\helpers\Console::FG_GREEN);

        return self::EXIT_CODE_NORMAL;
    }
}
