#!/usr/bin/env php
<?php
/**
 * donepm-cli-client
 *
 * @author rok
 * @since 14.12.15
 */

require __DIR__ . '/../vendor/autoload.php';

use DonePM\ConsoleClient\Commands;
use DonePM\ConsoleClient\Application;

(new Application())->run();
