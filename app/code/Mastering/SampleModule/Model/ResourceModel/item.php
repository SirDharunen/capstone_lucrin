<?php
namespace Mastering\SampleModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class item extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('mastering_sample_item', 'id');
    }
}
