<?php

namespace DonePM\ConsoleClient\Services;

use DonePM\ConsoleClient\Repositories\Config;

/**
 * Class ProjectIdSlugMapper
 *
 * @package DonePM\ConsoleClient\Services
 */
class ProjectIdSlugMapper
{
    /**
     * config repository
     *
     * @var \DonePM\ConsoleClient\Repositories\Config
     */
    private $config;

    /**
     * ProjectIdSlugMapper constructor.
     *
     * @param \DonePM\ConsoleClient\Repositories\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        if ( ! $this->config->has('mappings')) {
            $this->clearMappings();
        }
    }

    /**
     * adds a mapping
     *
     * @param string $id
     * @param string $slug
     *
     * @return $this
     */
    public function addMapping($id, $slug)
    {
        $mappings = $this->config->get('mappings', []);
        $mappings[$slug] = $id;

        $this->config->set('mappings', $mappings);

        return $this;
    }

    /**
     * clears all known mappings
     *
     * @return $this
     */
    public function clearMappings()
    {
        $this->config->set('mappings', []);

        return $this;
    }

    /**
     * returns config
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * returns id for given slug
     *
     * @param string $slug
     *
     * @return string|null
     */
    public function getIdBySlug($slug) {
        $mappings = $this->config->get('mappings', []);

        return array_get($mappings, $slug);
    }
}