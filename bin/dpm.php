<?php
/**
 * donepm-cli-client
 *
 * @author rok
 * @since 14.12.15
 */

require __DIR__ . '/../vendor/autoload.php';

use DonePM\ConsoleClient\Commands\Projects\ListCommand;
use DonePM\ConsoleClient\Application;
use DonePM\ConsoleClient\Commands\RollbackCommand;
use DonePM\ConsoleClient\Commands\SelfUpdateCommand;

$application = new Application();
$application->add(new ListCommand());

$application->add(new SelfUpdateCommand());
$application->add(new RollbackCommand());
$application->run();
