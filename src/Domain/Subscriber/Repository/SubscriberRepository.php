<?php

namespace App\Domain\Subscriber\Repository;

use App\Domain\Subscriber\Data\SubscriberData;
use App\Factory\QueryFactory;
use DomainException;

/**
 * Repository.
 */
final class SubscriberRepository
{
    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Insert user row.
     *
     * @param SubscriberData $subscriber The subscriber data
     *
     * @return int The new ID
     */
    public function register(SubscriberData $subscriber): int
    {
        if($this->checkIfSubscriberExists($this->toRow($subscriber)))
            return false;

        return (int)$this->queryFactory->newInsert('subscriber', $this->toRow($subscriber))
            ->execute()
            ->lastInsertId();
    }

    public function checkIfSubscriberExists(array $data): bool
    {
        $query = $this->queryFactory->newSelect('subscriber');
        $query->select(
            [
                'id'
            ]
        );

        $query->andWhere(['email' => $data['email']]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            return false;
        }

        return true;
    }


    public function getSubscriberByHash(array $data): SubscriberData
    {
        $query = $this->queryFactory->newSelect('subscriber');
        $query->select(
            [
                'id',
                'first_name',
                'last_name',
                'email',
                'hash', 
                'emailHash'
            ]
        );

        $query->andWhere(['hash' => $data['hash']]);
        $query->andWhere(['emailHash' => $data['emailHash']]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('Subscriber not found: %s'));
        }

        return new SubscriberData($row);
    }

    public function confirmSubscription(SubscriberData $subscriber)
    {
        $row = $this->toRow($subscriber);

        // Updating the password is another use case
        $row['confirmed'] = true;

        $this->queryFactory->newUpdate('subscriber', $row)
            ->andWhere(['id' => $subscriber->id])
            ->execute();
    }

    /**
     * Convert to array.
     *
     * @param SubscriberData $subscriber The subscriber data
     *
     * @return array The array
     */
    private function toRow(SubscriberData $subscriber): array
    {
        return [
            'id' => $subscriber->id,
            'first_name' => $subscriber->firstName,
            'last_name' => $subscriber->lastName,
            'email' => $subscriber->email,
            'confirmed' => $subscriber->confirmed,
            'hash' => $subscriber->hash,
            'emailHash' => $subscriber->emailHash,
        ];
    }
}
