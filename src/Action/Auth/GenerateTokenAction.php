<?php

namespace App\Action\Auth;

use App\Domain\Auth\Service\GenerateJWT;
use App\Domain\Subscriber\Service\ConfirmEmail;
use App\Responder\Responder;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Firebase\JWT\JWT;
/**
 * Action.
 */
final class GenerateTokenAction
{
    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Subscriber $subscriber The service
     * @param GenerateJWT $generateJWT Token generator
     */
    public function __construct(Responder $responder, ConfirmEmail $email, GenerateJWT $generateJWT)
    {
        $this->responder = $responder;
        $this->email = $email;
        $this->generateJWT = $generateJWT;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = (array)$request->getParsedBody();

        $token = $this->generateJWT->generate($data['client']);

        return $this->responder
            ->withJson($response, ['token' => $token])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
