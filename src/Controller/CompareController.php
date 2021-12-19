<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Compare;
use App\Exception\MissingRepoUrlException;
use App\Service\CompareService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CompareController extends AbstractRestController
{
    /**
     * @throws MissingRepoUrlException
     *`
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Compare::class))
     *     )
     * )
     * @OA\Parameter(name="first", in="query", description="First repository name or url",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(name="second", in="query", description="Second repository name or url",
     *     @OA\Schema(type="string")
     * )
     */
    #[Route('/compare', name: 'compare', methods: ['GET'])]
    public function index(Request $request, CompareService $service): Response
    {
        $first = $request->query->get('first');
        if (!$first) {
            throw new MissingRepoUrlException('first');
        }
        $second = $request->query->get('second');
        if (!$second) {
            throw new MissingRepoUrlException('second');
        }
        $compare = $service->compare($first, $second);

        return $this->response($compare);
    }
}
