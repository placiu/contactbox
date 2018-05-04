<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Person;
use ContactBundle\Entity\Phone;
use ContactBundle\Form\AddPhoneForm;
use ContactBundle\Form\DeletePhoneForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{
    /**
     * @Route("/{id}/phone/add", name="phoneAdd")
     */
    public function phoneAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $phoneForm = $this->createForm(AddPhoneForm::class, new Phone());
        $phoneForm->handleRequest($request);

        if ($phoneForm->isSubmitted()) {
            $phone = $phoneForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->persist($phone->setPerson($person));
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/new_phone.html.twig', [
            'form' => $phoneForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/phone/delete", name="phoneDelete")
     */
    public function phoneDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $phoneForm = $this->createForm(DeletePhoneForm::class, $person->getPhones());
        $phoneForm->handleRequest($request);

        if ($phoneForm->isSubmitted()) {
            $phone = $phoneForm->get('phones')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($phone);
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/delete_phone.html.twig', [
            'form' => $phoneForm->createView()
        ]);
    }
}
