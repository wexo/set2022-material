<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\Command;

use Set2022DynamicProductNames\Set2022DynamicProductNames;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Set2022DynamicProductNames\Service\ProductService;

class UpdateProductPrefixCommand extends Command
{
    /** @var string */
    public static $defaultName = 'set2022:update-product-prefix';

    /**
     * @param ProductService $productService
     */
    public function __construct(public ProductService $productService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Adds or removes prefixes for products')
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'Choose whether to add or remove prefix data'
            )
            ->addOption(
                'product-numbers',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Provide the product numbers / SKU (comma separated) of specific products to update prefix data for',
                null
            )
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force update all product prefixes', false);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // -p "SWDEMO10001,SWDEMO100013,SWDEMO10005.4"

        $productNumbers = $input->getOption('product-numbers')
            ? explode(',', $input->getOption('product-numbers'))
            : null;

        return $this->productService->updateProductPrefixData(
            $input->getArgument('action') ?? Set2022DynamicProductNames::ACTION_ADD,
            $productNumbers,
            $input->getOption('force')
        )
            ? self::SUCCESS
            : self::FAILURE;
    }
}
