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

use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use GB\PublisherBook\Model\ResourceModel\Publisher\CollectionFactory;
use Magento\Framework\UrlInterface;

class Publisher extends AbstractDataProvider
{
    protected $loadedData;
    private StoreManagerInterface $storeManger;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManger,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->storeManger = $storeManger;
    }

    public function getData()
    {
        $baseurl =  $this->storeManger->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
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
            $this->loadedData[$item->getId()] = array_merge($item->getData(), $temp);
        }

        return $this->loadedData;
    }
}
