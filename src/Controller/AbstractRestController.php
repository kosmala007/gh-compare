<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AbstractRestController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function response(mixed $data): JsonResponse
    {
        $data = $this->serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($data);
    }
}
