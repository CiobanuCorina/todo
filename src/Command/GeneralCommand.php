<?php


namespace ToDo\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\Finder;

class GeneralCommand extends Command
{
    public function getFilePath($helper, InputInterface $input, OutputInterface $output, string $message): string
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

    public function ask($helper,
                        InputInterface $input,
                        OutputInterface $output,
                        string $msg,
                        ?string $default = null,
                        ?bool $multiline = false){
        $question = new Question($msg, $default);
        $question->setMultiline($multiline);
        return $helper->ask($input, $output, $question);
    }
}