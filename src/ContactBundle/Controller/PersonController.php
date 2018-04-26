<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Address;
use ContactBundle\Entity\Mail;
use ContactBundle\Entity\Person;
use ContactBundle\Entity\Phone;
use ContactBundle\Form\addAddressForm;
use ContactBundle\Form\addMailForm;
use ContactBundle\Form\addPhoneForm;
use ContactBundle\Form\deleteAddressForm;
use ContactBundle\Form\addPersonForm;
use ContactBundle\Form\deleteMailForm;
use ContactBundle\Form\deletePhoneForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
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

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(addPersonForm::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            /** @var UploadedFile $file */
            $file = $person->getImagePath();
            if ($file) {
                $fileName = $person->getFirstName() . $person->getLastName() . '_' . rand(1, 1000) . '.' . $file->guessExtension();
                $file->move('images/', $fileName);
                $person->setImagePath($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('profile', ['id' => $person->getId()]);
        }
        return $this->render('/Person/new_person.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="profile")
     */
    public function profileAction($id)
    {
        $peopleRepository = $this->getDoctrine()->getRepository(Person::class);
        $person = $peopleRepository->find($id);
        return $this->render('/Person/profile.html.twig', array(
            'person' => $person
        ));
    }

    /**
     * @Route("/{id}/modify", name="modify")
     */
    public function modifyAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);

        if ($person) {
            $personForm = $this->createForm(addPersonForm::class, $person);
            $personForm->handleRequest($request);

            if ($personForm->isSubmitted()) {
                $person = $personForm->getData();
                /** @var UploadedFile $file */
                $file = $person->getImagePath();
                if ($file) {
                    $fileName = $person->getFirstName() . $person->getLastName(). '_' . rand(1,1000) . '.' . $file->guessExtension();
                    $file->move('images/',$fileName);
                    $person->setImagePath($fileName);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($person);
                $em->flush();
            }

            return $this->redirectToRoute('profile', ['id' => $id]);
        }
        return $this->redirectToRoute('all');
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
     * @Route("/{id}/address/add", name="addressAdd")
     */
    public function addressAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $addressForm = $this->createForm(addAddressForm::class, new Address());
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted()) {
            $address = $addressForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->persist($address->setPerson($person));
            $em->flush();
            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Person/new_address.html.twig', array(
            'form' => $addressForm->createView()
        ));
    }

    /**
     * @Route("/{id}/address/delete", name="addressDelete")
     */
    public function addressDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $addressForm = $this->createForm(deleteAddressForm::class, $person->getAddresses());
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted()) {
            $address = $addressForm->get('addresses')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();
        }

        return $this->render('Person/delete_address.html.twig', array(
            'form' => $addressForm->createView()
        ));
    }

    /**
     * @Route("/{id}/phone/add", name="phoneAdd")
     */
    public function phoneAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $phoneForm = $this->createForm(addPhoneForm::class, new Phone());
        $phoneForm->handleRequest($request);

        if ($phoneForm->isSubmitted()) {
            $phone = $phoneForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->persist($phone->setPerson($person));
            $em->flush();
            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Person/new_phone.html.twig', array(
            'form' => $phoneForm->createView()
        ));
    }

    /**
     * @Route("/{id}/phone/delete", name="phoneDelete")
     */
    public function phoneDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $phoneForm = $this->createForm(deletePhoneForm::class, $person->getPhones());
        $phoneForm->handleRequest($request);

        if ($phoneForm->isSubmitted()) {
            $phone = $phoneForm->get('phones')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($phone);
            $em->flush();
        }

        return $this->render('Person/delete_phone.html.twig', array(
            'form' => $phoneForm->createView()
        ));
    }

    /**
     * @Route("/{id}/mail/add", name="mailAdd")
     */
    public function mailAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $mailForm = $this->createForm(addMailForm::class, new Mail());
        $mailForm->handleRequest($request);

        if ($mailForm->isSubmitted()) {
            $mail = $mailForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mail);
            $em->persist($mail->setPerson($person));
            $em->flush();
            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Person/new_mail.html.twig', array(
            'form' => $mailForm->createView()
        ));
    }

    /**
     * @Route("/{id}/mail/delete", name="mailDelete")
     */
    public function mailDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $mailForm = $this->createForm(deleteMailForm::class, $person->getMails());
        $mailForm->handleRequest($request);

        if ($mailForm->isSubmitted()) {
            $mail = $mailForm->get('mails')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($mail);
            $em->flush();
        }

        return $this->render('Person/delete_mail.html.twig', array(
            'form' => $mailForm->createView()
        ));
    }

}
