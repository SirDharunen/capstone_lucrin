<?php
namespace Mastering\SampleModule\Controller\Adminhtml\Livestream;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mastering\SampleModule\Model\ResourceModel\Revenue\CollectionFactory;

class Index extends Action
{
    protected $resultPageFactory;
    protected $revenueCollectionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CollectionFactory $revenueCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->revenueCollectionFactory = $revenueCollectionFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Live Stream Integration'));

        try {
            $revenueCollection = $this->revenueCollectionFactory->create();
            $revenueData = $revenueCollection->getColumnValues('revenue_amount') ?: [];
            $revenueDates = $revenueCollection->getColumnValues('revenue_date') ?: [];

            $orderCollection = $this->revenueCollectionFactory->create()->getCollection('mastering_order_table');
            $orderData = $orderCollection->getColumnValues('id') ?: [];
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