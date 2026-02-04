<?php

namespace App\Controller;

use App\DTO\MailMessageDTO;
use App\Entity\Seller;
use http\Env\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/send-contact-form-mail', name: 'send_contact_from_mail')]
    public function sendQuestion(MailerInterface $mailer, Request $request, Seller $seller=null): JsonResponse
    {


        $mailTo = "";
        if($seller==null){
            $mailTo = "info@stabilius-real.cz";
        }else{
            $mailTo = $seller->getEmailContact();
        }

        $data = $request->request->all();

        $message = new MailMessageDTO($data["firstname"],$data["lastname"],$data["email"],$data["phone"],$data["message"]);

        $email = (new TemplatedEmail())
            ->from(new Address('noreply@stabilius-real.cz', 'Kontaktní formulář'))
            ->to(new Address($mailTo))
            ->subject($data["firstname"]. " ".$data["lastname"].' vám zasílá dotaz z webu')
            ->htmlTemplate('email/contact_form.html.twig')
            ->context([
                'message' => $message,
            ]);;

        $mailer->send($email);

        return new JsonResponse([
            'success' => true,
            'message' => 'Email sent successfully'
        ]);
    }
}
