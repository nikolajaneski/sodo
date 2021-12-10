<?php

namespace App\Domain\Pixel\Service;

use App\Domain\Pixel\Data\PixelData;
use App\Interfaces\PixelInterface;
use App\Domain\Pixel\Repository\PixelRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class Pixel implements PixelInterface
{    /**
     * The constructor.
     *
     * @param PixelRepository $repository The repository
     * @param PixelValidator $pixelValidator The validator
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(
        PixelRepository $repository,
        PixelValidator $pixelValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->pixelValidator = $pixelValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('pixel.log')
            ->createLogger();
    }

    /**
     * Track new pixel data.
     *
     * @param array $data The form data
     *
     * @return int The new pixel ID
     */
    public function trackPixel(array $data): int
    {
        // Input validation
        $this->pixelValidator->validatePixel($data);

        // Map form data to pixel DTO (model)
        $pixel = new PixelData($data);

        // Create pixel
        $pixelId = $this->repository->create($pixel);

        // Logging
        $this->logger->info($pixelId == true ? sprintf('Pixel tracked: %s', $pixelId) : sprintf('Pixel already exist'));

        return $pixelId;
    }
}
