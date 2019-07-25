<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserAdminType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var UserRepository $userRepo
     */
    protected $userRepo;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    public function __construct(LoggerInterface $logger, UserRepository $userRepo, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->userRepo = $userRepo;
        $this->em = $em;
    }

    /**
     * @Route("/backend/admin", name="admin_index")
     */
    public function index()
    {
        return $this->render("admin/index.html.twig");
    }

    /**
     * @Route("/backend/admin/user", name="admin_user")
     */
    public function user(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/backend/admin/user/{id}/edit", name="admin_user_edit")
     * @param $id
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userEdit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        return $this->processRequest($request, $passwordEncoder, $id);
    }

    private function processRequest(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        $id = null)
    {
        if (empty($id)) {
            $user = new User();
        } else {
            $user = $this->userRepo->find($id);
            $user = empty($user)? new User() : $user;
        }

        $form = $this->createForm(RegisterUserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $form->get('password')->getData())
                );
                $user->setEmail($form->get('email')->getData());

                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash(
                    "success",
                    "Benutzer <b>{$user->getFirstname()} {$user->getSurname()}</b> wurde erfolgreich gespeichert."
                );

                return $this->redirectToRoute("admin_user");
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage(), ['Create User']);
                $this->addFlash('critical', 'Beim Speichern des Benutzers ist ein Fehler aufgetreten');
            }
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/backend/admin/uzser/create", name="admin_user_create" )
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userCreate(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        return $this->processRequest($request, $passwordEncoder);
    }

    /**
     * @Route("/backend/admin/user/{id}/delete", name="admin_user_delete")
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser($id)
    {
        try {
            $user = $this->userRepo->find($id);

            if (empty($user)) {
                throw new \Exception('No User found for id ' . $id);
            }

            $this->em->remove($user);
            $this->em->flush();

            $this->addFlash(
                "success",
                "User <b>{$user->getEmail()}</b> successfully deleted"
            );

            return $this->redirectToRoute('admin_user');

        } catch(\Exception $e) {
            $this->logger->critical($e->getMessage(), ['API Delete user' => $id]);

            $this->addFlash(
                "warning",
                "An error occured while deleting the user."
            );

            return $this->redirectToRoute('admin_user');
        }
    }
}