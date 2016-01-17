<?php

namespace DonePM\ConsoleClient\Commands\Tasks;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Tasks\StoreCommand;
use DonePM\ConsoleClient\Services\ProjectIdSlugMapper;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CreateCommand
 *
 * Create a task
 *
 * @package DonePM\ConsoleClient\Commands\Tasks
 */
class CreateCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('task:create')
            ->setDescription('Creates a new task')
            ->addArgument('project', InputArgument::REQUIRED, 'Project id or slug to create the task in')
            ->addArgument('summary', InputArgument::REQUIRED, 'Task summary')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'Task description')
            ->addOption('worker', 'w', InputOption::VALUE_REQUIRED, 'Worker instructions')
            ->addOption('tester', 't', InputOption::VALUE_REQUIRED, 'Verifier or tester instructions')
            ->addOption('reporter', 'r', InputOption::VALUE_REQUIRED, 'Is the task reported by anyone?')
            ->addOption('master', 'm', InputOption::VALUE_REQUIRED, 'Master task')
        ;
    }

    /**
     * handles project creation
     */
    protected function handle()
    {
        $client = $this->getClient();

        $project = $this->resolveProjectId($this->argument('project'));

        $command = new StoreCommand();
        $command->setProject($project)
            ->setSummary($this->argument('summary'))
            ->setDescription($this->option('description'))
            ->setWorker($this->option('worker'))
            ->setVerifier($this->option('tester'))
            ->setReporter($this->option('reporter'))
            ->setMaster($this->option('master'));

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

        if ($response->getStatusCode() === 201) {
            $this->info('Task created');

            return 0;
        }

        $this->error('Something went wrong');

        return 1;
    }

    /**
     * resolves project id, from slug by using internal mapper
     *
     * @param int|string $param
     *
     * @return int|string
     */
    private function resolveProjectId($param)
    {
        if ( ! is_numeric($param)) {
            $config = $this->getApplication()->config();

            $slugMapper = new ProjectIdSlugMapper($config);

            return $slugMapper->getIdBySlug($param) ?: $param;
        }

        return $param;
    }

}