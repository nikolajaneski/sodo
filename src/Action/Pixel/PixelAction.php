<?php

namespace App\Action\Pixel;

use App\Domain\Pixel\Service\Pixel;
use App\Responder\Responder;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class PixelAction
{
    private Responder $responder;

    private Pixel $pixel;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Pixel $pixel The service
     */
    public function __construct(Responder $responder, Pixel $pixel)
    {
        $this->responder = $responder;
        $this->pixel = $pixel;
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
        $pixelId = $this->pixel->trackPixel($data);

        // Build the HTTP response
        return $this->responder
            ->withJson($response, ['description' => $pixelId == true ? 'Pixel saved.' : 'Item already exists.'])
            ->withStatus($pixelId == true ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_CONFLICT );
    }
}
