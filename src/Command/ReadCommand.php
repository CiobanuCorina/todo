<?php


namespace Todo\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ToDo\ToDo;
use ToDo\ToDoLoader;

class ReadCommand extends Command
{
    protected static $defaultName = 'read';

    protected function configure()
    {
        $this->addOption('stop-on-fail');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $todo = $this->chooseTodo($helper, $input, $output);
        if(!$todo){
            fileNotFound($helper, $input, $output);
        }
        $output->writeln("<info>Title: </info>".$todo->getTitle());
        $output->writeln("<info>Content: </info>".PHP_EOL.$todo->getContent());
        return Command::SUCCESS;
    }

    protected function chooseTodo($helper, InputInterface $input, OutputInterface $output): ToDo
    {
        $loader = new ToDoLoader();
        $filePath = getFilePath($helper, $input, $output, 'Which todo you want to view:');

        return $loader->load($filePath);
    }
}