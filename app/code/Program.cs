using static System.Net.Mime.MediaTypeNames;
using static System.Runtime.InteropServices.JavaScript.JSType;
using System.Diagnostics.Metrics;
using System.Diagnostics;
using System.Drawing;
using System.Reflection.Metadata;
using System.Runtime.Intrinsics.X86;
using System.Text.Unicode;

using System.Xml.Linq;

<? php

namespace YourVendor\YourModule\Console;

use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\DB\Adapter\AdapterInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportData extends Command
{
    protected $state;
    protected $fileDriver;
    protected $resource;

    public function __construct(
        State $state,
        File $fileDriver,
        \Magento\Framework\App\ResourceConnection $resource
    )
{
        $this->state = $state;
        $this->fileDriver = $fileDriver;
        $this->resource = $resource;
    parent::__construct();
}

protected function configure()
{
        $this->setName('yourvendor:importdata')
             ->setDescription('Import Data from Excel to Magento Table');
    parent::configure();
}

protected function execute(InputInterface $input, OutputInterface $output)
{
        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $filePath = '/path/to/your/excel/file.xlsx';
    if (!$this->fileDriver->isExists($filePath)) {
            $output->writeln("File not found: ". $filePath);
        return;
    }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $connection = $this->resource->getConnection(AdapterInterface::DEFAULT_CONNECTION);

        // Create Tables
        $this->createTables($connection);

        // Import Data
        $this->importData($connection, $rows);

        $output->writeln("Data imported successfully.");
}

protected function createTables($connection)
{
        $tables = [
            'user_table' => "
                CREATE TABLE IF NOT EXISTS `user_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    `age` INT(3) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8; "
            ,
            'country_table' => "
                CREATE TABLE IF NOT EXISTS `country_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `country_name` VARCHAR(255) NOT NULL,
                    `population` INT(11) NOT NULL,
                    `currency` VARCHAR(50) NOT NULL,
                    `iso_code` VARCHAR(10) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
            ,
            'product_table' => "
                CREATE TABLE IF NOT EXISTS `product_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `product_name` VARCHAR(255) NOT NULL,
                    `product_description` TEXT NOT NULL,
                    `price` DECIMAL(10, 2) NOT NULL,
                    `leather_type` VARCHAR(50) NOT NULL,
                    `colour` VARCHAR(50) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
            ,
            'advertisement_table' => "
                CREATE TABLE IF NOT EXISTS `advertisement_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `advert_name` VARCHAR(255) NOT NULL,
                    `cost` DECIMAL(10, 2) NOT NULL,
                    `country` VARCHAR(255) NOT NULL,
                    `done_by` VARCHAR(255) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
            ,
            'order_table' => "
                CREATE TABLE IF NOT EXISTS `order_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `order_date` DATE NOT NULL,
                    `shipping_method` VARCHAR(255) NOT NULL,
                    `shipping_cost` DECIMAL(10, 2) NOT NULL,
                    `shipping_address` VARCHAR(255) NOT NULL,
                    `tax` DECIMAL(10, 2) NOT NULL,
                    `discount` DECIMAL(10, 2) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
            ,
            'revenue_table' => "
                CREATE TABLE IF NOT EXISTS `revenue_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `revenue_amount` DECIMAL(10, 2) NOT NULL,
                    `revenue_date` DATE NOT NULL,
                    `exchange_rate` DECIMAL(10, 4) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
            ,
            'refund_table' => "
                CREATE TABLE IF NOT EXISTS `refund_table` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `refund_date` DATE NOT NULL,
                    `refund_amount` DECIMAL(10, 2) NOT NULL,
                    `refund_status` VARCHAR(255) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY(`id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8; "
        ];

    foreach ($tables as $tableName => $createQuery) {
            $connection->query($createQuery);
    }
}

protected function importData($connection, $rows)
{
    foreach ($rows as $row) {
            $userData = [
                'name' => $row[0],
                'email' => $row[1],
                'password' => $row[2],
                'age' => $row[3],
                'created_at' => $row[4]
            ];
            $connection->insert('user_table', $userData);

            // Add similar insertion code for other tables as per your data structure
            // Example for another table:
            // $productData = [...]; // Fill with product data from Excel
            // $connection->insert('product_table', $productData);
        }
    }
}
