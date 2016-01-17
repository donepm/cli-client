<?php

namespace DonePM\ConsoleClient\Commands\Tasks;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Tasks\IndexCommand;
use DonePM\ConsoleClient\Renderer\TaskRenderer;
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
        $tasksData = array_get($responseData, 'data', []);
        $includedData = array_get($responseData, 'included', []);

        $tasks = new Collection($tasksData);

        if ($tasks->isEmpty()) {
            $this->info('No tasks found');

            return 0;
        }

        $output = $this->output;
        $renderer = new TaskRenderer($output, $includedData);

        $this->getFilteredOrderedTasks($tasks)->each(function ($task) use ($renderer) {
            $renderer->setTask($task)->writeln();
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

            $aProjectId = array_get($a, 'relationships.project.data.id');
            $bProjectId = array_get($b, 'relationships.project.data.id');

            if ($aProjectId === $bProjectId) {
                if ($a['id'] === $b['id']) {
                    return 0;
                }

                return $a['id'] < $b['id'] ? -1 : 1;
            }

            return $aProjectId < $bProjectId ? -1 : 1;
        });
    }
}