<?php

namespace App\Action\Subscriber;

use App\Domain\Subscriber\Service\Subscriber;
use App\Domain\Subscriber\Service\ConfirmEmail;
use App\Responder\Responder;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class DoiAction
{
    private Responder $responder;

    private Subscriber $subscriber;

    private ConfirmEmail $email;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Subscriber $subscriber The service
     */
    public function __construct(Responder $responder, Subscriber $subscriber, ConfirmEmail $email)
    {
        $this->responder = $responder;
        $this->subscriber = $subscriber;
        $this->email = $email;

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

        $hash = md5(rand(0,100000)); 
        $data['hash'] = $hash;
        $data['emailHash'] = md5($data['email']);

        // Invoke the Domain with inputs and retain the result
        $subscriberId = $this->subscriber->subscribe($data, true);

        if($subscriberId)
            $this->email->sendConfirmationEmail($data);
        
        // Build the HTTP response
        return $this->responder
            ->withJson($response, ['description' => $subscriberId == true ? 'User subscribed' : 'User already exist'])
            ->withStatus($subscriberId == true ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_CONFLICT);
    }
}
