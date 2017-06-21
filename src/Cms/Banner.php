<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */
declare(strict_types = 1);

namespace Wizaplace\Cms;

use Psr\Http\Message\UriInterface;

class Banner
{
    /**
     * @var UriInterface
     */
    private $link;

    /**
     * @var bool
     */
    private $shouldOpenInNewWindow;

    /**
     * @var int
     */
    private $imageId;

    public function __construct(
        UriInterface $link,
        bool $shouldOpenInNewWindow,
        int $imageId
    ) {
        $this->link = $link;
        $this->shouldOpenInNewWindow = $shouldOpenInNewWindow;
        $this->imageId = $imageId;
    }

    public function getLink(): UriInterface
    {
        return $this->link;
    }

    public function getShouldOpenInNewWindow(): bool
    {
        return $this->shouldOpenInNewWindow;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }
}