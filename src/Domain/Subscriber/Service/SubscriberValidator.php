<?php

namespace App\Domain\Subscriber\Service;

use App\Domain\Subscriber\Repository\SubscriberRepository;
use App\Factory\ValidationFactory;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class SubscriberValidator
{
    private SubscriberRepository $repository;

    private ValidationFactory $validationFactory;

    /**
     * The constructor.
     *
     * @param SubscriberRepository $repository The repository
     * @param ValidationFactory $validationFactory The validation
     */
    public function __construct(SubscriberRepository $repository, ValidationFactory $validationFactory)
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
    public function validateSubscriber(array $data): void
    {
        $validator = $this->createValidator();

        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($data)
        );

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your input', $validationResult);
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
            ->email('email', false, 'Input required')
            ->requirePresence('first_name', true, 'Input required')
            ->requirePresence('last_name', true, 'Input required');
    }
}
