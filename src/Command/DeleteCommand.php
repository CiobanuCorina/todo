<?php

namespace ToDo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class DeleteCommand extends GeneralCommand
{
    protected static $defaultName = 'delete';


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
        $file = parent::getFilePath($helper, $input, $output, 'Which todo should be deleted:');
        if (!unlink($file)) {
            throw new FileNotFoundException();
        }
        $output->writeln("<info>Todo successfully deleted</info>");
        return Command::SUCCESS;
    }
}
