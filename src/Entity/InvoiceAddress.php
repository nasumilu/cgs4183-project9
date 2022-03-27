<?php
/*
 * Copyright 2022 Michael Lucas <nasumilu@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Nasumilu\CGS4183\Entity;

use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class InvoiceAddress
{

    use AddressTrait;

    public function __construct(
        ?string $street = null,
        ?string $city = null,
        ?string $region = null,
        ?string $country = null,
        ?string $postalCode = null)
    {
        $this->street = $street;
        $this->city = $city;
        $this->region = $region;
        $this->country = $country;
        $this->postalCode = $postalCode;

    }

    public static function fromAddress(Address $address): InvoiceAddress
    {
        return new self(
            $address->getStreet(),
            $address->getCity(),
            $address->getRegion(),
            $address->getCountry(),
            $address->getPostalCode()
        );
    }

}