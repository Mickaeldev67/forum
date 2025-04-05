<?php

namespace App\EventSubscriber;

use App\Entity\Thread;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\SecurityBundle\Security;

class ThreadSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getMethod() === 'POST' && $request->getPathInfo() === '/api/threads') {
            $user = $this->security->getUser();

            if ($user && $request->attributes->get('data') instanceof Thread) {
                $thread = $request->attributes->get('data');
                $thread->setUser($user);
            }
        }
    }
}