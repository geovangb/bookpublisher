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

namespace GB\PublisherBook\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PublisherSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Publisher list.
     * @return PublisherInterface[]
     */
    public function getItems(): array;

    /**
     * Set entity_id list.
     * @param PublisherInterface[] $items
     * @return $this
     */
    public function setItems(array $items): static;
}
