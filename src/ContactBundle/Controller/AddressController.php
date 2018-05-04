<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Address;
use ContactBundle\Entity\Person;
use ContactBundle\Form\AddAddressForm;
use ContactBundle\Form\DeleteAddressForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends Controller
{
    /**
     * @Route("/{id}/address/add", name="addressAdd")
     */
    public function addressAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $addressForm = $this->createForm(AddAddressForm::class, new Address());
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted()) {
            $address = $addressForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->persist($address->setPerson($person));
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/new_address.html.twig', [
            'form' => $addressForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/address/delete", name="addressDelete")
     */
    public function addressDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $addressForm = $this->createForm(DeleteAddressForm::class, $person->getAddresses());
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted()) {
            $address = $addressForm->get('addresses')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/delete_address.html.twig', [
            'form' => $addressForm->createView()
        ]);
    }
}
