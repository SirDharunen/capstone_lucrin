<?php
namespace Mastering\SampleModule\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Mastering\SampleModule\Model\Order::class,
            \Mastering\SampleModule\Model\ResourceModel\Order::class
        );
    }
}
