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

#[ORM\Entity]
#[ORM\Table(name: 'invoice_item')]
class InvoiceItem
{

    use IdentityTrait;

    #[ORM\Column(name: 'product', type: 'string', length: 32)]
    private ?string $product;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(name: 'quantity', type: 'integer')]
    private int $quantity;

    #[ORM\Column(name: 'unit_amount', type: 'integer')]
    private int $unitAmount;

    #[ORM\ManyToOne(targetEntity: Invoice::class, cascade: ['all'], inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'invoice', referencedColumnName: 'id', nullable: false)]
    private ?Invoice $invoice;

    public function __construct(
        ?Invoice $invoice = null,
        ?string $product = null,
        ?string $description = null,
        ?int $quantity = 0,
        ?int $unitAmount = 0)
    {
        $this->invoice = $invoice;
        $this->product = $product;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitAmount = $unitAmount;
    }

    public static function createFromProduct(Product $product, int $quantity = 1) : InvoiceItem
    {
        return new InvoiceItem(
            product: $product->getName(),
            description: $product->getDescription(),
            quantity: $quantity,
            unitAmount: $product->getAmount()
        );
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @param string|null $product
     * @return InvoiceItem
     */
    public function setProduct(?string $product): InvoiceItem
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return InvoiceItem
     */
    public function setDescription(?string $description): InvoiceItem
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @return InvoiceItem
     */
    public function setQuantity(?int $quantity): InvoiceItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUnitAmount(): ?int
    {
        return $this->unitAmount;
    }

    /**
     * @param int|null $unitAmount
     * @return InvoiceItem
     */
    public function setUnitAmount(?int $unitAmount): InvoiceItem
    {
        $this->unitAmount = $unitAmount;
        return $this;
    }

    /**
     * Calculate the invoice line item's total
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->unitAmount * $this->quantity;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @param Invoice|null $invoice
     * @return InvoiceItem
     */
    public function setInvoice(?Invoice $invoice = null): InvoiceItem
    {
        if($this->invoice?->hasItem($this)) {
            $this->invoice?->removeItem($this);
        }
        $this->invoice = $invoice;

        if(!$this->invoice?->hasItem($this)) {
            $this->invoice?->addItem($this);
        }

        return $this;
    }
}