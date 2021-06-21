<?php

namespace Todo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Uid\Uuid;
use ToDo\ToDo;

class AddCommand extends Command
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
        $question = new Question("<info>Enter the title of your new todo:</info><comment>[title]</comment>", 'title');
        $title = $helper->ask($input, $output, $question);
        $question = new Question('<info>Enter text for todo (Terminate with Ctrl-D or Ctrl-Z):</info>');
        $question->setMultiline(true);
        $content = $helper->ask($input, $output, $question);
        $todo->setTitle($title);
        $todo->setContent($content);
        $todo->setCreatedAt(date('Y-m-d H:i:s'));
        $serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()], [new YamlEncoder()]);
        $serializedTodo = $serializer->serialize(
            $todo,
            'yaml',
            [
            'yaml_inline'=>3
        ]
        );
        $title = $title.Uuid::v4();
        $question = new Question('Enter the name of the file:', strtolower("{$title}.yaml"));
        $fileName = $helper->ask($input, $output, $question);
        if(!strrpos($fileName, '.yaml')){
            $fileName = $fileName.'.yaml';
        }
        file_put_contents(getcwd().'/storage/'.$fileName, $serializedTodo);
        $output->writeln("<info>Todo was saved</info>");
        return Command::SUCCESS;
    }
}
