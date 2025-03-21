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
use Magento\Catalog\Model\Product as ProductAlias;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Eav\Model\Config as EavConfig;

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
     * @var EavConfig
     */
    private EavConfig $eavConfig;

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
        CollectionProcessorInterface $collectionProcessor,
        EavConfig $eavConfig
    ) {
        $this->resource = $resource;
        $this->publisherFactory = $publisherFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->eavConfig = $eavConfig;
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

    /**
     * Get Nname Attribute selected
     * @return mixed
     * @throws LocalizedException
     */
    public function getProductsWithPublisher(): mixed
    {
        $connection = $this->resource->getConnection();
        $productCollection = $this->publisherFactory->create();

        $attribute = $this->eavConfig->getAttribute(ProductAlias::ENTITY, 'book_publisher');
        $attributeId = $attribute->getId();

        $select = $productCollection->getSelect();
        $select->join(
            ['cpei' => $connection->getTableName('catalog_product_entity_int')],
            "e.entity_id = cpei.entity_id AND cpei.attribute_id = {$attributeId}",
            ['publisher_id' => 'cpei.value']
        )->join(
            ['gpb' => $connection->getTableName('gb_publisher_book')],
            "cpei.value = gpb.publisher_id",
            ['publisher_name' => 'gpb.name']
        );

        return $productCollection->getItems();
    }
}
