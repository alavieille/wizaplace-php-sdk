<?php
/**
 * @copyright Copyright (c) Wizacha
 * @license Proprietary
 */
declare(strict_types = 1);

namespace Wizaplace\SDK\Discussion;

use function theodorejb\polycast\to_int;

final class Discussion
{
    /** @var int */
    private $id;

    /** @var string */
    private $recipient;

    /** @var int */
    private $productId;

    /** @var string */
    private $title;

    /** @var int */
    private $unreadCount;

    /**
     * @internal
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->recipient = $data['recipient'];
        $this->productId = to_int($data['productId'] ?? 0);
        $this->title = $data['title'];
        $this->unreadCount = $data['unreadCount'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUnreadCount(): int
    {
        return $this->unreadCount;
    }
}
