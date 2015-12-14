<?php

namespace DonePM\ConsoleClient\Services\Update;

/**
 * Class UpdateStatusResult
 *
 * @package DonePM\ConsoleClient\Services\Update
 */
final class UpdateStatusResult
{
    const NO_UPDATE_NEEDED = 'no_update_needed';
    const UNKNOWN_ERROR = 'unknown_error';
    const ROLLBACK_FAILED = 'rollback_failed';
    const OK = 'ok';

    /**
     * status
     *
     * @var string
     */
    private $status;

    /**
     * new version
     *
     * @var string
     */
    private $newVersion;

    /**
     * old version
     *
     * @var string
     */
    private $oldVersion;

    /**
     * UpdateStatusResult constructor.
     *
     * @param string $status
     * @param null $newVersion
     * @param null $oldVersion
     */
    public function __construct($status, $newVersion = null, $oldVersion = null)
    {
        $this->status = $status;
        $this->newVersion = $newVersion;
        $this->oldVersion = $oldVersion;
    }

    /**
     * is okay
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->status === self::OK || $this->status === self::NO_UPDATE_NEEDED;
    }

    /**
     * is failed
     *
     * @return bool
     */
    public function failed()
    {
        return ! $this->isOk();
    }

    /**
     * returns NewVersion
     *
     * @return string
     */
    public function getNewVersion()
    {
        return $this->newVersion;
    }

    /**
     * returns OldVersion
     *
     * @return string
     */
    public function getOldVersion()
    {
        return $this->oldVersion;
    }

    /**
     * is status
     *
     * @param string $status
     *
     * @return bool
     */
    public function is($status)
    {
        return $this->status === $status;
    }
}