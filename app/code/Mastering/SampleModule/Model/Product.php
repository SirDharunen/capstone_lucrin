<?php
namespace Mastering\SampleModule\Model;

use Magento\Framework\Model\AbstractModel;

class Product extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mastering\SampleModule\Model\ResourceModel\Product::class);
    }
}