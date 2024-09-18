<?php
namespace Mastering\SampleModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Refund extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('mastering_refund_table', 'id');
    }
}