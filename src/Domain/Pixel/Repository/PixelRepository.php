<?php

namespace App\Domain\Pixel\Repository;

use App\Domain\Pixel\Data\PixelData;
use App\Factory\QueryFactory;
use DomainException;

/**
 * Repository.
 */
final class PixelRepository
{
    private QueryFactory $queryFactory;

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
     * @param PixelData $pixel The pixel data
     *
     * @return int The new ID
     */
    public function create(PixelData $pixel): int
    {
        if($this->existPixel($pixel)) 
            return false;

        return (int)$this->queryFactory->newInsert('pixel', $this->toRow($pixel))
            ->execute()
            ->lastInsertId();
    }

    public function existPixel(PixelData $pixel): bool
    {
        $query = $this->queryFactory->newSelect('pixel');
        $query->select('userId')->andWhere(['userId' => $pixel->userId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    /**
     * Convert to array.
     *
     * @param PixelData $pixel The pixel data
     *
     * @return array The array
     */
    private function toRow(PixelData $pixel): array
    {
        return [
            'id' => $pixel->id,
            'pixelType' => $pixel->pixelType,
            'userId' => $pixel->userId,
            'occuredOn' => $pixel->occuredOn,
            'portalId' => $pixel->portalId,
        ];
    }
}
