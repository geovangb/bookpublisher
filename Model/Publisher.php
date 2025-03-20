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

use Magento\Framework\Model\AbstractModel;
use GB\PublisherBook\Model\ResourceModel\Publisher as PublisherResource;

class Publisher extends AbstractModel implements \GB\PublisherBook\Api\Data\PublisherInterface
{
    const CACHE_TAG = 'publisher_id';

    protected function _construct()
    {
        $this->_init(PublisherResource::class);
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Magento\Catalog\Model\Product
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * @inheritDoc
     */
    public function getLogo()
    {
        return $this->getData(self::LOGO);
    }

    /**
     * @inheritDoc
     */
    public function setLogo($logo)
    {
        return $this->setData(self::LOGO, $logo);
    }

    /**
     * @inheritDoc
     */
    public function getCnpj()
    {
        return $this->getData(self::CNPJ);
    }

    /**
     * @inheritDoc
     */
    public function setCnpj($cnpj)
    {
        return $this->setData(self::CNPJ, $cnpj);
    }

    /**
     * @inheritDoc
     */
    public function getCreateAt()
    {
        return $this->getData(self::CREATE_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreateAt($createAt)
    {
        return $this->setData(self::CREATE_AT, $createAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}
