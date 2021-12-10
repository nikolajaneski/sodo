<?php

namespace App\Domain\Pixel\Service;

use App\Domain\Pixel\Repository\PixelRepository;
use App\Factory\ValidationFactory;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class PixelValidator
{
    private PixelRepository $repository;

    private ValidationFactory $validationFactory;

    /**
     * The constructor.
     *
     * @param PixelRepository $repository The repository
     * @param ValidationFactory $validationFactory The validation
     */
    public function __construct(PixelRepository $repository, ValidationFactory $validationFactory)
    {
        $this->repository = $repository;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Validate new subscriber.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validatePixel(array $data): void
    {
        // die(var_dump($data));
        $validator = $this->createValidator();

        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($data)
        );

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your inputs', $validationResult);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        
        $validator = $this->validationFactory->createValidator();
        
        return $validator
                ->inList('pixelType', ['DOI', 'SOI'], 'Invalid pixel type')
                ->requirePresence('userId', true, 'userId is required')
                ->requirePresence('occuredOn', true, 'occuredOn is required')
                ->requirePresence('portalId', true, 'portalId is required'); 
    }
}
