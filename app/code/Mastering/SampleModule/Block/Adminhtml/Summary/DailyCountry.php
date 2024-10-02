<?php
namespace Mastering\SampleModule\Block\Adminhtml\Summary;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\ResourceConnection;

class DailyCountry extends Template
{
    protected $resourceConnection;
    private $fromDate;
    private $toDate;

    public function __construct(
        Context $context,
        ResourceConnection $resourceConnection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resourceConnection = $resourceConnection;
    }

    public function setFromDate($date)
    {
        $this->fromDate = $date;
    }

    public function setToDate($date)
    {
        $this->toDate = $date;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function getDailySummaryData()
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(
                ['c' => $this->resourceConnection->getTableName('mastering_country_table')],
                ['country_name']
            )
            ->joinLeft(
                ['r' => $this->resourceConnection->getTableName('mastering_revenue_table')],
                'c.id = r.country_id',
                ['revenue' => 'SUM(r.revenue_amount)']
            );

        if ($this->fromDate) {
            $select->where('r.revenue_date >= ?', $this->fromDate);
        }
        if ($this->toDate) {
            $select->where('r.revenue_date <= ?', $this->toDate);
        }

        $select->group('c.country_name')
            ->order('c.country_name ASC');

        $result = $connection->fetchAll($select);

        $totalRevenue = 0;
        $totalCost = 0;
        $combinedResult = [];

        foreach ($result as $row) {
            $countryName = $row['country_name'];
            $revenue = (float)($row['revenue'] ?? 0);
            $revenueEuro = $this->convertToEuro($revenue);

            // Skip this iteration if revenue is zero
            if ($revenueEuro == 0) {
                continue;
            }

            $costPercentage = $this->getRandomCostPercentage();
            $costEuro = $revenueEuro * $costPercentage;

            $costRatio = $revenueEuro > 0 ? ($costEuro / $revenueEuro) * 100 : 0;

            $combinedResult[$countryName] = [
                'country_name' => $countryName,
                'revenue' => $revenueEuro,
                'cost' => $costEuro,
                'cost_euro' => $costEuro,
                'cost_ratio' => $costRatio
            ];

            $totalRevenue += $revenueEuro;
            $totalCost += $costEuro;
        }

        $result = array_values($combinedResult);

        // Add Grand Total row
        $grandTotalCostRatio = $totalRevenue > 0 ? ($totalCost / $totalRevenue) * 100 : 0;
        $result[] = [
            'country_name' => 'Grand Total',
            'revenue' => $totalRevenue,
            'cost' => $totalCost,
            'cost_euro' => $totalCost,
            'cost_ratio' => $grandTotalCostRatio
        ];

        return $result;
    }

    private function getRandomCostPercentage()
    {
        // Generate a random cost percentage between 50% and 90%
        return mt_rand(5000, 9000) / 10000; // This gives more precision
    }

    private function convertToEuro($amount)
    {
        // Assuming a fixed exchange rate for simplicity. In a real-world scenario,
        // you'd want to use a service or API to get the current exchange rate.
        $exchangeRate = 0.85; // 1 USD = 0.85 EUR (example rate)
        return $amount * $exchangeRate;
    }
}