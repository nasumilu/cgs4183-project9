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

use Doctrine\ORM\Mapping as ORM;
use function spl_object_hash;

#[ORM\Entity]
#[ORM\Table(name: 'address')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'address_type', type: 'string', length: 8)]
#[ORM\DiscriminatorMap([
    'billing' => BillingAddress::class,
    'shipping' => ShippingAddress::class
])]
abstract class Address
{

    use IdentityTrait;
    use AddressTrait;

    #[ORM\ManyToOne(targetEntity: Customer::class, cascade: ['all'], inversedBy: 'addresses')]
    #[ORM\JoinColumn(name: 'customer', referencedColumnName: 'id', nullable: false)]
    private ?Customer $customer;

    public function __construct(
        ?string $street = null,
        ?string $city = null,
        ?string $region = null,
        ?string $country = null,
        ?string $postalCode = null,
        ?Customer $customer = null)
    {
        $this->id = null;
        $this->street = $street;
        $this->city = $city;
        $this->region = $region;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->customer = $customer;

    }


    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return Address
     */
    public function setCustomer(?Customer $customer): Address
    {
        if($customer?->hasAddress($this)) {
            $customer->removeAddress($this);
        }
        $this->customer = $customer;

        if(!$this->customer?->hasAddress($this)) {
            $this->customer->addAddress($this);
        }
        return $this;
    }

    public function __toString(): string
    {
        return spl_object_hash($this);
    }

}