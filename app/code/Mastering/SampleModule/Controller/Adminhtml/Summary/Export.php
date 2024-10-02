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
        $summaryData = $this->dailyCountryBlock->getDailySummaryData();
        $content = "Country,Cost (Euro),Revenue (Euro),Cost Ratio\n";

        foreach ($summaryData as $data) {
            $content .= sprintf(
                "%s,%s,%s,%s\n",
                $this->escapeCsv($data['country_name']),
                $this->formatNumber($data['cost_euro']),
                $this->formatNumber($data['revenue']),
                $this->formatNumber($data['cost_ratio'], true)
            );
        }

        return $content;
    }

    private function formatNumber($value, $isPercentage = false)
    {
        if (is_null($value)) {
            return 'N/A';
        }
        return $isPercentage ? number_format($value, 4, '.', '') : number_format($value, 2, '.', '');
    }

    private function escapeCsv($value)
    {
        if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
            return '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }
}