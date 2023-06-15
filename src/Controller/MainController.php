<?php

namespace App\Controller;

use App\Entity\Goods;
use App\Repository\GoodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_main')]
    public function homepage(): Response
    {

        $goods = $this->entityManager->getRepository(Goods::class)->findAll();

        return $this->render('main_page.html.twig', [
            'goods' => $goods
        ]);
    }

    #[Route('/goodsPage/{slug}', name: 'app_goodsPage')]
    public function goodsPage(Goods $goods): Response
    {

        $goodsBD = $this->entityManager->getRepository(Goods::class)->findBy([], ['id' => 'DESC'], 6);;

        return $this->render('goods_page.html.twig', [
            'goods' => $goods,
            'goodsBD' => $goodsBD,
        ]);
    }

    #[Route('/list', name: 'app_goodsList')]
    public function goodsList(Request $request, GoodsRepository $goodsRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = 12;

        $paginator = $goodsRepository->getGoodsPaginator($page, $perPage);

        return $this->render('goods_list.html.twig', [
            'paginator' => $paginator,
        ]);

    }
}
