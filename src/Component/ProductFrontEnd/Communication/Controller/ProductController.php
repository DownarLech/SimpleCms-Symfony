<?php declare(strict_types=1);

namespace App\Component\ProductFrontEnd\Communication\Controller;

use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\Component\ProductFrontEnd\Communication\Form\ProductAddNewType;
use App\Component\ProductFrontEnd\Communication\Form\ProductUpdateType;
use App\DataTransferObject\CsvProductDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;

    /**
     * ProductController constructor.
     * @param ProductBusinessFacadeInterface $productBusinessFacade
     * @param CategoryBusinessFacadeInterface $categoryBusinessFacade
     */
    public function __construct(
        ProductBusinessFacadeInterface $productBusinessFacade,
        CategoryBusinessFacadeInterface $categoryBusinessFacade
    )
    {
        $this->productBusinessFacade = $productBusinessFacade;
        $this->categoryBusinessFacade = $categoryBusinessFacade;
    }

    /**
     * @Route("/product", name="product_product")
     */
    //#[Route('/', name: 'product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    //#[Route('/detail/{productId}', name: 'detail')]

    /**
     * @Route("/product/detail/{productId}", name="product_detail")
     */
    public function detail(int $productId): Response
    {
        $product = $this->productBusinessFacade->getProductById($productId);

        if (!$product instanceof CsvProductDataProvider) {
            throw $this->createNotFoundException('Product does not exist');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    //#[Route('/list', name: 'list')]
    public function list(): Response
    {
        $productsList = $this->productBusinessFacade->getProductList();

        return $this->render('product/list.html.twig', [
            'productsList' => $productsList,
        ]);
    }

    /**
     * @Route("/product/delete/{productId}", name="product_delete")
     */
    public function delete(int $productId): Response
    {
        $product = $this->productBusinessFacade->getProductById($productId);
        if (!$product instanceof CsvProductDataProvider) {
            throw $this->createNotFoundException('Product does not exist');
        }

        $this->productBusinessFacade->delete($product);

        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/product/update/{productId}", name="product_update")
     */
    public function update(Request $request, int $productId): Response
    {
        $product = $this->productBusinessFacade->getProductById($productId);
        if (!$product instanceof CsvProductDataProvider) {
            throw $this->createNotFoundException('Product does not exist');
        }

        $form = $this->createForm(ProductUpdateType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryName = $form->get('categoryName')->getData()->getName();
            $product->setCategoryName($categoryName);

            $productNew = $this->productBusinessFacade->save($product);

            return $this->redirectToRoute('product_detail', ['productId' => $productNew->getId()]);
        }

        return $this->render('product/update.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/new", name="product_new")
     */
    public function new(Request $request): Response
    {
        $product = new CsvProductDataProvider();
        $form = $this->createForm(ProductAddNewType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setName($form->get('name')->getData());
            $product->setDescription($form->get('description')->getData());
            $product->setArticleNumber($form->get('articleNumber')->getData());
            $product->setCategoryName($form->get('categoryName')->getData()->getName());

            $productNew = $this->productBusinessFacade->save($product);

            return $this->redirectToRoute('product_detail', ['productId' => $productNew->getId()]);
        }

        return $this->render('product/addNew.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
