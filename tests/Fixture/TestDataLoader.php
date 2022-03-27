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

namespace Nasumilu\CGS4183\Tests\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Nasumilu\CGS4183\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Nasumilu\CGS4183\Entity\BillingAddress;
use Nasumilu\CGS4183\Entity\Category;
use Nasumilu\CGS4183\Entity\Customer;
use Nasumilu\CGS4183\Entity\Invoice;
use Nasumilu\CGS4183\Entity\InvoiceAddress;
use Nasumilu\CGS4183\Entity\InvoiceItem;
use Nasumilu\CGS4183\Entity\MimeType;
use Nasumilu\CGS4183\Entity\Product;
use Nasumilu\CGS4183\Entity\ShippingAddress;

class TestDataLoader implements FixtureInterface
{


    public function load(ObjectManager $manager)
    {

        $categories = $this->categoryFixtures();
        $customers = $this->customersFixtures();

        foreach($categories as $category) {
            $manager->persist($category);
        }

        foreach($customers as $customer) {
            $manager->persist($customer);
        }

        $manager->flush();

        $invoices = $this->invoicesFixture($categories, $customers);

        foreach($invoices as $invoice) {
            $manager->persist($invoice);
        }
        $manager->flush();

    }

    private function invoicesFixture(array $categories, array $customers): array
    {
        return [
            new Invoice(
                invoiceDate: new \DateTime('2002-01-01'),
                customer: $customers[0],
                billingAddress: InvoiceAddress::fromAddress($customers[0]->getBillingAddresses()[0]),
                shippingAddress: InvoiceAddress::fromAddress($customers[0]->getShippingAddresses()[0]),
                items: [
                    InvoiceItem::createFromProduct($categories[0]->getProducts()[0]),
                    InvoiceItem::createFromProduct($categories[1]->getProducts()[1], 2),
                    InvoiceItem::createFromProduct($categories[0]->getProducts()[2])
                ]
            ),
            new Invoice(
                invoiceDate: new \DateTime('2010-05-10'),
                customer: $customers[1],
                billingAddress: InvoiceAddress::fromAddress($customers[1]->getBillingAddresses()[0]),
                shippingAddress: InvoiceAddress::fromAddress($customers[1]->getShippingAddresses()[0]),
                items: [
                    InvoiceItem::createFromProduct($categories[1]->getProducts()[1], 3)
                ]
            ),
            new Invoice(
                invoiceDate: new \DateTime('2002-01-01'),
                customer: $customers[2],
                billingAddress: InvoiceAddress::fromAddress($customers[2]->getBillingAddresses()[0]),
                shippingAddress: InvoiceAddress::fromAddress($customers[2]->getShippingAddresses()[0]),
                items: [
                    InvoiceItem::createFromProduct($categories[0]->getProducts()[2], 5)
                ]
            )
        ];
    }

    private function customersFixtures(): array
    {
        return [
            new Customer(
                name: 'John Smith',
                addresses: [
                    new ShippingAddress(
                        '101 N Main St',
                        'Some Town',
                        'FL',
                        'US',
                        '12345'
                    ),
                    new BillingAddress(
                        'PO BOX 12345',
                        'Some Town',
                        'FL',
                        'US',
                        '12345'
                    )
                ]
            ),
            new Customer(
                'Jane Doe',
                [
                    new ShippingAddress('123 Martin Luther King Blvd', 'Another Town', 'FL', 'US', '98745'),
                    new BillingAddress('123 Martin Luther King Blvd', 'Another Town', 'FL', 'US', '98745')
                ]
            ),
            new Customer(
                'Billy Bob',
                [
                    new ShippingAddress('456 Dusty Road', 'Big Country', 'OK', 'US', '14562-1235'),
                    new BillingAddress('456 Dusty Road', 'Big Country', 'OK', 'US', '14562-1235')
                ]
            ),
            new Customer(
                'Tiny Tim',
                [
                    new ShippingAddress('20014 NW 63rd Ct', 'Big City', 'NY', 'US', '45893-456'),
                    new BillingAddress('PO BOX 789', 'Big City', 'NY', 'US', '45893-456')
                ]
            )
        ];
    }

    private function categoryFixtures(): array
    {
        return [
            new Category(
                name: 'Category One',
                description: 'Test Category One Description',
                products: [
                    new Product(
                        1999,
                        name: 'C1:Product1',
                        description: 'Test Product One for Category One',
                        image: new Image(__DIR__.'/images/c1_product_one.png', MimeType::PNG)
                    ),
                    new Product(
                        amount: 2999,
                        name: 'C1:Product2',
                        description: 'Test Product Two for Category One',
                        image: new Image(__DIR__.'/images/c1_product_two.png', MimeType::PNG)
                    ),
                    new Product(
                        999,
                        name: 'C1:Product3',
                        description: 'Test Product Three for Category One',
                        image: new Image(__DIR__.'/images/c1_product_three.png', MimeType::PNG)
                    )
                ],
                image: new Image(path: __DIR__.'/images/category_one.png', mimeType: MimeType::PNG)
            ),
            new Category(
                name: 'Category Two',
                description: 'Test Category Two Description',
                products: [
                    new Product(
                        5999,
                        name: 'C2:Product1',
                        description: 'Test Product One for Category Two',
                        image: new Image(__DIR__.'/images/c2_product_one.png', MimeType::PNG)
                    ),
                    new Product(
                        amount: 15000,
                        name: 'C2:Product2',
                        description: 'Test Product Two for Category Two',
                        image: new Image(__DIR__.'/images/c2_product_two.png', MimeType::PNG)
                    ),
                ],
                image: new Image(__DIR__.'/images/category_two.png', MimeType::PNG)
            ),
            // no description or products just a plain old lonely category three
            new Category(
                'Category Three',
                image: new Image(__DIR__.'/images/category_three.png', MimeType::PNG))
        ];
    }
}