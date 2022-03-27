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

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class Category
{

    use IdentityTrait;
    use NamedDescriptionTrait;
    use ImageTrait;

    /**
     * @var Collection<Address>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class, cascade: ['all'])]
    private Collection $products;

    public function __construct(
        ?string $name = null,
        ?string $description = null,
        array $products = [],
        ?Image $image = null
    )
    {
        $this->id = null;
        $this->name = $name;
        $this->description = $description;
        $this->products = new ArrayCollection();
        $this->image = $image ?? new Image();
        $this->setProducts($products);
    }


    public function hasProduct(Product $product): bool
    {
        return $this->products->contains($product);
    }

    public function getProducts(): array
    {
        return $this->products->getValues();
    }

    public final function setProducts(array $products): Category
    {
        return $this->removeProduct(...$this->products->toArray())
            ->addProduct(...$products);
    }

    public function addProduct(Product ...$products): Category
    {
        foreach($products as $product) {
            if(!$this->hasProduct($product)
                && $this->products->add($product)
                && $product->getCategory() !== $this) {
                $product->setCategory($this);
            }
        }
        return $this;
    }

    public function removeProduct(Product ...$products): Category
    {
        foreach($products as $product) {
            if($this->hasProduct($product)
                && $this->products->removeElement($product)
                && $product->getCategory() === $this) {
                $product->setCategory();
            }
        }
        return $this;
    }


    public function __toString(): string
    {
        return spl_object_hash($this);
    }

}