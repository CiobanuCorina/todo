<?php

namespace ToDo;

use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ToDoLoader
{
    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()], [new YamlEncoder()]);
    }

    public function load(string $file): ToDo
    {
        if (!file_exists($file)) {
            throw new LogicException("File '$file' does not exist.");
        }

        $content = file_get_contents($file);
        return $this->serializer->deserialize($content, ToDo::class, 'yaml');
    }
}
