<?php

namespace Mastering\SampleModule\Controller\Adminhtml\Livestream;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mastering\SampleModule\Model\ResourceModel\Revenue\CollectionFactory as RevenueCollectionFactory;
use Mastering\SampleModule\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Mastering\SampleModule\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Mastering\SampleModule\Model\ResourceModel\Refund\CollectionFactory as RefundCollectionFactory;

class Index extends Action
{
    protected $resultPageFactory;
    protected $revenueCollectionFactory;
    protected $orderCollectionFactory;
    protected $productCollectionFactory;
    protected $refundCollectionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RevenueCollectionFactory $revenueCollectionFactory,
        OrderCollectionFactory $orderCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        RefundCollectionFactory $refundCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->revenueCollectionFactory = $revenueCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->refundCollectionFactory = $refundCollectionFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Live Stream Integration Dashboard'));

        try {
            $params = $this->getRequest()->getParams();
            $fromDate = isset($params['from_date']) ? $this->validateDate($params['from_date']) : null;
            $toDate = isset($params['to_date']) ? $this->validateDate($params['to_date']) : null;

            $revenueCollection = $this->revenueCollectionFactory->create();
            $orderCollection = $this->orderCollectionFactory->create();
            $refundCollection = $this->refundCollectionFactory->create();
            $productCollection = $this->productCollectionFactory->create();

            $this->applyDateFilter($revenueCollection, $fromDate, $toDate, 'revenue_date');
            $this->applyDateFilter($orderCollection, $fromDate, $toDate, 'order_date');
            $this->applyDateFilter($refundCollection, $fromDate, $toDate, 'refund_date');
            $this->applyDateFilter($productCollection, $fromDate, $toDate, 'created_at'); 
            $revenueData = $revenueCollection->getColumnValues('revenue_amount') ?: [];
            $revenueDates = $revenueCollection->getColumnValues('revenue_date') ?: [];

            $orderData = $orderCollection->getColumnValues('order_num') ?: [];
            $orderDates = $orderCollection->getColumnValues('order_date') ?: [];

            $productCount = $productCollection->getSize();

            $refundData = $refundCollection->getColumnValues('refund_amount') ?: [];
            $refundDates = $refundCollection->getColumnValues('refund_date') ?: [];

            $totalRefundAmount = array_sum($refundData);
            $refundCount = count($refundData);

            $orderCount = $orderCollection->getSize();
            $refundRate = $orderCount > 0 ? ($refundCount / $orderCount) * 100 : 0;

            $block = $resultPage->getLayout()->getBlock('livestream_integration');
            if ($block) {
                $block->setRevenueData($revenueData);
                $block->setRevenueDates($revenueDates);
                $block->setOrderData($orderData);
                $block->setOrderDates($orderDates);
                $block->setProductCount($productCount);
                $block->setRefundData($refundData);
                $block->setRefundDates($refundDates);
                $block->setTotalRefundAmount($totalRefundAmount);
                $block->setRefundCount($refundCount);
                $block->setRefundRate($refundRate);
                $block->setFromDate($fromDate);
                $block->setToDate($toDate);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while fetching data: %1', $e->getMessage()));
        }

        return $resultPage;
    }

    private function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return ($d && $d->format('Y-m-d') === $date) ? $date : null;
    }

    private function applyDateFilter($collection, $fromDate, $toDate, $dateField)
    {
        if ($fromDate) {
            $collection->addFieldToFilter($dateField, ['gteq' => $fromDate]);
        }
        if ($toDate) {
            $collection->addFieldToFilter($dateField, ['lteq' => $toDate]);
        }
    }
}