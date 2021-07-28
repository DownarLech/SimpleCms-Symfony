<?php declare(strict_types=1);


namespace App\Command;

use App\Component\Import\Business\ImportBusinessFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductsCommand extends Command
{
    /**
     * @var ImportBusinessFacadeInterface
     */
    private ImportBusinessFacadeInterface $importBusinessFacade;

    protected static $defaultName = 'SimpleCms:product:import';


    /**
     * ImportProductsCommand constructor.
     * @param ImportBusinessFacadeInterface $importBusinessFacade
     */
    public function __construct(ImportBusinessFacadeInterface $importBusinessFacade)
    {
        $this->importBusinessFacade = $importBusinessFacade;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setDescription('Product import');
        $this->addArgument('file', InputArgument::REQUIRED, 'Write the file path to upload');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');

        if (!is_dir($path)) {
            throw new \RuntimeException(
                sprintf('Folder: %s not exist', $path)
            );
        }

        $this->importBusinessFacade->saveCategoriesFromCsvFile($path);
        $this->importBusinessFacade->saveProductsFromCsvFile($path);

        return Command::SUCCESS;
    }

}