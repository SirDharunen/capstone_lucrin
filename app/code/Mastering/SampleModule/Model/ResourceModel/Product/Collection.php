<?php
namespace Mastering\SampleModule\Model\ResourceModel\Product;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Mastering\SampleModule\Model\Product::class,
            \Mastering\SampleModule\Model\ResourceModel\Product::class
        );
    }
}