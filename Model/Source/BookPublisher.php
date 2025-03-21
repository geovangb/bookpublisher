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

namespace GB\PublisherBook\Model\Source;

use GB\PublisherBook\Api\Data\PublisherInterface;
use GB\PublisherBook\Api\Data\PublisherInterfaceFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\ResourceConnection;

class BookPublisher extends AbstractSource
{
    /**
     * @var ResourceConnection
     */
    protected ResourceConnection $resource;

    /**
     * Options Attribute book_publisher in Catalog Products
     *
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName(PublisherInterface::MAIN_TABLE);

            $select = $connection->select()
                ->from($tableName, [PublisherInterface::ENTITY_ID, PublisherInterface::NAME])
                ->where('status = ?', PublisherInterface::STATUS_ACTIVE)
                ->order('name ASC');

            $results = $connection->fetchAll($select);

            $this->_options[] = [
                'value' => '',
                'label' => __('Select Book Publisher')
            ];
            foreach ($results as $publisher) {
                $this->_options[] = [
                    'value' => $publisher['entity_id'],
                    'label' => $publisher['name']
                ];
            }
        }

        return $this->_options;
    }
}
