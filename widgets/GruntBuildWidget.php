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

        try {
            // Use array format for command to prevent shell injection
            $command = ['grunt', $this->task];
            
            // Safely add options to the command array
            foreach ($this->options as $key => $value) {
                $command[] = '--' . $key;
                $command[] = $value;
            }
            
            // Use proc_open for safer execution with array arguments
            $descriptorSpec = [
                0 => ["pipe", "r"],  // stdin
                1 => ["pipe", "w"],  // stdout
                2 => ["pipe", "w"]   // stderr
            ];
            
            $process = proc_open($command, $descriptorSpec, $pipes);
            
            if (is_resource($process)) {
                // Close stdin as we don't need it
                fclose($pipes[0]);
                
                // Get output
                $output = stream_get_contents($pipes[1]);
                $errorOutput = stream_get_contents($pipes[2]);
                
                // Close pipes
                fclose($pipes[1]);
                fclose($pipes[2]);
                
                // Get exit code
                $returnVar = proc_close($process);
                
                if ($returnVar !== 0) {
                    throw new \Exception("Grunt task '{$this->task}' failed with output: " . $output . $errorOutput);
                }
                
                return Html::tag('pre', Html::encode($output));
            } else {
                throw new \Exception("Failed to start Grunt process");
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return Html::tag('pre', 'Grunt build failed. Check application logs for details.');
        }
    }
}
