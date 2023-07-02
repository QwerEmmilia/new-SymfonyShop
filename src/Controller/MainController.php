<?php

namespace App\Controller;

use App\Entity\Goods;
use App\Entity\GoodsSize;
use App\Entity\Size;
use App\Repository\GoodsRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $entityManager;
    private $cartService;

    public function __construct(EntityManagerInterface $entityManager, CartService $cartService)
    {
        $this->entityManager = $entityManager;
        $this->cartService = $cartService;
    }

    #[Route('/', name: 'app_main')]
    public function homepage(): Response
    {
        $goods = $this->entityManager->getRepository(Goods::class)->findAll();

        return $this->render('main_page.html.twig', [
            'goods' => $goods,
        ]);
    }

    #[Route('/header-cart-quantity', name: 'app_header_cart')]
    public function headerCart(): Response
    {
        $cartQuantity = $this->cartService->getCartQuantity();

        return $this->render('header.html.twig', [
            'cartQuantity' => $cartQuantity,
        ]);
    }

    #[Route('/goods-page/{slug}', name: 'app_goodsPage')]
    public function goodsPage(Goods $goods): Response
    {
        $goodsBD = $this->entityManager->getRepository(Goods::class)->findBy([], ['id' => 'DESC'], 6);

        $hasAvailableSizes = false;
        foreach ($goods->getGoodsSizes() as $goodsSize) {
            if ($goodsSize->getQuantity() > 0) {
                $hasAvailableSizes = true;
                break;
            }
        }

        return $this->render('goods_page.html.twig', [
            'goods' => $goods,
            'goodsBD' => $goodsBD,
            'hasAvailableSizes' => $hasAvailableSizes,
        ]);
    }


    #[Route('/female/{type}', name: 'app_goodsFemale')]
    public function goodsFemale(Request $request, GoodsRepository $goodsRepository, string $type = null): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = 12;
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort');

        $typeTranslations = [
            't-shirt' => 'Футболки',
            'outerwear' => 'Плащи',
            'Pants' => 'Штани',
        ];

        $paginator = $goodsRepository->getGoodsPaginator($page, $perPage, $minPrice, $maxPrice, $sort, $type, 'Female');

        return $this->render('femaleGoods.html.twig', [
            'paginator' => $paginator,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'type' => $type,
            'typeTranslations' => $typeTranslations,
        ]);
    }

    #[Route('/male/{type}', name: 'app_goodsMale')]
    public function goodsMale(Request $request, GoodsRepository $goodsRepository, string $type = null): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = 12;
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort');

        $typeTranslations = [
            't-shirt' => 'Футболки',
            'outerwear' => 'Плащи',
            'Pants' => 'Штани',
            'hat' => 'Кепки',
            'backpack' => 'Рюкзаки',
        ];

        $paginator = $goodsRepository->getGoodsPaginator($page, $perPage, $minPrice, $maxPrice, $sort, $type, 'Male');

        return $this->render('maleGoods.html.twig', [
            'paginator' => $paginator,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'type' => $type,
            'typeTranslations' => $typeTranslations,
        ]);
    }



    #[Route('/a', name: 'app_a')]
    public function newGoods(): Response
    {
        $goods1 = new Goods();
        $goods1->setName('Футболка Оверсайз Унісекс Black');
        $goods1->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods1->setComposition('95% бавовна,5% поліестер');
        $goods1->setPrice(10.49);
        $goods1->setGender('Male');
        $goods1->setType('T-shirt');
        $goods1->setImage('https://i0.wp.com/tabooclothes.com.ua/wp-content/uploads/2023/01/MG_9409-%D0%BA%D0%BE%D0%BF%D0%B8%D1%8F.webp?fit=3050%2C3812&ssl=1');

        $goods2 = new Goods();
        $goods2->setName('Чорна базова футболка');
        $goods2->setDescription('lorem fdjsaiofas fwiqeadafda ffqef f fa');
        $goods2->setComposition('94% бавовна,6% поліестер');
        $goods2->setPrice(10);
        $goods2->setGender('Female');
        $goods2->setType('T-shirt');
        $goods2->setImage('https://img2.ans-media.com/i/1080x1626/AW23-TSD03O-99X_F1.jpg?v=1685988512');

        $goods3 = new Goods();
        $goods3->setName('ФУТБОЛКА SMASH OVERSIZE "STRETCH COTTON" ЧОРНА');
        $goods3->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd jsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef ffadafda ffqef f fa');
        $goods3->setComposition('95% бавовна,5% поліестер');
        $goods3->setPrice(14);
        $goods3->setGender('Male');
        $goods3->setType('T-shirt');
        $goods3->setImage('https://smash.com.ua/image/cache/catalog/futbolki/strechCoton/1-700x956.jpg');

        $goods4 = new Goods();
        $goods4->setName('ДЖИНСОВА КЕПКА');
        $goods4->setDescription('lorem fdjsaiofas fwiqenoofnqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods4->setComposition('95% бавовна,5% поліестер');
        $goods4->setPrice(9.99);
        $goods4->setGender('Male');
        $goods4->setType('hat');
        $goods4->setImage('https://smash.com.ua/image/cache/catalog/caps/jinceKep/bezj/1-700x956.jpg');

        $goods5 = new Goods();
        $goods5->setName('РЮКЗАК WEROCKER "PAST" ЧОРНИЙ');
        $goods5->setDescription('lorem fdjsaуцй23w12wiqenoofnавіфафівdasqwe fasjdnflaeeq fdafasd fadafda ffqef f fa');
        $goods5->setComposition(' ');
        $goods5->setPrice(55.50);
        $goods5->setGender('Male');
        $goods5->setType('backpack');
        $goods5->setImage('https://smash.com.ua/image/cache/catalog/Backpacks/past/22-700x956.jpg');

        $goods6 = new Goods();
        $goods6->setName('ПАЛЬТО INFLATION "INTELLIGENCE" СІРЕ');
        $goods6->setDescription('fda');
        $goods6->setComposition('поліефірне волокно 100%');
        $goods6->setPrice(39.99);
        $goods6->setGender('Female');
        $goods6->setType('outerwear');
        $goods6->setImage('https://smash.com.ua/image/cache/catalog/kurtki/intelligence/zeroo-700x956.jpg');

        $goods7 = new Goods();
        $goods7->setName('Блакитні джинси з розрізами ззаду');
        $goods7->setDescription('fуцййвйц віфda');
        $goods7->setComposition('100% котон');
        $goods7->setPrice(14.81);
        $goods7->setGender('Female');
        $goods7->setType('Pants');
        $goods7->setImage('https://img2.ans-media.com/i/1080x1626/AW22-SJD023_55D_F1.jpg?v=1661422493');


        $this->entityManager->persist($goods1);
        $this->entityManager->persist($goods2);
        $this->entityManager->persist($goods3);
        $this->entityManager->persist($goods4);
        $this->entityManager->persist($goods5);
        $this->entityManager->persist($goods6);
        $this->entityManager->persist($goods7);

        $sizeXS = new Size();
        $sizeXS->setSize('XS');
        $sizeS = new Size();
        $sizeS->setSize('S');
        $sizeM = new Size();
        $sizeM->setSize('M');
        $sizeL = new Size();
        $sizeL->setSize('L');
        $sizeXL = new Size();
        $sizeXL->setSize('XL');
        $sizeXXL = new Size();
        $sizeXXL->setSize('XXL');


        $this->entityManager->persist($sizeXS);
        $this->entityManager->persist($sizeS);
        $this->entityManager->persist($sizeM);
        $this->entityManager->persist($sizeL);
        $this->entityManager->persist($sizeXL);
        $this->entityManager->persist($sizeXXL);

        $goodsSize1 = new GoodsSize();
        $goodsSize1->setGoodsId($goods1);
        $goodsSize1->setSizeId($sizeM);
        $goodsSize1->setQuantity(5);

        $goodsSize2 = new GoodsSize();
        $goodsSize2->setGoodsId($goods1);
        $goodsSize2->setSizeId($sizeS);
        $goodsSize2->setQuantity(7);

        $goodsSize3 = new GoodsSize();
        $goodsSize3->setGoodsId($goods2);
        $goodsSize3->setSizeId($sizeXL);
        $goodsSize3->setQuantity(3);

        $goodsSize4 = new GoodsSize();
        $goodsSize4->setGoodsId($goods2);
        $goodsSize4->setSizeId($sizeXXL);
        $goodsSize4->setQuantity(2);

        $goodsSize5 = new GoodsSize();
        $goodsSize5->setGoodsId($goods3);
        $goodsSize5->setSizeId($sizeXS);
        $goodsSize5->setQuantity(5);

        $goodsSize6 = new GoodsSize();
        $goodsSize6->setGoodsId($goods3);
        $goodsSize6->setSizeId($sizeS);
        $goodsSize6->setQuantity(12);

        $goodsSize7 = new GoodsSize();
        $goodsSize7->setGoodsId($goods3);
        $goodsSize7->setSizeId($sizeM);
        $goodsSize7->setQuantity(5);

        $goodsSize8 = new GoodsSize();
        $goodsSize8->setGoodsId($goods3);
        $goodsSize8->setSizeId($sizeL);
        $goodsSize8->setQuantity(15);

        $goodsSize9 = new GoodsSize();
        $goodsSize9->setGoodsId($goods3);
        $goodsSize9->setSizeId($sizeXL);
        $goodsSize9->setQuantity(0);

        $goodsSize10 = new GoodsSize();
        $goodsSize10->setGoodsId($goods3);
        $goodsSize10->setSizeId($sizeXXL);
        $goodsSize10->setQuantity(1);

        $goodsSize11 = new GoodsSize();
        $goodsSize11->setGoodsId($goods4);
        $goodsSize11->setSizeId($sizeM);
        $goodsSize11->setQuantity(23);

        $goodsSize12 = new GoodsSize();
        $goodsSize12->setGoodsId($goods5);
        $goodsSize12->setSizeId($sizeM);
        $goodsSize12->setQuantity(5);

        $goodsSize13 = new GoodsSize();
        $goodsSize13->setGoodsId($goods6);
        $goodsSize13->setSizeId($sizeM);
        $goodsSize13->setQuantity(4);

        $goodsSize14 = new GoodsSize();
        $goodsSize14->setGoodsId($goods7);
        $goodsSize14->setSizeId($sizeM);
        $goodsSize14->setQuantity(4);

        $goodsSize15 = new GoodsSize();
        $goodsSize15->setGoodsId($goods6);
        $goodsSize15->setSizeId($sizeXL);
        $goodsSize15->setQuantity(4);



        $this->entityManager->persist($goodsSize1);
        $this->entityManager->persist($goodsSize2);
        $this->entityManager->persist($goodsSize3);
        $this->entityManager->persist($goodsSize4);
        $this->entityManager->persist($goodsSize5);
        $this->entityManager->persist($goodsSize6);
        $this->entityManager->persist($goodsSize7);
        $this->entityManager->persist($goodsSize8);
        $this->entityManager->persist($goodsSize9);
        $this->entityManager->persist($goodsSize10);
        $this->entityManager->persist($goodsSize11);
        $this->entityManager->persist($goodsSize12);
        $this->entityManager->persist($goodsSize13);
        $this->entityManager->persist($goodsSize14);
        $this->entityManager->persist($goodsSize15);


//        $this->entityManager->flush();


        return $this->render('a.html.twig');
    }
}
