<?php
namespace Mastering\SampleModule\Model\ResourceModel\Revenue;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Mastering\SampleModule\Model\Revenue::class,
            \Mastering\SampleModule\Model\ResourceModel\Revenue::class
        );
    }
}
