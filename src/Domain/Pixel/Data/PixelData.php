<?php

namespace App\Domain\Pixel\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class PixelData
{
    public ?int $id = null;

    public ?string $pixelType = null;
    
    public ?string $userId = null;
    
    public ?string $occuredOn = null;

    public ?bool $portalId = null;
    
   
    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [], $doi = false)
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->pixelType = $reader->findString('pixelType');
        $this->userId = $reader->findInt('userId');
        $this->occuredOn = $reader->findString('occuredOn');
        $this->portalId = $reader->findInt('portalId');


    }
}
