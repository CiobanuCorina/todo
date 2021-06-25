<?php

namespace ToDo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Uid\Uuid;
use ToDo\ToDo;

class AddCommand extends GeneralCommand
{
    protected static $defaultName = 'add';

    protected function configure(): void
    {
        $this->setDescription('Creates a new todo')
            ->setHelp('This command allows to create a new todo and store it in the storage folder');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Add a new todo',
            '==============',
            '',
        ]);
        $todo = new ToDo();
        $helper = $this->getHelper('question');
        $title = parent::ask($helper, $input, $output,
            "<info>Enter the title of your new todo:</info><comment>[title]</comment>",
            'title');
        $content = parent::ask($helper, $input, $output,
            '<info>Enter text for todo (Terminate with Ctrl-D or Ctrl-Z):</info>',
        null,
        true);
        $todo->setTitle($title);
        $todo->setContent($content);
        $todo->setCreatedAt(date('Y-m-d H:i:s'));
        $serializer = new Serializer([new ObjectNormalizer()], [new YamlEncoder()]);
        $serializedTodo = $serializer->serialize(
            $todo,
            'yaml',
            [
            'yaml_inline'=>3
        ]
        );
        $title .= Uuid::v4();
        $fileName = parent::ask($helper, $input, $output,
            '<info>Enter the name of the file:</info>',
            strtolower("{$title}.yaml"));
        if(!strrpos($fileName, '.yaml')){
            $fileName = $fileName.'.yaml';
        }
        file_put_contents(getcwd().'/storage/'.$fileName, $serializedTodo);
        $output->writeln("<info>Todo was saved</info>");
        return Command::SUCCESS;
    }
}
