<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employer;
use App\Repository\EmployerRepository;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Types\Object_;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\EmployerType;
use Symfony\Component\HttpFoundation\Response;

class SupermarcheController extends AbstractController
{
    /**
     * @Route("/supermarche", name="supermarche")
     */
    public function index(EmployerRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Employer::class);

        $employers = $repo->findAll();

        return $this->render('supermarche/index.html.twig', [
            'controller_name' => 'SupermarcheController', 'employers' => $employers
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('supermarche/home.html.twig');
    }

    /**
     * @Route("/supermarche/new", name="supermarche_create")
     * @Route("/supermarche/{id}/edit", name="supermarche_edit")
     */
    public function form(Employer $employer = null, Request $request, ObjectManager $manager)
    {

        if (!$employer) {
            $employer  = new Employer();
        }

        /* $form = $this->createFormBuilder($employer)
            ->add('matricule')
            ->add('prenomEtNom')
            ->add('dateDeNaissance', DateType::class, [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',])
            ->add('salaire')
            ->getForm();
        */

        $form = $this->createForm(EmployerType::class, $employer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($employer);
            $manager->flush();

            return $this->redirectToRoute('supermarche', ['id' => $employer->getId()]);
        }

        return $this->render('supermarche/create.html.twig', ['formEmployer' => $form->createView(), 'editMode' => $employer->getId() !== null]);
    }


/**
 * @Route("/supermarche/delete/{id}" , name="sup")
 */
    public function delete(Request $request, $id)
    {
        $employer = $this->getDoctrine()->getRepository(Employer::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($employer);
        $entityManager->flush();

        return $this->redirectToRoute('supermarche', ['id' => $employer->getId()]);

        // $response = new Response();
        // $response->send();

    }
}