<?php
namespace Mastering\SampleModule\Model;

use Magento\Framework\Model\AbstractModel;

class item extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Mastering\SampleModule\Model\ResourceModel\item::class);
    }
}
