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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function spl_object_hash;

#[ORM\Entity]
#[ORM\Table(name: 'customer')]
class Customer
{

    use IdentityTrait;

    #[ORM\Column(name: 'name', type: 'string', length: 64)]
    private ?string $name;

    /**
     * @var Collection<Address>
     */
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Address::class, cascade: ['all'])]
    private Collection $addresses;

    public function __construct(?string $name = null, array $addresses = [])
    {
        $this->id = null;
        $this->name = $name;
        $this->addresses = new ArrayCollection();
        $this->setAddresses($addresses);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Customer
     */
    public function setName(?string $name): Customer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the customer addresses. This will remove any existing address before setting the address.
     * To append to the existing list of address see Customer::addAddress.
     *
     * @param array $addresses
     * @return $this
     */
    public final function setAddresses(array $addresses): Customer
    {
        return $this->removeAddress(...$this->addresses->toArray())
            ->addAddress(...$addresses);
    }

    /**
     * Get the customer addresses
     * @return array
     */
    public function getAddresses(): array
    {
        return $this->addresses->getValues();
    }

    /**
     * Get the customer's billing address
     * @return array
     */
    public function getBillingAddresses(): array
    {
        return $this->addresses->filter(fn (Address $address) => $address instanceof BillingAddress)
            ->getValues();
    }

    /**
     * Get the customer's shipping addresses
     * @return array
     */
    public function getShippingAddresses(): array
    {
        return $this->addresses->filter(fn (Address $address) => $address instanceof ShippingAddress)
            ->getValues();
    }

    /**
     * Indicates whether a customer address exists or not
     *
     * @param Address $address
     * @return bool
     */
    public function hasAddress(Address $address): bool
    {
        return $this->addresses->contains($address);
    }

    /**
     * Adds an address to the list of customer address, if not already included.
     * @param Address ...$addresses
     * @return $this
     */
    public function addAddress(Address ...$addresses): self
    {
        foreach ($addresses as $address) {
            if (!$this->hasAddress($address)
                && $this->addresses->add($address)
                && $address->getCustomer() !== $this) {
                $address->setCustomer($this);
            }
        }
        return $this;
    }

    /**
     * Removes a customer address, if exists.
     * @param Address ...$addresses
     * @return $this
     */
    public function removeAddress(Address ...$addresses): self
    {
        foreach($addresses as $address) {
            if($this->hasAddress($address)
                && $this->addresses->removeElement($address)
                && $address->getCustomer() === $this) {
                $address->setCustomer();
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return spl_object_hash($this);
    }

}