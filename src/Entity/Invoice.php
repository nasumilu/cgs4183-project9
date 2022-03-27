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
use DateTimeInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoice')]
class Invoice
{

    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'string', length: 32)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: InvoiceIdGenerator::class)]
    private ?string $id;

    #[ORM\Column(name: 'invoice_date', type: 'date')]
    private DateTimeInterface $invoiceDate;

    #[ORM\ManyToOne(targetEntity: Customer::class, cascade: ['all'])]
    #[ORM\JoinColumn(name: 'customer', referencedColumnName: 'id', nullable: false)]
    private ?Customer $customer;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItem::class, cascade: ['all'])]
    private Collection $items;

    #[ORM\Embedded(class: InvoiceAddress::class, columnPrefix: 'billing_')]
    private InvoiceAddress $billingAddress;

    #[ORM\Embedded(class: InvoiceAddress::class, columnPrefix: 'shipping_')]
    private InvoiceAddress $shippingAddress;

    public function __construct(
        DateTimeInterface $invoiceDate = new DateTime(),
        ?Customer $customer = null,
        InvoiceAddress $billingAddress = new InvoiceAddress(),
        InvoiceAddress $shippingAddress = new InvoiceAddress(),
        array $items = [])
    {
        $this->id = null;
        $this->invoiceDate = $invoiceDate;
        $this->customer = $customer;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->items = new ArrayCollection();
        $this->setItems($items);
    }

    public function hasItem(InvoiceItem $item): bool
    {
        return $this->items->contains($item);
    }

    public final function setItems(array $items = []): Invoice
    {
        return $this->removeItem(...$this->items->toArray())
            ->addItem(...$items);
    }

    public function addItemForProduct(Product $product, int $quantity = 1) : Invoice
    {
        InvoiceItem::createFromProduct($this, $product, $quantity);
        return $this;
    }


    public function addItem(InvoiceItem ...$items): Invoice
    {
        foreach($items as $item) {
            if(!$this->hasItem($item)
                && $this->items->add($item)
                && $item->getInvoice() !== $this) {
                $item->setInvoice($this);
            }
        }
        return $this;
    }

    public function removeItem(InvoiceItem ...$items): Invoice
    {
        foreach($items as $item) {
            if($this->hasItem($item)
                && $this->items->removeElement($item)
                && $items->getInvoice() === $this) {
                $item->setInvoice();
            }
        }
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getInvoiceDate(): DateTimeInterface
    {
        return $this->invoiceDate;
    }

    /**
     * @param DateTimeInterface $invoiceDate
     * @return Invoice
     */
    public function setInvoiceDate(DateTimeInterface $invoiceDate): Invoice
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * @return Invoice
     */
    public function setCustomer(?Customer $customer = null): Invoice
    {
        $this->customer = $customer;
        return $this;
    }

    public function getTotal(): int
    {
        return array_reduce(
            $this->items->getValues(),
            fn(int $subtotal, InvoiceItem $item): int => $subtotal + $item->getTotal(),
            0
        );
    }

}