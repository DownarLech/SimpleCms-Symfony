<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacadeInterface;
use App\Component\User\Communication\Form\UserAddNewType;
use App\Component\User\Communication\Form\UserUpdateType;
use App\DataTransferObject\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    private UserBusinessFacadeInterface $userBusinessFacade;

    /**
     * UserController constructor.
     * @param UserBusinessFacadeInterface $userBusinessFacade
     */
    public function __construct(UserBusinessFacadeInterface $userBusinessFacade)
    {
        $this->userBusinessFacade = $userBusinessFacade;
    }

    /**
     * @Route("/user", name="user_user")
     */
    //#[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/detail/{userId}", name="user_detail")
     */
    public function detail(int $userId): Response
    {
        $user = $this->userBusinessFacade->getUserById($userId);

        if (!$user instanceof UserDataProvider) {
            throw $this->createNotFoundException('User does not exist');
        }

        return $this->render('user/detail.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/list", name="user_list")
     */
    //#[Route('/list', name: 'list')]
    public function list(): Response
    {
        $userList = $this->userBusinessFacade->getUserList();

        return $this->render('user/list.html.twig', [
            'userList' => $userList,
        ]);
    }

    /**
     * @Route("/user/delete/{userId}", name="user_delete")
     */
    public function delete(int $userId): Response
    {
        $user = $this->userBusinessFacade->getUserById($userId);
        if (!$user instanceof UserDataProvider) {
            throw $this->createNotFoundException('User does not exist');
        }
        $this->userBusinessFacade->delete($user);

        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/user/update/{userId}", name="user_update")
     */
    public function update(Request $request, int $userId): Response
    {
        $user = $this->userBusinessFacade->getUserById($userId);
        if (!$user instanceof UserDataProvider) {
            throw $this->createNotFoundException('User does not exist');
        }

        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userNew = $this->userBusinessFacade->save($user);

            return $this->redirectToRoute('user_detail', ['userId' => $userNew->getId()]);
        }

        return $this->render('user/update.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/new", name="user_new")
     */
    public function new(Request $request): Response
    {
        $user =  new UserDataProvider();
        $form = $this->createForm(UserAddNewType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUsername($form->get('username')->getData());
            $user->setPassword($form->get('password')->getData());
            $user->setUserrole($form->get('userrole')->getData());

            $userNew = $this->userBusinessFacade->save($user);

            return $this->redirectToRoute('user_detail', ['userId' => $userNew->getId()]);
        }

        return $this->render('user/addNew.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
