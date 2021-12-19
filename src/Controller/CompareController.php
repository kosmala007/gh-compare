<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\MissingRepoUrlException;
use App\Service\CompareService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompareController extends AbstractRestController
{
    /**
     * @throws MissingRepoUrlException
     */
    #[Route('/', name: 'compare')]
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
