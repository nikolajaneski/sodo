<?php

namespace App\Domain\Subscriber\Service;

use App\Domain\Subscriber\Data\SubscriberData;
use App\Domain\Subscriber\Repository\SubscriberRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class Subscriber
{
    private SubscriberRepository $repository;

    private SubscriberValidator $subscriberValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param SubscriberRepository $repository The repository
     * @param SubscriberValidator $subscriberValidator The validator
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(
        SubscriberRepository $repository,
        SubscriberValidator $subscriberValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->subscriberValidator = $subscriberValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('subscriber.log')
            ->createLogger();
    }

    /**
     * Register a new subscriber.
     *
     * @param array $data The form data
     *
     * @return int The new subscriber ID
     */
    public function subscribe(array $data, bool $doi = false): int
    {
        // Input validation
        $this->subscriberValidator->validateSubscriber($data);

        // Map form data to subscriber DTO (model)
        $subscriber = new SubscriberData($data, $doi);

        // Register subscriber and get new subscriber ID
        $subscriberId = $this->repository->register($subscriber);

        // Logging
        $this->logger->info($subscriberId == true ? sprintf('User subscribed: %s', $subscriberId) : sprintf('User already exists'));

        return $subscriberId;
    }

    public function confirmSubscription(array $data): bool
    {
        // Register subscriber and get new subscriber ID
        $subscriber = $this->repository->getSubscriberByHash($data);
        if(!$subscriber) {
            return false;
        }

        $this->repository->confirmSubscription($subscriber);

        // Logging
        $this->logger->info(sprintf('User subscription confirmed: %s', $subscriber->email));

        return true;
    }
}
