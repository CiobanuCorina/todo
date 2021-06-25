<?php

namespace ToDo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ToDo\ToDoLoader;

class EditCommand extends GeneralCommand
{
    protected static $defaultName = 'edit';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $output->writeln([
            'Edit an existing todo',
            '=====================',
            '',
        ]);
        $loader = new ToDoLoader();
        $filePath = parent::getFilePath($helper, $input, $output, 'Choose a todo to edit:');
        $todo = $loader->load($filePath);
        $prevTitle = $todo->getTitle();
        $title = parent::ask($helper, $input, $output,
            "<info>Change title of your todo:</info><comment>[{$prevTitle}]</comment>",
            $prevTitle);
        $content = parent::ask($helper, $input, $output,
            '<info>Change text for todo (Terminate with Ctrl-D or Ctrl-Z):</info>',
            $todo->getContent(), true);
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
            throw new FileNotFoundException();
        }
        file_put_contents($filePath, $serializedTodo);
        $output->writeln("<info>Todo was edited</info>");
        return Command::SUCCESS;
    }
}

