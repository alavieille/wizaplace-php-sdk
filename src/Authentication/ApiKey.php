<?php
/**
 * @copyright Copyright (c) Wizacha
 * @license Proprietary
 */
declare(strict_types = 1);

namespace Wizaplace\SDK\Authentication;

use Wizaplace\SDK\ApiClient;

/**
 * @see ApiClient::authenticate()
 */
final class ApiKey
{
    /** @var int */
    private $id;

    /** @var string */
    private $key;

    /**
     * @internal
     */
    public function __construct(array $data)
    {
        $this->key = $data['apiKey'];
        $this->id = $data['id'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
