<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */
declare(strict_types=1);

namespace GB\PublisherBook\Model\ResourceModel\Publisher;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use GB\PublisherBook\Model\Publisher;
use GB\PublisherBook\Model\ResourceModel\Publisher as PublisherResource;

class Collection extends AbstractCollection
{
    /**
     * Collection
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Publisher::class, PublisherResource::class);
    }
}
