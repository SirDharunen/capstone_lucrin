<?php
namespace Mastering\SampleModule\Model\ResourceModel\Refund;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Mastering\SampleModule\Model\Refund::class,
            \Mastering\SampleModule\Model\ResourceModel\Refund::class
        );
    }
}