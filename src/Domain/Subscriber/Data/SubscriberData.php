<?php

namespace App\Domain\Subscriber\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class SubscriberData
{
    public ?int $id = null;

    public ?string $firstName = null;
    
    public ?string $lastName = null;
    
    public ?string $email = null;

    public ?bool $confirmed = null;
    
    public ?string $hash = null;

    public ?string $emailHash = null;



    
    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [], $doi = false)
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->firstName = $reader->findString('first_name');
        $this->lastName = $reader->findString('last_name');
        $this->email = $reader->findString('email');
        $this->confirmed = $doi == true ? false : true;
        $this->hash = $reader->findString('hash');
        $this->emailHash = $reader->findString('emailHash');



    }
}
