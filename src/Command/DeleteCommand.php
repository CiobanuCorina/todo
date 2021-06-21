<?php

namespace ToDo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
{
    protected static $defaultName = 'delete';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption('stop-on-fail');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Delete an existing todo',
            '=======================',
            '',
        ]);
        $helper = $this->getHelper('question');
        $file = getFilePath($helper, $input, $output, 'Which todo should be deleted:');
        if (!unlink($file)) {
            fileNotFound($helper, $input, $output);
        }
        $output->writeln("<info>Todo successfully deleted</info>");
        return Command::SUCCESS;
    }
}
