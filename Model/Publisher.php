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

use GB\PublisherBook\Api\Data\PublisherInterface;
use Magento\Framework\Model\AbstractModel;
use GB\PublisherBook\Model\ResourceModel\Publisher as PublisherResource;

class Publisher extends AbstractModel implements PublisherInterface
{
    public const CACHE_TAG = 'publisher_id';

    /**
     * Model Book Publisher
     *
     * @return void
     */
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

    /**
     * Get Identities
     *
     * @return string[]
     */
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
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): PublisherInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): int
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getAddress(): ?string
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setAddress(string $address): string
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
    public function setLogo(string $logo): Publisher
    {
        return $this->setData(self::LOGO, $logo);
    }

    /**
     * @inheritDoc
     */
    public function getCnpj(): string
    {
        return $this->getData(self::CNPJ);
    }

    /**
     * @inheritDoc
     */
    public function setCnpj($cnpj): string
    {
        return $this->setData(self::CNPJ, $cnpj);
    }

    /**
     * @inheritDoc
     */
    public function getCreateAt(): ?string
    {
        return $this->getData(self::CREATE_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreateAt($createAt): Publisher
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
    public function setUpdatedAt(string $updatedAt): Publisher
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
