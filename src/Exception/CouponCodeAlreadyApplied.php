<?php
/**
 * @copyright Copyright (c) Wizacha
 * @license Proprietary
 */
declare(strict_types=1);

namespace Wizaplace\SDK\Exception;

use Wizaplace\SDK\Basket\Exception\CouponAlreadyPresent;

final class CouponCodeAlreadyApplied extends CouponAlreadyPresent implements DomainError
{
    /**
     * @var array
     */
    private $context;

    /**
     * @internal
     */
    public function __construct(string $message, array $context = [], ?\Throwable $previous = null)
    {
        $this->context = $context;

        parent::__construct($message, static::getErrorCode()->getValue(), $previous);
    }


    final public function getContext(): array
    {
        return $this->context;
    }

    public static function getErrorCode(): ErrorCode
    {
        return ErrorCode::COUPON_CODE_ALREADY_APPLIED();
    }
}
