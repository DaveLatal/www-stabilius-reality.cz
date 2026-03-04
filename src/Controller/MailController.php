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
    public function sendQuestion(MailerInterface $mailer, Request $request, string $seller=null): JsonResponse
    {


        $mailTo = "";
        if($seller==null){
            $mailTo = "info@stabilius-real.cz";
        }else{
            $mailTo = $seller;
        }
        dump($request->request->all());

        $data = $request->request->all();

        $message = new MailMessageDTO($data["mail_form_firstname"],$data["mail_form_lastname"],$data["mail_form_email"],$data["mail_form_phone"],$data["mail_form_message-text"]);
//        if($data["email"]!=""){$replyTo=$data["email"];}else{$replyTo="unknown@stabilius-real.cz";}
        $email = (new TemplatedEmail())
            ->from(new Address('info@stabilius-real.cz', 'Kontaktní formulář'))
//            ->to('info@stabilius-real.cz')
            ->to('davelatal@gmail.com')
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
