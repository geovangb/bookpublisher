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

namespace GB\PublisherBook\Model;

use GB\PublisherBook\Api\Data\PublisherSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class PublisherSearchResults extends SearchResults implements PublisherSearchResultsInterface
{
}
