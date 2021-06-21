<?php

namespace ToDo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ToDo\ToDoLoader;

class EditCommand extends Command
{
    protected static $defaultName = 'edit';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Edit an existing todo',
            '=====================',
            '',
        ]);
        $helper = $this->getHelper('question');
        $loader = new ToDoLoader();
        $filePath = getFilePath($helper, $input, $output, 'Choose a todo to edit:');
        $todo = $loader->load($filePath);
        $prevTitle = $todo->getTitle();
        $question = new Question("<info>Change title of your todo:</info><comment>[{$prevTitle}]</comment>", $prevTitle);
        $title = $helper->ask($input, $output, $question);
        $question = new Question('<info>Change text for todo (Terminate with Ctrl-D or Ctrl-Z):</info>', $todo->getContent());
        $question->setMultiline(true);
        $content = $helper->ask($input, $output, $question);
        $todo->setTitle($title);
        $todo->setContent($content);
        $serializer = new Serializer([new ObjectNormalizer()], [new YamlEncoder()]);
        $serializedTodo = $serializer->serialize(
            $todo,
            'yaml',
            [
                'yaml_inline'=>3
            ]
        );
        if (!$filePath) {
            fileNotFound($helper, $input, $output);
        }
        file_put_contents($filePath, $serializedTodo);
        $output->writeln("<info>Todo was edited</info>");
        return Command::SUCCESS;
    }
}
