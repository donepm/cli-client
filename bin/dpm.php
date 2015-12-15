<?php
/**
 * donepm-cli-client
 *
 * @author rok
 * @since 14.12.15
 */

require __DIR__ . '/../vendor/autoload.php';

use DonePM\ConsoleClient\Commands\InitCommand;
use DonePM\ConsoleClient\Commands;
use DonePM\ConsoleClient\Application;
use DonePM\ConsoleClient\Commands\RollbackCommand;
use DonePM\ConsoleClient\Commands\SelfUpdateCommand;
use DonePM\ConsoleClient\Commands\TokenCommand;

$application = new Application();
$application->add(new InitCommand());
$application->add(new TokenCommand());
$application->add(new Commands\Projects\ListCommand());
$application->add(new Commands\Tasks\ListCommand());

$application->add(new SelfUpdateCommand());
$application->add(new RollbackCommand());
$application->run();
