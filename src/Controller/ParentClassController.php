<?php
namespace App\Controller;

use App\Entity\ParentClass;
use App\Form\ParentClassFormType;
use App\Repository\ParentClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ParentClassController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ParentClassRepository
     */
    private $pcr;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, ParentClassRepository $pcr, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->pcr = $pcr;
        $this->logger = $logger;
    }

    /**
     * @Route("/backend/parent/create/", name="parent_create")
     */
    public function create(Request $request)
    {
        return $this->processRequest($request);
    }

    /**
    /**
     * @Route(
     *     "/backend/parent/{id}/edit",
     *     name="parent_edit",
     *     requirements={"id"="\d+"}
     * )
     */
    public function edit($id, Request $request)
    {
        return $this->processRequest($request, $id);
    }

    /**
     * @Route("/api/parent/{id}/delete", name="parent_delete")
     *
     * @param $id
     * @param EntityManagerInterface $em
     * @param ParentClassRepository $pcr
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, EntityManagerInterface $em, ParentClassRepository $pcr, LoggerInterface $logger)
    {
        try {
            $parent = $pcr->find($id);

            if (empty($parent)) {
                throw new \Exception('No parent found for id ' . $id);
            }

            $em->remove($parent);
            $em->flush();
            $this->addFlash(
                "success",
                "Parent <b>{$parent->getName()}</b> was successfully deleted"
            );

            return $this->redirectToRoute('parent_overview');

        } catch(\Exception $e) {
            $logger->critical($e->getMessage(), ['API Delete parent' => $id]);

            $this->addFlash(
                "warning",
                "An error occured while deleting the parent"
            );

            return $this->redirectToRoute('parent_overview');
        }
    }
    
    /**
     * @Route("/backend/parent", name="parent_overview")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInterface $user)
    {
        $parents = $this->pcr->findAll();

        return $this->render('parent/index.html.twig', [
            'parents' => $parents
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function processRequest(Request $request, $id = null)
    {
        if (empty($id)) {
            /**
             * @var ParentClass $parent
             */
            $parent = new ParentClass();
        } else {
            $isNew = false;
            $parent = $this->pcr->find($id);
            if ($parent == null) {
                return $this->render('parent/create.html.twig', [
                    'error' => 'No parent found'
                ]);
            }
        }

        $form = $this->createForm(ParentClassFormType::class, $parent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /**
                 * @var ParentClass $parent
                 */
                $parent = $form->getData();
                $this->em->persist($parent);
                $this->em->flush();

                $this->addFlash(
                    "success",
                    "Parent <b>{$parent->getName()}</b> successfully saved."
                );

                return $this->redirectToRoute('parent_edit', ['id' => $parent->getId()]);

            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
                $this->addFlash('critical', 'An error occured while editing the parent');
            }
        }

        return $this->render('parent/create.html.twig', [
            'title' => 'Edit parent',
            'form' => $form->createView(),
            'parent' => $parent
        ]);
    }
}