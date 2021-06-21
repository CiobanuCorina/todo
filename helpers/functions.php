<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Finder\Finder;

function getFilePath($helper, InputInterface $input, OutputInterface $output, string $message): string
{
    $finder = new Finder();
    $files = $finder->in(getcwd().'/storage/')
        ->name("*.yaml")
        ->files();

    $choices = [];
    foreach ($files->getIterator() as $file) {
        $choices[$file->getFilenameWithoutExtension()] = $file->getRealPath();
    }

    $response = $helper->ask($input, $output, new ChoiceQuestion($message, array_keys($choices)));
    return $choices[$response];
}

function fileNotFound($helper, InputInterface $input, OutputInterface $output): int
{
    $helper->ask($input, $output, new ConfirmationQuestion("This file does not exist [Enter]"));
    return Command::FAILURE;
}