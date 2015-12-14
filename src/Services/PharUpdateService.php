<?php

namespace DonePM\ConsoleClient\Services;

use DonePM\ConsoleClient\Services\Update\UpdateStatusResult;
use Humbug\SelfUpdate\Updater;

/**
 * Class PharUpdateService
 *
 * @package DonePM\ConsoleClient\Services
 */
class PharUpdateService
{
    /**
     * phar file
     *
     * @var string
     */
    private $urlToGithubPagesPharFile;

    /**
     * version file
     *
     * @var string
     */
    private $urlToGithubPagesVersionFile;

    /**
     * constructing PharUpdateService
     *
     * @param string $urlToGithubPagesPharFile
     * @param string $urlToGithubPagesVersionFile
     */
    public function __construct($urlToGithubPagesPharFile = '', $urlToGithubPagesVersionFile = '')
    {
        $this->urlToGithubPagesPharFile = $urlToGithubPagesPharFile;
        $this->urlToGithubPagesVersionFile = $urlToGithubPagesVersionFile;
    }

    /**
     * updating...
     *
     * @return UpdateStatusResult
     */
    public function update()
    {
        $updater = new Updater();
        $updater->getStrategy()->setPharUrl($this->urlToGithubPagesPharFile);
        $updater->getStrategy()->setVersionUrl($this->urlToGithubPagesVersionFile);
        try {
            $result = $updater->update();
            if ( ! $result) {
                // No update needed!
                return new UpdateStatusResult(UpdateStatusResult::NO_UPDATE_NEEDED);
            }

            $new = $updater->getNewVersion();
            $old = $updater->getOldVersion();

            return new UpdateStatusResult(UpdateStatusResult::OK, $new, $old);
        } catch (\Exception $e) {
            // Report an error!
            return new UpdateStatusResult(UpdateStatusResult::UNKNOWN_ERROR);
        }
    }

    /**
     * rollback
     *
     * @return \DonePM\ConsoleClient\Services\Update\UpdateStatusResult
     */
    public function rollback()
    {
        $updater = new Updater();
        try {
            $result = $updater->rollback();
            if ( ! $result) {
                // report failure!
                return new UpdateStatusResult(UpdateStatusResult::ROLLBACK_FAILED);
            }

            return new UpdateStatusResult(UpdateStatusResult::OK);
        } catch (\Exception $e) {
            // Report an error!
            return new UpdateStatusResult(UpdateStatusResult::UNKNOWN_ERROR);
    }
    }
}