<?php

namespace App\Controller;

use App\DTO\MailMessageDTO;
use App\Entity\Seller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/send-contact-form-mail', name: 'send_contact_from_mail')]
    public function sendQuestion(MailerInterface $mailer, Request $request): JsonResponse
    {


        dump($request->request->all());

        $data = $request->request->all();

        $message = new MailMessageDTO($data["mail_form_firstname"],$data["mail_form_lastname"],$data["mail_form_email"],$data["mail_form_phone"],$data["mail_form_message-text"]);

        if($data["mail_form_seller"]==null || $data["mail_form_seller"]==""){
            $mailTo = "info@stabilius-real.cz";
        }else{
            $mailTo = $data["mail_form_seller"];
        }
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@stabilius-real.cz', 'Kontaktní formulář'))
            ->to($mailTo)
            ->subject('Contact form')
            ->htmlTemplate('email/contact_form.html.twig')
            ->context(['message' => $message]);

        $emailAddress = $data['email'] ?? null;

        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            $email->replyTo(new Address($emailAddress));
        }

        $mailer->send($email);

        return new JsonResponse([
            'success' => true,
            'message' => 'Email sent successfully'
        ]);
    }
}
