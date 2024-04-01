<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException && (strpos($exception->getMessage(), 'App\Entity\User') !== false)) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'Cet utilisateur n\'existe pas.'
            ];

            $event->setResponse(new JsonResponse($data));
        } elseif ($exception instanceof NotFoundHttpException && (strpos($exception->getMessage(), 'App\Entity\Product') !== false)) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'Ce produit n\'existe pas.'
            ];

            $event->setResponse(new JsonResponse($data));
        } elseif ($exception instanceof HttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        } else {
            $data = [
                'status' => 500,
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
