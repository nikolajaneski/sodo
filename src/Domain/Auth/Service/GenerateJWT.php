<?php

namespace App\Domain\Auth\Service;

use App\Domain\Pixel\Data\PixelData;
use App\Interfaces\PixelInterface;
use App\Domain\Pixel\Repository\PixelRepository;
use App\Factory\LoggerFactory;
use App\Interfaces\GenerateTokenInterface;
use Psr\Log\LoggerInterface;
use \Firebase\JWT\JWT;


/**
 * Service.
 */
final class GenerateJWT
{    /**
     * The constructor.
     *
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(

        LoggerFactory $loggerFactory
    ) {
        $this->logger = $loggerFactory
            ->addFileHandler('token.log')
            ->createLogger();
    }

    /**
     * Track new pixel data.
     *
     * @param array $data The form data
     *
     * @return int The new pixel ID
     */
    public function generate($client): string
    {
        $now = time();
        $secret = "verypersonalsecret";

        $payload = [
          "jti" => $client,
          "iat" => $now,
          "exp" => strtotime('+1 hour',$now)
        ];
        $this->logger->info(sprintf('Token generated for %s', $client));

        return JWT::encode($payload,$secret,"HS256");
    }
}
