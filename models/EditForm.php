<?php

namespace humhub\modules\composer\models;

use Yii;
use yii\base\Model;

/**
 * EditForm is the model class for handling composer.json editing.
 *
 * @property string $composerData The content of the composer.json file.
 */
class EditForm extends Model
{
    public $composerData;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['composerData'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'composerData' => Yii::t('ComposerModule.base', 'Composer Data'),
        ];
    }

    /**
     * Saves the updated composer data to the composer.json file.
     * @return bool whether the saving is successful
     * @throws \RuntimeException if the composer.json file is not found
     */
    public function saveComposerData()
    {
        $composerJsonFile = $_SERVER['DOCUMENT_ROOT'] . '/composer.json';

        if (!file_exists($composerJsonFile)) {
            throw new \RuntimeException('composer.json file not found.');
        }

        $composerData = json_decode($this->composerData, true);

        // Save updated composer.json file
        return file_put_contents($composerJsonFile, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
