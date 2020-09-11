<?php

namespace App\Controller;

use App\Service\Quotes\DataProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    private DataProviderInterface $quotesDataProvider;
    private const MAX_LIMIT = 10;

    public function __construct(DataProviderInterface $quotesDataProvider)
    {
        $this->quotesDataProvider = $quotesDataProvider;
    }

    /**
     * @Route("/api/shout/{slug}", name="shout", methods={"GET"})
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function shout(Request $request, string $slug) : JsonResponse
    {
        $limit = $request->query->get("limit");

        try {
            $result = $this->quotesDataProvider->fetch($this->normalizeSlug($slug), $limit);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            //. " - " . $e->getFile() . " - " . $e->getLine()
            ]);
        }

        return $this->json($result);
    }


    private function normalizeSlug(string $slug): string {
        return \ucwords(\str_replace('-', ' ', $slug));
    }
}
