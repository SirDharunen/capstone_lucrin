<?php
namespace Mastering\SampleModule\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.7', '<')) {
            $this->createCountryTable($setup);
            $this->createProductTable($setup);
            $this->createOrderTable($setup);
            $this->createRevenueTable($setup);
            $this->createRefundTable($setup);
        }

        $setup->endSetup();
    }

    private function createCountryTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable('mastering_country_table')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'country_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Country Name'
        )->addColumn(
            'population',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Population'
        )->addColumn(
            'currency',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Currency'
        )->addColumn(
            'iso_code',
            Table::TYPE_TEXT,
            10,
            ['nullable' => false],
            'ISO Code'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->setComment('Country Table');

        $setup->getConnection()->createTable($table);
    }

    private function createProductTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('mastering_product_table');
        $connection = $setup->getConnection();

        if (!$connection->isTableExists($tableName)) {
            $table = $connection->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'ID'
                )
                ->addColumn(
                    'product_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Name'
                )
                ->addColumn(
                    'product_description',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => true],
                    'Product Description'
                )
                ->addColumn(
                    'price',
                    Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Price'
                )
                ->addColumn(
                    'cost',
                    Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Cost'
                )
                ->addColumn(
                    'leather_type',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => true],
                    'Leather Type'
                )
                ->addColumn(
                    'colour',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => true],
                    'Colour'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->addColumn(
                    'country_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Country ID'
                )
                ->addForeignKey(
                    $setup->getFkName('mastering_product_table', 'country_id', 'mastering_country_table', 'id'),
                    'country_id',
                    $setup->getTable('mastering_country_table'),
                    'id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Product Table');

            $connection->createTable($table);
        } else {
            if (!$connection->tableColumnExists($tableName, 'cost')) {
                $connection->addColumn(
                    $tableName,
                    'cost',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'length' => '12,4',
                        'nullable' => false,
                        'comment' => 'Cost'
                    ]
                );
            }
        }
    }

    private function createOrderTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable('mastering_order_table')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'order_date',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Order Date'
        )->addColumn(
            'shipping_method',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false],
            'Shipping Method'
        )->addColumn(
            'shipping_cost',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false],
            'Shipping Cost'
        )->addColumn(
            'shipping_address',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Shipping Address'
        )->addColumn(
            'order_num',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Order Number'
        )->addColumn(
            'discount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => true],
            'Discount'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->setComment('Order Table');

        $setup->getConnection()->createTable($table);
    }

    private function createRevenueTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable('mastering_revenue_table')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'revenue_amount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false],
            'Revenue Amount'
        )->addColumn(
            'revenue_date',
            Table::TYPE_DATE,
            null,
            ['nullable' => false],
            'Revenue Date'
        )->addColumn(
            'exchange_rate',
            Table::TYPE_DECIMAL,
            '12,6',
            ['nullable' => false],
            'Exchange Rate'
        )->addColumn(
            'country_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Country ID'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->addForeignKey(
            $setup->getFkName('mastering_revenue_table', 'country_id', 'mastering_country_table', 'id'),
            'country_id',
            $setup->getTable('mastering_country_table'),
            'id',
            Table::ACTION_CASCADE
        )->setComment('Revenue Table');

        $setup->getConnection()->createTable($table);
    }

    private function createRefundTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable('mastering_refund_table')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'refund_date',
            Table::TYPE_DATE,
            null,
            ['nullable' => false],
            'Refund Date'
        )->addColumn(
            'refund_amount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false],
            'Refund Amount'
        )->addColumn(
            'refund_status',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Refund Status'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->setComment('Refund Table');

        $setup->getConnection()->createTable($table);
    }
}