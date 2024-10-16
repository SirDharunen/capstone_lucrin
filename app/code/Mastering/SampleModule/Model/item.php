<?php
namespace Mastering\SampleModule\Model;

use Magento\Framework\Model\AbstractModel;

class Item extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Mastering\SampleModule\Model\ResourceModel\Item::class);
    }
}
