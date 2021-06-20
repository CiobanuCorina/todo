#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \ToDo\Command\AddCommand());
$application->add(new \ToDo\Command\EditCommand());
$application->add(new \ToDo\Command\DeleteCommand());
$application->add(new \ToDo\Command\ReadCommand());

$application->run();
