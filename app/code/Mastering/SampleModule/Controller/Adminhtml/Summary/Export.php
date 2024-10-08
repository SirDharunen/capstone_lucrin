<?php
namespace Mastering\SampleModule\Controller\Adminhtml\Summary;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Mastering\SampleModule\Block\Adminhtml\Summary\DailyCountry;

class Export extends Action
{
    protected $fileFactory;
    protected $directoryList;
    protected $dailyCountryBlock;

    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        DirectoryList $directoryList,
        DailyCountry $dailyCountryBlock
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->dailyCountryBlock = $dailyCountryBlock;
    }

    public function execute()
    {
        return $this->exportCsv();
    }

    private function exportCsv()
    {
        $fileName = 'daily_country_summary.csv';
        $content = $this->generateCsvContent();

        return $this->fileFactory->create(
            $fileName,
            $content,
            DirectoryList::VAR_DIR,
            'text/csv'
        );
    }

    private function generateCsvContent()
    {
        // Get the filter dates from the request
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');

        // Set the filter dates in the block
        $this->dailyCountryBlock->setFromDate($fromDate);
        $this->dailyCountryBlock->setToDate($toDate);

        $summaryData = $this->dailyCountryBlock->getDailySummaryData();
        $content = "Country,Cost (Euro),Revenue (Euro),Cost Ratio\n";

        foreach ($summaryData as $data) {
            $content .= sprintf(
                "%s,%s,%s,%s\n",
                $this->escapeCsv($data['country_name']),
                $this->escapeCsv($data['cost']),  // Use 'cost' instead of 'cost_euro'
                $this->escapeCsv($data['revenue']),
                $this->escapeCsv($data['cost_ratio'])
            );
        }

        return $content;
    }

    private function escapeCsv($value)
    {
        if (is_numeric($value)) {
            return $value;  // Don't quote numeric values
        }
        if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
            return '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }
}