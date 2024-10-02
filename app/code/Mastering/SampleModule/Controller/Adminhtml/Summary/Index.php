<?php
namespace Mastering\SampleModule\Controller\Adminhtml\Summary;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Daily Cost & Revenue by Country Summary'));

        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');

        // Check if it's a reset action
        if ($this->getRequest()->getParam('reset')) {
            $fromDate = null;
            $toDate = null;
        }

        // Handle file upload
        if ($this->getRequest()->isPost() && isset($_FILES['import_file'])) {
            $this->handleFileUpload();
        }

        $block = $resultPage->getLayout()->getBlock('daily_country_summary');
        if ($block) {
            $block->setFromDate($fromDate);
            $block->setToDate($toDate);
        }

        return $resultPage;
    }

    private function handleFileUpload()
    {
        try {
            $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'import_file']);
            $uploader->setAllowedExtensions(['csv', 'xls', 'xlsx']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $path = $this->_objectManager->get('Magento\Framework\App\Filesystem\DirectoryList')->getPath('var') . '/import/';
            $result = $uploader->save($path);

            if ($result['file']) {
                $this->importData($result['path'] . $result['file']);
                $this->messageManager->addSuccessMessage(__('File uploaded and data imported successfully.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error uploading file: %1', $e->getMessage()));
        }
    }

    private function importData($filePath)
    {
        // Implement the logic to read the file and import data
        // This will depend on whether it's a CSV or Excel file
        // You may want to use a library like PhpSpreadsheet for Excel files
        // For simplicity, let's assume it's a CSV file for now

        $handle = fopen($filePath, 'r');
        if ($handle !== false) {
            // Skip the header row
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                // Assuming the CSV structure is: Country, Cost, Revenue
                $countryName = $data[0];
                $cost = $data[1];
                $revenue = $data[2];

                // Update or insert the data into your database
                // You'll need to implement this logic based on your database structure
            }

            fclose($handle);
        }
    }
}