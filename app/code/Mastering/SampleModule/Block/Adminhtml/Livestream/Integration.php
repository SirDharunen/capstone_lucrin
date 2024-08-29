<?php
namespace Mastering\SampleModule\Block\Adminhtml\Livestream;

use Magento\Backend\Block\Template;

class Integration extends Template
{
    protected $revenueData;
    protected $revenueDates;
    protected $orderData;
    protected $orderDates;

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
        $chartData = [['Date', 'Tax']];
        if (is_array($this->orderData) && is_array($this->orderDates)) {
            foreach ($this->orderData as $key => $value) {
                $chartData[] = [$this->orderDates[$key], floatval($value)];
            }
        }
        return json_encode($chartData);
    }
}