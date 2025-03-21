<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */

namespace GB\PublisherBook\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportBookPublisher extends Command
{
    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('import_book_publisher');
        $this->setDescription('Import Book Publisher');
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        $this->setHelp("This command allows you to import book publisher...");
        parent::configure();
    }

    private const NAME_ARGUMENT = "name";
    private const NAME_OPTION = "option";

    /**
     * @inheritdoc
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        $output->writeln("Hello " . $name);
        return Command::SUCCESS;
    }
}
