<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProductSubscriber implements \Doctrine\Common\EventSubscriber
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist
        ];
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->sendNotifEmail($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function sendNotifEmail(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Product) {
            return;
        }
        $email = (new Email())
            ->from('noreply@eshop.com')
            ->to('marketing@eshop.com')
            ->subject("Mail pour l'équipe MARKETING")
            ->html("<p>Bonjour le produit suivant a été crée : " . $entity->getName() . "</p>");

        $this->mailer->send($email);
    }
}
