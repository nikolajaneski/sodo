<?php

namespace App\Action\Subscriber;

use App\Domain\Subscriber\Service\Subscriber;
use App\Responder\Responder;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class ConfirmAction
{
    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Subscriber $subscriber The service
     */
    public function __construct(Responder $responder, Subscriber $subscriber)
    {
        $this->responder = $responder;
        $this->subscriber = $subscriber;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data['emailHash'] = $request->getAttribute('emailHash');
        $data['hash'] = $request->getAttribute('hash');

        // Invoke the Domain with inputs and retain the result
        $subscriber = $this->subscriber->confirmSubscription($data);
        
        // Build the HTTP response
        return $this->responder
            ->withJson($response, ['description' => $subscriber == true ? 'Subscription confirmed.' : 'Subscriber not found.'])
            ->withStatus($subscriber == true ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_BAD_REQUEST);
    }
}
