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

namespace GB\PublisherBook\Api\Data;

interface PublisherInterface
{
    const MAIN_TABLE = 'gb_publisher_book';
    const UPDATED_AT = 'updated_at';
    const NAME = 'name';
    const CREATE_AT = 'create_at';
    const ADDRESS = 'address';
    const STATUS = 'status';
    const CNPJ = 'cnpj';
    const ENTITY_ID = 'entity_id';
    const LOGO = 'logo';

    const STATUS_ACTIVE = '1';
    const STATUS_INACTIVE = '0';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId(): int;

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setName($name);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setStatus($status);

    /**
     * Get address
     * @return string|null
     */
    public function getAddress();

    /**
     * Set address
     * @param string $address
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setAddress($address);

    /**
     * Get logo
     * @return string|null
     */
    public function getLogo();

    /**
     * Set logo
     * @param string $logo
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setLogo($logo);

    /**
     * Get cnpj
     * @return string|null
     */
    public function getCnpj();

    /**
     * Set cnpj
     * @param string $cnpj
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setCnpj($cnpj);

    /**
     * Get create_at
     * @return string|null
     */
    public function getCreateAt();

    /**
     * Set create_at
     * @param string $createAt
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setCreateAt($createAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \GB\PublisherBook\Publisher\Api\Data\PublisherInterface
     */
    public function setUpdatedAt($updatedAt);
}
