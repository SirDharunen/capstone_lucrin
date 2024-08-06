<?php
namespace Mastering\SampleModule\Model\ResourceModel\item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mastering\SampleModule\Model\Item;
use Mastering\SampleModule\Model\ResourceModel\item as ItemResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct(): void
    {
        $this->_init(Item::class, ItemResource::class);
    }
}
