<?php

namespace App\Controller;

use App\Entity\Goods;
use App\Repository\GoodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, GoodsRepository $goodsRepository): Response
    {
        $keyword = $request->query->get('keyword');
        $page = $request->query->getInt('page', 1);
        $perPage = 12;
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort');
        $gender = $request->query->get('gender');

        $results = $goodsRepository->searchByKeyword($keyword, $page, $perPage, $minPrice, $maxPrice, $sort, $gender);


        return $this->render('search_results.html.twig', [
            'results' => $results,
            'keyword' => $keyword,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }


}