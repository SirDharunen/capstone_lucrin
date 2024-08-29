<?php

namespace Mastering\SampleModule\Controller\Adminhtml\Livestream;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mastering\SampleModule\Model\ResourceModel\Revenue\CollectionFactory as RevenueCollectionFactory;
use Mastering\SampleModule\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Index extends Action
{
    protected $resultPageFactory;
    protected $revenueCollectionFactory;
    protected $orderCollectionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RevenueCollectionFactory $revenueCollectionFactory,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->revenueCollectionFactory = $revenueCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Live Stream Integration'));

        try {
            $revenueCollection = $this->revenueCollectionFactory->create();
            $revenueData = $revenueCollection->getColumnValues('revenue_amount') ?: [];
            $revenueDates = $revenueCollection->getColumnValues('revenue_date') ?: [];

            $orderCollection = $this->orderCollectionFactory->create();
            $orderData = $orderCollection->getColumnValues('tax') ?: [];
            $orderDates = $orderCollection->getColumnValues('order_date') ?: [];

            $block = $resultPage->getLayout()->getBlock('livestream_integration');
            if ($block) {
                $block->setRevenueData($revenueData);
                $block->setRevenueDates($revenueDates);
                $block->setOrderData($orderData);
                $block->setOrderDates($orderDates);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while fetching data: %1', $e->getMessage()));
        }

        return $resultPage;
    }
}