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
            ->setDescription('List all tasks');
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

                return 1;
            }
        }

        if ($response->getStatusCode() >= 300) {
            $this->info('No Tasks found');

            return 0;
        }

        $responseData = json_decode($response->getBody()->getContents(), true);
        $tasksData = $responseData['data'];
        $includedData = $responseData['included'];

        $tasks = new Collection($tasksData);

        if ($tasks->isEmpty()) {
            $this->info('No tasks found');

            return 0;
        }

        $output = $this->output;
        $this->getFilteredOrderedTasks($tasks)->each(function ($task) use ($output, $includedData) {
            $output->writeln(sprintf('%s <options=bold>%s</>  <info>%s</info> %s', $this->getCheckbox($task),
                array_get($task, 'attributes.summary'), $this->getId($task, $includedData), $this->getStatus($task)));
        });

        return 0;
    }

    /**
     * @param \Illuminate\Support\Collection $tasks
     *
     * @return \Illuminate\Support\Collection
     */
    private function getFilteredOrderedTasks(Collection $tasks)
    {
        return $tasks->sort(function ($a, $b) {
            if ($a['project'] === $b['project']) {
                if ($a['id'] === $b['id']) {
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
        return array_get($task, 'attributes.status') !== 'done' ? '▢' : '<fg=green>✔</>';
    }

    /**
     * returns task identifier
     *
     * @param array $task
     * @param array $includedData
     *
     * @return string
     */
    private function getId($task, $includedData)
    {
        $projectSlug = $projectId = array_get($task, 'relationships.project.data.id');

        foreach ($includedData as $data) {
            if (array_get($data, 'id') == $projectId && array_get($data, 'type') === 'projects') {
                $projectSlug = array_get($data, 'attributes.slug');
                break;
            }
        }

        return '♯' . $projectSlug . '-' . array_get($task, 'attributes.identifier');
    }

    /**
     * @param array $task
     *
     * @return string
     */
    private function getStatus($task)
    {
        return '<fg=blue>♯' . array_get($task, 'attributes.status') . '</>';
    }
}