<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Command;

use Shopware\Core\Framework\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wexo\ProductLabels\Service\ProductLabelsService;

class SetProductLabelsCommand extends Command
{
    /** @var string */
    public static $defaultName = 'set2022:update-custom-labels';
}
