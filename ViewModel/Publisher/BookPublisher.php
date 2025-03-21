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

namespace GB\PublisherBook\ViewModel\Publisher;

use Magento\Catalog\Model\ProductRepository;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class BookPublisher extends DataObject implements ArgumentInterface
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;
    /**
     * @var EavConfig
     */
    private EavConfig $eavConfig;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     *  BookPublisher constructor.
     *
     * @param ProductRepository $productRepository
     * @param ResourceConnection $resource
     * @param EavConfig $eavConfig
     * @param RequestInterface $request
     */
    public function __construct(
        ProductRepository $productRepository,
        ResourceConnection $resource,
        EavConfig $eavConfig,
        RequestInterface $request
    ) {
        $this->productRepository = $productRepository;
        $this->resource = $resource;
        $this->eavConfig = $eavConfig;
        $this->request = $request;
    }

    /**
     * Get Book PPublisher Selected
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getBookPublisherSelected(): ?string
    {
        $productId = $this->request->getParam('id');
        if (!$productId) {
            return null;
        }

        $product = $this->productRepository->getById($productId);
        $publisherId = $product->getCustomAttribute('book_publisher')?->getValue();

        if (!$publisherId) {
            return null;
        }

        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName('gb_publisher_book');

        $query = $connection->select()
            ->from($tableName, ['name'])
            ->where('publisher_id = ?', $publisherId);

        return $connection->fetchOne($query) ?: null;
    }
}
