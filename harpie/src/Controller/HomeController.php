<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Contact;
use App\Form\DevisType;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
            $email = (new Email())
            ->from('oumaimasadeddine4@gmail.com') // Votre adresse email
            ->to('oumaimasadeddine4@gmail.com') // Adresse email de l'administrateur
            ->subject('Nouvelle demande de contact')
            ->text('Vous avez reçu une nouvelle demande de contact.')
            ->html('<p>Vous avez reçu une nouvelle demande de contact.</p>
            <p>Nom:</p>'.$contact->getFirstname().' '.$contact->getLastname().
            '<p>Email:</p>'.$contact->getEmail().
            '<p>Numéro de téléphone:</p>'.$contact->getTelephone().
            '<p>Message:</p>'.$contact->getMessage()
        );
        $mailer->send($email);
        $this->addFlash('success', 'Votre message a été envoyée avec succès.');
            
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            
        }

        $devi = new Devis();
        $form2 = $this->createForm(DevisType::class, $devi);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager->persist($devi);
            $entityManager->flush();
            $email = (new Email())
            ->from('oumaimasadeddine4@gmail.com') // Votre adresse email
            ->to('oumaimasadeddine4@gmail.com') // Adresse email de l'administrateur
            ->subject('Nouvelle demande de devis')
            ->text('Vous avez reçu une nouvelle demande de contact.')
            ->html('<p>Vous avez reçu une nouvelle demande de contact.</p>
            <p>Nom:</p>'.$devi->getFirstname().' '.$devi->getLastname().
            '<p>Email:</p>'.$devi->getEmail().
            '<p>Numéro de téléphone:</p>'.$devi->getTelephone().
            '<p>Société / nom de domaine:</p>'.$devi->getSiret().
            '<p>Message:</p>'.$devi->getMessage()
        );
        $mailer->send($email);
            $this->addFlash('success', 'Votre demande de devis a été envoyée avec succès.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'form2' => $form2,
        ]);
    }
}

