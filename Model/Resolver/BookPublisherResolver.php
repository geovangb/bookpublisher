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

namespace GB\PublisherBook\Model\Resolver;

use GB\PublisherBook\Api\Data\PublisherInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class BookPublisherResolver implements ResolverInterface
{
    const BOOK_PUBLISHER = 'book_publisher';
    private PublisherRepositoryInterface $publisherRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param PublisherRepositoryInterface $publisherRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PublisherRepositoryInterface $publisherRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->publisherRepository = $publisherRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return Value|mixed|null
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): mixed
    {
        if (!isset($value[self::BOOK_PUBLISHER])) {
            return null;
        }

        $publisherId = $value[self::BOOK_PUBLISHER];

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(PublisherInterface::ENTITY_ID, $publisherId)
            ->create();
        $publisherList = $this->publisherRepository->getList($searchCriteria)->getItems();

        if (!empty($publisherList)) {
            $publisher = reset($publisherList);
            return $publisher->getName();
        }

        return null;
    }
}
