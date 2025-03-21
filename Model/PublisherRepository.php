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
use GB\PublisherBook\Model\ResourceModel\Publisher\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

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
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @param ResourcePublisher $resource
     * @param PublisherInterfaceFactory $publisherFactory
     * @param CollectionFactory $collectionFactory
     * @param PublisherSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourcePublisher $resource,
        PublisherInterfaceFactory $publisherFactory,
        CollectionFactory $collectionFactory,
        PublisherSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->publisherFactory = $publisherFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(PublisherInterface $publisher): PublisherInterface
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
     * Get By Enntity Id
     *
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
    public function getList(SearchCriteriaInterface $searchCriteria): PublisherSearchResultsInterface
    {
        $searchResult = $this->searchResultsFactory->create();
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
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
