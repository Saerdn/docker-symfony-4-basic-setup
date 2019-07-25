<?php
namespace App\Controller;

use App\Entity\ChildClass;
use App\Form\ChildClassFormType;
use App\Repository\ChildClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ChildClassController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ChildClassRepository
     */
    private $ccr;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, ChildClassRepository $ccr, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->ccr = $ccr;
        $this->logger = $logger;
    }

    /**
     * @Route("/backend/child/create/", name="child_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        return $this->processRequest($request);
    }

    /**
     * /**
     * @Route(
     *     "/backend/child/{id}/edit",
     *     name="child_edit",
     *     requirements={"id"="\d+"}
     * )
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request)
    {
        return $this->processRequest($request, $id);
    }


    /**
     * @Route("/api/child/{id}/delete", name="child_delete")
     *
     * @param $id
     * @param EntityManagerInterface $em
     * @param ChildClassRepository $ccr
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, EntityManagerInterface $em, ChildClassRepository $ccr, LoggerInterface $logger)
    {
        try {
            $child = $ccr->find($id);

            if (empty($child)) {
                throw new \Exception('No child found for id ' . $id);
            }

            $em->remove($child);
            $em->flush();
            $this->addFlash(
                "success",
                "Child <b>{$child->getName()}</b> was successfully deleted"
            );

            return $this->redirectToRoute('child_overview');

        } catch(\Exception $e) {
            $logger->critical($e->getMessage(), ['API Delete child' => $id]);

            $this->addFlash(
                "warning",
                "An error occured while deleting the child"
            );

            return $this->redirectToRoute('child_overview');
        }
    }
    
    /**
     * @Route("/backend/child", name="child_overview")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInterface $user)
    {
        $children = $this->ccr->findAll();

        return $this->render('child/index.html.twig', [
            'children' => $children
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
             * @var ChildClass $child
             */
            $child = new ChildClass();
        } else {
            $child = $this->ccr->find($id);
            if ($child == null) {
                return $this->render('child/create.html.twig', [
                    'error' => 'No child found'
                ]);
            }
        }

        $form = $this->createForm(ChildClassFormType::class, $child);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /**
                 * @var ChildClass $child
                 */
                $child = $form->getData();
                $this->em->persist($child);
                $this->em->flush();

                $this->addFlash(
                    "success",
                    "Child <b>{$child->getName()}</b> successfully saved."
                );

                return $this->redirectToRoute('child_edit', ['id' => $child->getId()]);

            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
                $this->addFlash('critical', 'An error occured while editing the child');
            }
        }

        return $this->render('child/create.html.twig', [
            'title' => 'Edit child',
            'form' => $form->createView(),
            'child' => $child
        ]);
    }
}