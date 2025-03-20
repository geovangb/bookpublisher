<?php

namespace GB\PublisherBook\Model\ResourceModel\Publisher;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use GB\PublisherBook\Model\Publisher;
use GB\PublisherBook\Model\ResourceModel\Publisher as PublisherResource;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Publisher::class, PublisherResource::class);
    }
}
