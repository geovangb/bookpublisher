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

namespace GB\PublisherBook\Model;

use GB\PublisherBook\Api\Data\PublisherInterface;
use GB\PublisherBook\Api\Data\PublisherInterfaceFactory;
use GB\PublisherBook\Api\Data\PublisherSearchResultsInterface;
use GB\PublisherBook\Api\Data\PublisherSearchResultsInterfaceFactory;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use GB\PublisherBook\Model\ResourceModel\Publisher as ResourcePublisher;
use GB\PublisherBook\Model\ResourceModel\Publisher\CollectionFactory as PublisherCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface as SearchCriteriaInterfaceAlias;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

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
class PublisherRepository implements PublisherRepositoryInterface
{

    /**
     * @var ResourcePublisher
     */
    protected ResourcePublisher $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;

    /**
     * @var Publisher
     */
    protected Publisher|PublisherSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var PublisherInterfaceFactory
     */
    protected PublisherInterfaceFactory $publisherFactory;

    /**
     * @var PublisherCollectionFactory
     */
    protected PublisherCollectionFactory $publisherCollectionFactory;


    /**
     * @param ResourcePublisher $resource
     * @param PublisherInterfaceFactory $publisherFactory
     * @param PublisherCollectionFactory $publisherCollectionFactory
     * @param PublisherSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourcePublisher $resource,
        PublisherInterfaceFactory $publisherFactory,
        PublisherCollectionFactory $publisherCollectionFactory,
        PublisherSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->publisherFactory = $publisherFactory;
        $this->publisherCollectionFactory = $publisherCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(PublisherInterface $publisher)
    {
        try {
            $this->resource->save($publisher);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the publisher: %1',
                $exception->getMessage()
            ));
        }
        return $publisher;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById(int $id): mixed
    {
        $publisher = $this->publisherFactory->create();
        $publisher->load($id);

        if (!$publisher->getId()) {
            throw new NoSuchEntityException(__('Publisher with id "%1" does not exist.', $id));
        }

        return $publisher;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterfaceAlias $searchCriteria
    ): PublisherSearchResultsInterface
    {
        $collection = $this->publisherCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];

        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(PublisherInterface $publisher): bool
    {
        try {
            $publisherModel = $this->publisherFactory->create();
            $this->resource->load($publisherModel, $publisher->getEntityId());
            $this->resource->delete($publisherModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Publisher: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($publisherId): bool
    {
        return $this->delete($this->get((int)$publisherId));
    }
}
