<?php

namespace humhub\modules\composer\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use humhub\modules\composer\services\GruntService;

/**
 * GruntBuildWidget is responsible for executing specified Grunt tasks.
 *
 * This widget abstracts the logic for running Grunt commands and handling their output.
 */
class GruntBuildWidget extends Widget
{
    /**
     * @var string The Grunt task to execute (e.g., 'build-assets', 'build-search').
     */
    public $task;

    /**
     * @var array Additional options for the Grunt task.
     * This can include parameters such as 'module' for migrations.
     */
    public $options = [];

    /**
     * @var GruntService Instance of GruntService to handle command execution.
     */
    private $gruntService;

    /**
     * Constructor to initialize the GruntService.
     *
     * @param array $config Configuration array. 
     * Can include task and options for the widget.
     * 
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->gruntService = new GruntService();
    }

    /**
     * Executes the specified Grunt task and returns the output.
     *
     * Validates the task, executes the corresponding Grunt command, and captures the output.
     *
     * @return string The rendered output of the Grunt command, or an error message if execution fails.
     */
    public function run()
    {
        $validTasks = [
            'build-assets', 'build-search',
            'migrate-up'
        ];

        if (!in_array($this->task, $validTasks)) {
            return Html::tag('pre', "Invalid Grunt task: {$this->task}");
        }

        $task = escapeshellarg($this->task);
        $optionString = $this->buildOptionString($this->options);
        $command = "grunt {$task} {$optionString}";

        try {
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            if ($returnVar !== 0) {
                throw new \Exception("Grunt task '{$this->task}' failed with output: " . implode("\n", $output));
            }
            return Html::tag('pre', Html::encode(implode("\n", $output)));
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return Html::tag('pre', 'Grunt build failed. Check application logs for details.');
        }
    }

    protected function buildOptionString($options)
    {
        $optionString = '';
        foreach ($options as $key => $value) {
            $optionString .= '--' . escapeshellarg($key) . ' ' . escapeshellarg($value) . ' ';
        }
        return trim($optionString);
    }
}
