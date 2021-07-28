<?php declare(strict_types=1);

namespace App\Component\ProductFrontEnd\Communication\Controller;


use App\Component\Import\Business\ImportBusinessFacade;
use App\Component\ProductFrontEnd\Business\Upload\FileUploader;
use App\Component\ProductFrontEnd\Communication\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductImportController extends AbstractController
{
    private ImportBusinessFacade $importBusinessFacade;

    /**
     * ProductImportController constructor.
     * @param ImportBusinessFacade $importBusinessFacade
     */
    public function __construct(ImportBusinessFacade $importBusinessFacade)
    {
        $this->importBusinessFacade = $importBusinessFacade;
    }

    /**
     * @Route("product/import/make", name="product_import_make")
     * @param Request $request
     * @throws \League\Csv\Exception
     */
    //#[Route('/import', name: 'import')]
    public function index(Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class); //second parameter

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $data */
            $data = $form->get('upload_file')->getData();

             if ($data instanceof File ) {
            //if ($data) {
                $fileToSave = $fileUploader->upLoad($data);
                $fileToSave = $fileUploader->getTargetDirectory().'/'.$fileToSave;

                $this->importBusinessFacade->saveCategoriesFromCsvFile($fileToSave);
                $this->importBusinessFacade->saveProductsFromCsvFile($fileToSave);
                $this->addFlash('sucess', 'Ok');
            }
            return $this->redirectToRoute('product_list');
        }

        return $this->render('import/productImport.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
