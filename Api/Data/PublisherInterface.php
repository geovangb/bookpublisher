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
    public const MAIN_TABLE = 'gb_publisher_book';
    public const UPDATED_AT = 'updated_at';
    public const NAME = 'name';
    public const CREATE_AT = 'create_at';
    public const ADDRESS = 'address';
    public const STATUS = 'status';
    public const CNPJ = 'cnpj';
    public const ENTITY_ID = 'entity_id';
    public const LOGO = 'logo';

    public const STATUS_ACTIVE = '1';
    public const STATUS_INACTIVE = '0';

    /**
     * Get  Entity ID
     *
     * @return int
     */
    public function getEntityId(): int;

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set Name
     *
     * @param string $name
     * @return PublisherInterface
     */
    public function setName(string $name): PublisherInterface;

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set Status
     *
     * @param int $status
     * @return int
     */
    public function setStatus(int $status): int;

    /**
     * Get Address
     *
     * @return string|null
     */
    public function getAddress(): ?string;

    /**
     * Set Address
     *
     * @param string $address
     * @return string
     */
    public function setAddress(string $address): string;

    /**
     * Get logo
     *
     * @return string|null
     */
    public function getLogo();

    /**
     * Set Logo
     *
     * @param string $logo
     * @return mixed
     */
    public function setLogo(string $logo): mixed;

    /**
     * Get Cnpj
     *
     * @return mixed
     */
    public function getCnpj(): mixed;

    /**
     * Set Cnpj
     *
     * @param string $cnpj
     * @return string
     */
    public function setCnpj($cnpj): string;

    /**
     * Get create_at
     *
     * @return string|null
     */
    public function getCreateAt(): ?string;

    /**
     * Set create_at
     *
     * @param string $createAt
     * @return mixed
     */
    public function setCreateAt($createAt): mixed;

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return mixed
     */
    public function setUpdatedAt(string $updatedAt): mixed;
}
