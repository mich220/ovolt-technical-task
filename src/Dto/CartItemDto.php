<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CartItemDto
{
    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    #[Assert\GreaterThan(0)]
    public int $productId;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $productName;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    #[Assert\GreaterThan(0)]
    public int $price;

    #[Assert\NotBlank]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0)]
    public int $quantity;
}
