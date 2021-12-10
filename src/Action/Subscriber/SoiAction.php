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
final class SoiAction
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
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $subscriberId = $this->subscriber->subscribe($data);

        // Build the HTTP response
        return $this->responder
            ->withJson($response, ['description' => $subscriberId == true ? 'User subscribed' : 'User already exist'])
            ->withStatus($subscriberId == true ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_CONFLICT);
    }
}
