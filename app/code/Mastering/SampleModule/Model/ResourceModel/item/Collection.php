<?php
namespace Mastering\SampleModule\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mastering\SampleModule\Model\Item;
use Mastering\SampleModule\Model\ResourceModel\Item as ItemResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Item::class,
            ItemResource::class
        );
    }
}