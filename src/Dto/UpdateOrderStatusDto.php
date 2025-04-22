<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\OrderStatus;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderStatusDto
{
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [OrderStatus::class, 'values'])]
    public string $status;
}
