<?php
namespace Mastering\SampleModule\Block\Adminhtml\Livestream;

use Magento\Backend\Block\Template;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class Integration extends Template
{
    protected $revenueData;
    protected $revenueDates;
    protected $orderData;
    protected $orderDates;
    protected $productCount;

    protected $refundData;
    protected $refundDates;
    protected $totalRefundAmount;
    protected $refundCount;
    protected $refundRate;

    protected $resourceConnection;
    protected $logger;

    protected $fromDate;
    protected $toDate;

    public function __construct(
        Template\Context $context,
        ResourceConnection $resourceConnection,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
    }

    public function setRevenueData($data)
    {
        $this->revenueData = $data;
    }

    public function setRevenueDates($dates)
    {
        $this->revenueDates = $dates;
    }

    public function setOrderData($data)
    {
        $this->orderData = $data;
    }

    public function setOrderDates($dates)
    {
        $this->orderDates = $dates;
    }

    public function setProductCount($count)
    {
        $this->productCount = $count;
    }

    public function getProductCount()
    {
        return $this->productCount;
    }

    public function getChartData()
    {
        $chartData = [['Date', 'Revenue']];
        if (is_array($this->revenueData) && is_array($this->revenueDates)) {
            foreach ($this->revenueData as $key => $value) {
                $chartData[] = [$this->revenueDates[$key], floatval($value)];
            }
        }
        return json_encode($chartData);
    }

    public function getOrderChartData()
    {
        $chartData = [['Date', 'Order Number']];
        if (is_array($this->orderData) && is_array($this->orderDates)) {
            foreach ($this->orderData as $key => $value) {
                $chartData[] = [$this->orderDates[$key], floatval($value)];
            }
        }
        return json_encode($chartData);
    }

    public function setRefundData($data)
    {
        $this->refundData = $data;
    }

    public function setRefundDates($dates)
    {
        $this->refundDates = $dates;
    }

    public function setTotalRefundAmount($amount)
    {
        $this->totalRefundAmount = $amount;
    }

    public function setRefundCount($count)
    {
        $this->refundCount = $count;
    }

    public function setRefundRate($rate)
    {
        $this->refundRate = $rate;
    }

    public function getTotalRefundAmount()
    {
        return $this->totalRefundAmount;
    }

    public function getRefundCount()
    {
        return $this->refundCount;
    }

    public function getRefundRate()
    {
        return $this->refundRate;
    }

    public function getRefundChartData()
    {
        $chartData = [['Date', 'Refund Amount']];
        if (is_array($this->refundData) && is_array($this->refundDates)) {
            foreach ($this->refundData as $key => $value) {
                $chartData[] = [$this->refundDates[$key], floatval($value)];
            }
        }
        return json_encode($chartData);
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

    public function getRevenueByCountryData()
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(['r' => 'mastering_revenue_table'], ['revenue_amount' => 'SUM(revenue_amount)', 'country_id'])
            ->join(['c' => 'mastering_country_table'], 'r.country_id = c.id', ['country_name'])
            ->group('r.country_id')
            ->order('revenue_amount DESC');

        if ($this->fromDate) {
            $select->where('r.revenue_date >= ?', $this->fromDate);
        }
        if ($this->toDate) {
            $select->where('r.revenue_date <= ?', $this->toDate);
        }

        $result = $connection->fetchAll($select);
        
        // Create an associative array to store unique country data
        $uniqueCountries = [];
        foreach ($result as $row) {
            $countryName = $row['country_name'];
            $revenueAmount = $row['revenue_amount'];
            if (!isset($uniqueCountries[$countryName])) {
                $uniqueCountries[$countryName] = $revenueAmount;
            } else {
                $uniqueCountries[$countryName] += $revenueAmount;
            }
        }

        // Sort the unique countries by revenue amount
        arsort($uniqueCountries);

        // Prepare the final result array
        $finalResult = [];
        foreach ($uniqueCountries as $countryName => $revenueAmount) {
            $finalResult[] = [
                'country_name' => $countryName,
                'revenue_amount' => $revenueAmount
            ];
        }

        // Limit to top 10 countries
        return json_encode(array_slice($finalResult, 0, 10));
    }

    public function getTotalRevenue()
    {
        return array_sum($this->revenueData);
    }

    public function getTotalOrders()
    {
        return count($this->orderData);
    }
}