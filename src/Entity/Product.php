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
#[ORM\Table(name: 'product')]
class Product
{

    use IdentityTrait;
    use NamedDescriptionTrait;
    use ImageTrait;

    #[ORM\Column(name: 'amount', type: 'integer')]
    private int $amount;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['all'], inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category', referencedColumnName: 'id', nullable: false)]
    private ?Category $category;

    public function __construct(
        int $amount = 0,
        ?Category $category = null,
        ?string $name = null,
        ?string $description = null,
        ?Image $image = null
    )
    {
        $this->id = null;
        $this->amount = $amount;
        $this->category = $category;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image ?? new Image();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount = 0) : Product
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return Product
     */
    public function setCategory(?Category $category = null): Product
    {
        if($this->category?->hasProduct($this)) {
            $this->category?->removeProduct($this);
        }
        $this->category = $category;

        if(!$this->category?->hasProduct($this)) {
            $this->category?->addProduct($this);
        }
        return $this;
    }
}