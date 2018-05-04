<?php

namespace ContactBundle\Controller;

use ContactBundle\Entity\Mail;
use ContactBundle\Entity\Person;
use ContactBundle\Form\AddMailForm;
use ContactBundle\Form\DeleteMailForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MailController extends Controller
{
    /**
     * @Route("/{id}/mail/add", name="mailAdd")
     */
    public function mailAddAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $mailForm = $this->createForm(AddMailForm::class, new Mail());
        $mailForm->handleRequest($request);

        if ($mailForm->isSubmitted()) {
            $mail = $mailForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mail);
            $em->persist($mail->setPerson($person));
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/new_mail.html.twig', [
            'form' => $mailForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/mail/delete", name="mailDelete")
     */
    public function mailDeleteAction(Request $request, $id)
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $id, 'owner' => $this->getUser()->getId()]);
        $mailForm = $this->createForm(DeleteMailForm::class, $person->getMails());
        $mailForm->handleRequest($request);

        if ($mailForm->isSubmitted()) {
            $mail = $mailForm->get('mails')->getData();
            $em = $this->getDoctrine()->getManager();
            $em->remove($mail);
            $em->flush();

            return $this->redirectToRoute('modify', ['id' => $id]);
        }

        return $this->render('Contact/delete_mail.html.twig', [
            'form' => $mailForm->createView()
        ]);
    }
}
