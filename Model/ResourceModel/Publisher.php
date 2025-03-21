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

namespace GB\PublisherBook\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Publisher extends AbstractDb
{
    /**
     * Resource Model Book Publisher
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gb_publisher_book', 'entity_id');
    }
}
