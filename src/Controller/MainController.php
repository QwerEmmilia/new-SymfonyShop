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

        $goods1 = new Goods();

        $goods1->setName('Футболка Оверсайз Унісекс Black');
        $goods1->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods1->setComposition('95% бавовна,5% поліестер');
        $goods1->setPrice(10.49);
        $goods1->setRating(4);
        $goods1->setSizes("M, L, S");
        $goods1->setImage('https://i0.wp.com/tabooclothes.com.ua/wp-content/uploads/2023/01/MG_9409-%D0%BA%D0%BE%D0%BF%D0%B8%D1%8F.webp?fit=3050%2C3812&ssl=1');
        $goods1->setQuantity(5);

        $goods2 = new Goods();

        $goods2->setName('Футболка Оверсайз Унісекс Purple');
        $goods2->setDescription('lorem fdjsaiofas fwiqeadafda ffqef f fa');
        $goods2->setComposition('94% бавовна,6% поліестер');
        $goods2->setPrice(11);
        $goods2->setRating(2.5);
        $goods2->setSizes("XXL");
        $goods2->setImage('https://i0.wp.com/tabooclothes.com.ua/wp-content/uploads/2023/01/DSC00249-%D0%BA%D0%BE%D0%BF%D0%B8%D1%8F-2kk.webp?fit=2446%2C3058&ssl=1');
        $goods2->setQuantity(7);

        $goods3 = new Goods();

        $goods3->setName('ФУТБОЛКА SMASH OVERSIZE "STRETCH COTTON" ЧОРНА');
        $goods3->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd jsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef ffadafda ffqef f fa');
        $goods3->setComposition('95% бавовна,5% поліестер');
        $goods3->setPrice(14);
        $goods3->setRating(5);
        $goods3->setSizes("L, S");
        $goods3->setImage('https://smash.com.ua/image/cache/catalog/futbolki/strechCoton/1-700x956.jpg');
        $goods3->setQuantity(2);

        $goods4 = new Goods();

        $goods4->setName('ДЖИНСОВА КЕПКА');
        $goods4->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods4->setComposition('95% бавовна,5% поліестер');
        $goods4->setPrice(9.99);
        $goods4->setRating(5);
        $goods4->setSizes("L");
        $goods4->setImage('https://smash.com.ua/image/cache/catalog/caps/jinceKep/bezj/1-700x956.jpg');
        $goods4->setQuantity(13);


        $goods5 = new Goods();

        $goods5->setName('РЮКЗАК WEROCKER "PAST" ЧОРНИЙ');
        $goods5->setDescription('lorem fdjsaуцй23w12wiqenoofnавіфафівdasqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods5->setComposition(' ');
        $goods5->setPrice(55.50);
        $goods5->setRating(4.1);
        $goods5->setSizes("S");
        $goods5->setImage('https://smash.com.ua/image/cache/catalog/Backpacks/past/22-700x956.jpg');
        $goods5->setQuantity(3);

        $goods6 = new Goods();

        $goods6->setName('ПАЛЬТО INFLATION "INTELLIGENCE" СІРЕ');
        $goods6->setDescription('fda');
        $goods6->setComposition('поліефірне волокно 100%');
        $goods6->setPrice(39.99);
        $goods6->setRating(3);
        $goods6->setSizes("M");
        $goods6->setImage('https://smash.com.ua/image/cache/catalog/kurtki/intelligence/zeroo-700x956.jpg');
        $goods6->setQuantity(5);

        $this->entityManager->persist($goods1);
        $this->entityManager->persist($goods2);
        $this->entityManager->persist($goods3);
        $this->entityManager->persist($goods4);
        $this->entityManager->persist($goods5);
        $this->entityManager->persist($goods6);
//        $this->entityManager->flush();

        return $this->render('main_page.html.twig', [
            'goods' => $goods
        ]);
    }

    #[Route('/goodsPage/{slug}', name: 'app_goodsPage')]
    public function goodsPage(Goods $goods): Response
    {

        $goodsBD = $this->entityManager->getRepository(Goods::class)->findBy([], ['id' => 'DESC'], 6);

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
