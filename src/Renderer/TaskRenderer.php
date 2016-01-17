<?php

namespace DonePM\ConsoleClient\Renderer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskRenderer
 *
 * Renders a task
 *
 * @package DonePM\ConsoleClient\Renderer
 */
class TaskRenderer
{
    /**
     * Output reference
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * extra data
     *
     * @var array
     */
    private $includedData;

    /**
     * current task data
     *
     * @var array
     */
    private $task = [];

    /**
     * TaskRenderer constructor.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $includedData
     */
    public function __construct(OutputInterface $output, array $includedData = [])
    {
        $this->output = $output;
        $this->includedData = $includedData;
    }

    /**
     * sets current task
     *
     * @param array $task
     *
     * @return $this
     */
    public function setTask(array $task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * writes the current task to the output
     */
    public function writeln()
    {
        $this->output->writeln(
            sprintf('%s <options=bold>%s</>  <info>%s</info> %s',
                $this->getStatusLabel(),
                $this->getSummary(),
                $this->getTaskId(),
                $this->getStatus()
            )
        );
    }

    /**
     * returns the tasks summary
     *
     * @return string
     */
    private function getSummary()
    {
        return array_get($this->task, 'attributes.summary', '<No summary>');
    }

    /**
     * returns the status
     *
     * @return string
     */
    private function getStatus()
    {
        return '<fg=blue>♯' . array_get($this->task, 'attributes.status') . '</>';
    }

    /**
     * returns a status representation
     *
     * @return string
     */
    private function getStatusLabel()
    {
        return array_get($this->task, 'attributes.status') !== 'done' ? '▢' : '<fg=green>✔</>';
    }

    /**
     * returns task identifier
     *
     * @return string
     */
    private function getTaskId()
    {
        $projectSlug = $projectId = array_get($this->task, 'relationships.project.data.id');

        foreach ($this->includedData as $data) {
            if (array_get($data, 'id') == $projectId && array_get($data, 'type') === 'projects') {
                $projectSlug = array_get($data, 'attributes.slug');
                break;
            }
        }

        return '♯' . $projectSlug . '-' . array_get($this->task, 'attributes.identifier');
    }
}