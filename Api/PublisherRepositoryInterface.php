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

namespace GB\PublisherBook\Api;

use GB\PublisherBook\Api\Data\PublisherInterface;
use GB\PublisherBook\Api\Data\PublisherSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface PublisherRepositoryInterface
{

    /**
     * Set Publisher Book
     *
     * @param PublisherInterface $publisher
     * @return mixed
     */
    public function save(
        PublisherInterface $publisher
    ): mixed;

    /**
     * Get By Id
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $criteria
     * @return PublisherSearchResultsInterface
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ): PublisherSearchResultsInterface;

    /**
     * Delete Publisher
     *
     * @param PublisherInterface $publisher
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        PublisherInterface $publisher
    ): bool;

    /**
     * Delete Publisher by ID
     *
     * @param string $publisherId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($publisherId): bool;
}
