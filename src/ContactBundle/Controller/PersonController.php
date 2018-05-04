<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Person;
use ContactBundle\Form\AddPersonForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *  @Security("has_role('ROLE_USER')")
 */
class PersonController extends Controller
{
    /**
     * @Route("/", name="all", methods={"GET"})
     */
    public function allPeopleAction()
    {
        $people = $this->getDoctrine()->getRepository(Person::class)->findBy(['owner' => $this->getUser()->getId()], ['lastname' => 'ASC']);
        return $this->render('Contact/all_people.html.twig', [
            'people' => $people
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(AddPersonForm::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $person->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('profile', ['id' => $person->getId()]);
        }
        return $this->render('Contact/new_person.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/profile", name="profile")
     */
    public function profileAction($id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        return $this->render('Contact/profile.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/{id}/modify", name="modify")
     */
    public function modifyAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);

        if ($person) {

            $personForm = $this->createForm(AddPersonForm::class, $person);
            $personForm->handleRequest($request);

            if ($personForm->isSubmitted()) {
                $person = $personForm->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($person);
                $em->flush();

                return $this->redirectToRoute('profile', ['id' => $id]);
            }

            return $this->render('Contact/modify.html.twig', [
                'form' => $personForm->createView(),
                'id' => $id
            ]);
        }

        return $this->redirectToRoute('all');
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"})
     */
    public function deleteAction($id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();

        return $this->redirectToRoute('all');
    }

}
