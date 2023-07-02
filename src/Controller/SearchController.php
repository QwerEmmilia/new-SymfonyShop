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

        $results = $goodsRepository->searchByKeyword($keyword);

        return $this->render('search_results.html.twig', [
            'results' => $results,
            'keyword' => $keyword,
        ]);
    }


}