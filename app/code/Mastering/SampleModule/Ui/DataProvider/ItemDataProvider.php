<?php

namespace Mastering\SampleModule\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Mastering\SampleModule\Model\ResourceModel\Item\CollectionFactory;

class ItemDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
