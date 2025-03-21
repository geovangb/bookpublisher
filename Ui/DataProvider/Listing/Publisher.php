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

namespace GB\PublisherBook\Ui\DataProvider\Listing;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use GB\PublisherBook\Model\ResourceModel\Publisher\CollectionFactory;
use Magento\Framework\UrlInterface;

class Publisher extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */

    private StoreManagerInterface $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * Get Data Listing
     *
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        $baseurl =  $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $temp = $item->getData();
            $img = [];
            $img[0]['name'] = $temp['logo'];
            $img[0]['url'] = $baseurl . 'label/icon/' . $temp['logo'];
            $temp['logo'] = $img;
            $this->loadedData[$item->getId()] = $temp;
        }

        return $this->loadedData;
    }
}
