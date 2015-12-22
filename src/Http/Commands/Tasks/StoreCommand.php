<?php

namespace DonePM\ConsoleClient\Http\Commands\Tasks;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\TokenizedCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class StoreCommand
 *
 * Stores task data
 *
 * @package DonePM\ConsoleClient\Http\Commands\Tasks
 */
class StoreCommand extends TokenizedCommand implements Command
{
    const PATH = '/api/v1/tasks';

    /**
     * project id
     *
     * @var integer
     */
    private $project;

    /**
     * task summary
     *
     * @var string
     */
    private $summary;

    /**
     * task description
     *
     * @var string|null
     */
    private $description;

    /**
     * worker
     *
     * @var string|null
     */
    private $worker;

    /**
     * verifier
     *
     * @var string|null
     */
    private $verifier;

    /**
     * reporter
     *
     * @var string|null
     */
    private $reporter;

    /**
     * master ticket reference
     *
     * @var integer|null
     */
    private $master;

    /**
     * @return RequestInterface
     */
    public function request()
    {
        $attributes = $this->resolveAttributes();

        $relationships = $this->resolveRelationships();

        return new Request('post', self::PATH, $this->header(), json_encode([
            'data' => [
                'type' => 'projects',
                'attributes' => $attributes,
                'relationships' => $relationships,
            ],
        ]));
    }

    /**
     * set project
     *
     * @param int $project
     *
     * @return StoreCommand
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * set summary
     *
     * @param string $summary
     *
     * @return StoreCommand
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * set description
     *
     * @param null|string $description
     *
     * @return StoreCommand
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * set worker
     *
     * @param null|string $worker
     *
     * @return StoreCommand
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * set verifier
     *
     * @param null|string $verifier
     *
     * @return StoreCommand
     */
    public function setVerifier($verifier)
    {
        $this->verifier = $verifier;

        return $this;
    }

    /**
     * set reporter
     *
     * @param null|string $reporter
     *
     * @return StoreCommand
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * set master
     *
     * @param int|null $master
     *
     * @return StoreCommand
     */
    public function setMaster($master)
    {
        $this->master = $master;

        return $this;
    }

    /**
     * resolves attributes
     *
     * @return array
     */
    private function resolveAttributes()
    {
        $attributes = [
            'summary' => $this->summary,
        ];

        if (null !== $this->description) {
            $attributes['description'] = $this->description;
        }
        if (null !== $this->worker) {
            $attributes['worker_text'] = $this->worker;
        }
        if (null !== $this->verifier) {
            $attributes['verifier_text'] = $this->verifier;
        }
        if (null !== $this->reporter) {
            $attributes['reporter'] = $this->reporter;
        }

        return $attributes;
    }

    /**
     * resolves relationships
     *
     * @return array
     */
    private function resolveRelationships()
    {
        $relationships = [
            'project' => [
                'data' => [
                    'type' => 'projects',
                    'id' => $this->project,
                ],
            ],
        ];

        if (null !== $this->master) {
            $relationships['master'] = [
                'data' => [
                    'type' => 'tasks',
                    'id' => $this->master,
                ],
            ];
        }

        return $relationships;
    }
}