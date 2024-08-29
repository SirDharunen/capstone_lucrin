<?php
namespace Mastering\SampleModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Revenue extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('mastering_revenue_table', 'id');
    }
}
