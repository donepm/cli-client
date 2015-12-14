<?php
/**
 * donepm-cli-client
 *
 * @author rok
 * @since 14.12.15
 */

require __DIR__.'/../vendor/autoload.php';

use DonePM\ConsoleClient\Commands\Projects\ListCommand;
use DonePM\ConsoleClient\Application;

$application = new Application();
$application->add(new ListCommand());
$application->run();