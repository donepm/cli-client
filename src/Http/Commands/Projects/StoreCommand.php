<?php

namespace DonePM\ConsoleClient\Http\Commands\Projects;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\TokenizedCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class StoreCommand
 *
 * Stores project data
 *
 * @package DonePM\ConsoleClient\Http\Commands\Projects
 */
class StoreCommand extends TokenizedCommand implements Command
{
    const PATH = '/api/v1/projects';

    /**
     * project name
     *
     * @var string
     */
    private $name;

    /**
     * project slug
     *
     * @var string|null
     */
    private $slug;

    /**
     * is project archived
     *
     * @var bool
     */
    private $archived = false;

    /**
     * is project public
     *
     * @var bool
     */
    private $public = false;

    /**
     * @return RequestInterface
     */
    public function request()
    {
        $attributes = $this->resolveAttributes();

        return new Request('post', self::PATH, $this->header(), json_encode([
            'data' => [
                'type' => 'projects',
                'attributes' => $attributes,
            ]
        ]));
    }

    /**
     * set name
     *
     * @param string $name
     *
     * @return StoreCommand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * set slug
     *
     * @param null|string $slug
     *
     * @return StoreCommand
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * set archived
     *
     * @param boolean $archived
     *
     * @return StoreCommand
     */
    public function setArchived($archived)
    {
        $this->archived = $archived === true;

        return $this;
    }

    /**
     * set public
     *
     * @param boolean $public
     *
     * @return StoreCommand
     */
    public function setPublic($public)
    {
        $this->public = $public === true;

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
            'name' => $this->name,
            'status' => $this->archived ? 'archived' : 'active',
            'public' => $this->public,
        ];

        if (null !== $this->slug) {
            $attributes['slug'] = $this->slug;
        }

        return $attributes;
    }
}