<?php
namespace Mastering\SampleModule\Model;

use Magento\Framework\Model\AbstractModel;

class Order extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mastering\SampleModule\Model\ResourceModel\Order::class);
    }
}
