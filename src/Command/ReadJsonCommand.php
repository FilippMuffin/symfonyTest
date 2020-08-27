<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ReadJsonCommand extends Command
{
    protected static $defaultName = 'app:read-json';

    protected $serializer;

    const CATEGORIES_FILE = '!categories.json!';

    const PRODUCTS_FILE = '!products.json!';

    public function __construct(string $name = null, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Command read json and save data in db')
            ->addArgument('filePath', InputArgument::REQUIRED, 'file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');

        $content = file_get_contents($filePath);
        $encodeData = utf8_encode($content);

        if (preg_match (self::CATEGORIES_FILE, $filePath)) {
            $entities = $this->serializer->deserialize($encodeData, Category::class, "json");
        } elseif (preg_match (self::PRODUCTS_FILE, $filePath)) {
            $entities = $this->serializer->deserialize($encodeData, Product::class, "json");
        }


        $io->success('Read success!');

        return Command::SUCCESS;
    }
}
