<?php

namespace DonePM\ConsoleClient\Commands\Tasks;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Tasks\IndexCommand;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ListCommand
 *
 * @package DonePM\ConsoleClient\Commands\Tasks
 */
class ListCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('task:list')
            ->setDescription('List all tasks')
            ->addOption('project', 'p', InputOption::VALUE_OPTIONAL, 'Fetch tasks for this project only')
            ->addOption('status', 's', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Fetch tasks only with this status(es)');
    }

    /**
     * executes the command
     */
    protected function handle()
    {
        $client = $this->getClient();

        $command = new IndexCommand();

        try {
            $response = $client->send($command);
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $client->setToken($this->getApplication()->resetConfig()->config()->get('token'));

                $response = $client->send($command);
            } else {
                $this->error($e->getMessage());
                return;
            }
        }

        if ($response->getStatusCode() >= 300) {
            $this->info('No Tasks found');

            return;
        }

        $tasksData = json_decode($response->getBody()->getContents(), true)['data'];

        $tasks = new Collection($tasksData);

        $statusIncluded = $this->input->getOption('status');
        if ( !empty($statusIncluded)) {
            $tasks = $tasks->filter(function ($task) use ($statusIncluded) {
                return in_array($task['status'], $statusIncluded);
            });
        }

        $projectFilter = $this->input->getOption('project');
        if ($projectFilter) {
            $tasks = $tasks->filter(function ($task) use ($projectFilter) {
                return $task['project'] === $projectFilter;
            });
        }

        if ($tasks->isEmpty()) {
            $this->info('No tasks found');
            return;
        }

        $output = $this->output;
        $this->getFilteredOrderedTasks($tasks)->each(function ($task) use ($output) {
            $output->writeln(sprintf('%s <options=bold>%s</>  <info>%s</info> %s', $this->getCheckbox($task), $task['summary'], $this->getId($task), $this->getStatus($task)));
        });
    }

    /**
     * @param \Illuminate\Support\Collection $tasks
     *
     * @return \Illuminate\Support\Collection
     */
    private function getFilteredOrderedTasks(Collection $tasks)
    {
        return $tasks->sort(function($a, $b) {
            if($a['project'] === $b['project']) {
                if($a['id'] === $b['id']) {
                    return 0;
                }
                return $a['id'] < $b['id'] ? -1 : 1;
            }
            return $a['project'] < $b['project'] ? -1 : 1;
        });
    }

    /**
     * @param array $task
     *
     * @return string
     */
    private function getCheckbox($task)
    {
        return $task['status'] !== 'done' ? '▢' : '<fg=green>✔</>';
    }

    /**
     * @param array $task
     *
     * @return string
     */
    private function getId($task)
    {
        return '♯' . $task['project'] . '-' . $task['id'];
    }

    /**
     * @param array $task
     *
     * @return string
     */
    private function getStatus($task)
    {
        return '<fg=blue>♯' . $task['status'] . '</>';
    }
}