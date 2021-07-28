<?php declare(strict_types=1);

namespace App\Component\Category\Communication\Controller;

use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Category\Communication\Form\CategoryAddNewType;
use App\Component\Category\Communication\Form\CategoryUpdateType;
use App\DataTransferObject\CategoryDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;

    /**
     * CategoryController constructor.
     * @param CategoryBusinessFacadeInterface $categoryBusinessFacade
     */
    public function __construct(CategoryBusinessFacadeInterface $categoryBusinessFacade)
    {
        $this->categoryBusinessFacade = $categoryBusinessFacade;
    }


    /**
     * @Route("/category", name="category_category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/detail/{categoryId}", name="category_detail")
     */
    public function detail(Request $request, int $categoryId): Response
    {
        $category = $this->categoryBusinessFacade->getCategoryById($categoryId);
        if (!$category instanceof CategoryDataProvider) {
            throw $this->createNotFoundException('Category does not exist');
        }

        return $this->render('category/detail.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/list", name="category_list")
     */
    public function list(): Response
    {
        $categoryList = $this->categoryBusinessFacade->getCategoryList();

        return $this->render('category/list.html.twig', [
            'categoryList' => $categoryList,
        ]);
    }


    /**
     * @Route("/category/delete/{categoryId}", name="category_delete")
     */
    public function delete(int $categoryId): Response
    {
        $category = $this->categoryBusinessFacade->getCategoryById($categoryId);
        if (!$category instanceof CategoryDataProvider) {
            throw $this->createNotFoundException('Category does not exist');
        }

        $this->categoryBusinessFacade->delete($category);

        return $this->redirectToRoute('category_list');
    }

    /**
     * @Route("/category/update/{categoryId}", name="category_update")
     */
    public function update(Request $request, int $categoryId): Response
    {
        $category = $this->categoryBusinessFacade->getCategoryById($categoryId);
        if (!$category instanceof CategoryDataProvider) {
            throw $this->createNotFoundException('Category does not exist');
        }

        $form = $this->createForm(CategoryUpdateType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryBusinessFacade->save($category);

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/update.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/new", name="category_new")
     */
    public function new(Request $request): Response
    {
        $category = new CategoryDataProvider();
        $form = $this->createForm(CategoryAddNewType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();
            $category->setName($name);
            $this->categoryBusinessFacade->save($category);

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/addNew.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
