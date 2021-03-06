<?php

namespace AppBundle\EventDispatcher\Listener;

use AppBundle\Exception\InvalidRequestArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class InvalidRequestArgumentListener
{
    public function onInvalidRequestArgumentException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!($exception instanceof InvalidRequestArgumentException)) {
            return;
        }

        $errorMessage = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];

        $responseData = [
            'error' => [
                $errorMessage,
            ],
        ];

        $statusCode = $exception->getCode();

        if ($responseData['error'][0]['code'] === 0) {
            $responseData['error'][0]['code'] = Response::HTTP_BAD_REQUEST;
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        $event->setResponse(new JsonResponse($responseData, $statusCode));
    }
}
