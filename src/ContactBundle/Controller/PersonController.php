<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Person;
use ContactBundle\Entity\Phone;
use ContactBundle\Forms\addressForm;
use ContactBundle\Forms\personForm;
use ContactBundle\Forms\phoneForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @Route("/new", name="newGet", methods={"GET"})
     */
    public function newGetAction()
    {
        $form = $this->createForm(personForm::class, ['url' => $this->generateUrl('newPost')]);
        return $this->render('Person/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/new", name="newPost", methods={"POST"})
     */
    public function newPostAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(personForm::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            /** @var UploadedFile $file */
            $file = $person->getImagePath();
            if($file) {
                $fileName = $person->getFirstName() . $person->getLastName() . '_' . rand(1, 1000) . '.' . $file->guessExtension();
                $file->move('images/', $fileName);
                $person->setImagePath($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('single', ['id' => $person->getId()]);
        }
        return $this->render('Person/new.html.twig');
    }

    /**
     * @Route("/{id}/modify", name="modifyGet", methods={"GET"})
     */
    public function modifyGetAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);

        if ($person) {
            $personForm = $this->createForm(personForm::class, $person, array('action' => $this->generateUrl('modifyPost', array('id' => $id)), 'method' => 'post'));
            $addressForm = $this->createForm(addressForm::class, $person->getAddress(), array('action' => $this->generateUrl('modifyPost', array('id' => $id)), 'method' => 'post'));
            $phoneForm = $this->createForm(phoneForm::class, $person, array('action' => $this->generateUrl('modifyPost', array('id' => $id)), 'method' => 'post'));

            $personForm->handleRequest($request);
            $addressForm->handleRequest($request);
            $phoneForm->handleRequest($request);

            return $this->render('Person/modify.html.twig', array(
                'personForm' => $personForm->createView(),
                'addressForm' => $addressForm->createView(),
                'phoneForm' => $phoneForm->createView()
            ));
        }
        return new Request('nie ma takiego usera');
    }

    /**
     * @Route("/{id}/modify", name="modifyPost", methods={"POST"})
     */
    public function modifyPostAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);

        $personForm = $this->createForm(personForm::class, $person);
        $addressForm = $this->createForm(addressForm::class, $person->getAddress());
        $phoneForm = $this->createForm(phoneForm::class, $person);

        $personForm->handleRequest($request);
        $addressForm->handleRequest($request);
        $phoneForm->handleRequest($request);

        if ($personForm->isSubmitted()) {
            $person = $personForm->getData();
            /** @var UploadedFile $file */
            $file = $person->getImagePath();
            if($file) {
                $fileName = $person->getFirstName() . $person->getLastName(). '_' . rand(1,1000) . '.' . $file->guessExtension();
                $file->move('images/',$fileName);
                $person->setImagePath($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
        }
        if ($addressForm->isSubmitted()) {
            $address = $addressForm->getData();
            $address->addPerson($person);
            $person->setAddress($address);
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->persist($person);
            $em->flush();
        }
        if ($phoneForm->isSubmitted()) {
            $phones = $phoneForm->getData();
            $phones->setPerson($person);
            //var_dump(gettype($person));exit;
            $em = $this->getDoctrine()->getManager();
            $em->persist($phones);
            $em->flush();
        }
        return $this->redirectToRoute('modifyGet', array('id' => $id));
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $em->remove($person);
        $em->flush();
        return $this->redirectToRoute('all');
    }

    /**
     * @Route("/{id}", name="single", methods={"GET"})
     */
    public function singlePersonAction($id)
    {
        $peopleRepository = $this->getDoctrine()->getRepository(Person::class);
        $person = $peopleRepository->find($id);
        return $this->render('Person/single_person.html.twig', array(
            'person' => $person
        ));
    }

    /**
     * @Route("/", name="all", methods={"GET"})
     */
    public function allPeopleAction()
    {
        $peopleRepository = $this->getDoctrine()->getRepository(Person::class);
        $people = $peopleRepository->findAll();
        return $this->render('Person/all_people.html.twig', array(
            'people' => $people
        ));
    }

}
