<?php

declare(strict_types=1);

namespace App\Dto\Workshop;

use App\Entity\Locality;
use App\Entity\Voivodeship;
use App\Validator\Constraints as AppAssert;

#[AppAssert\UniqueWorkshopUrl]
class CreateWorkshopDto
{
    public string $name;
    public string $email;
    public string $street;
    public string $postalCode;
    public Voivodeship $region;
    public Locality $city;
}
