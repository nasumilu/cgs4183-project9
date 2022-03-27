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

namespace Nasumilu\CGS4183\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Nasumilu\CGS4183\Entity\Category;
use Nasumilu\CGS4183\Entity\Customer;
use Nasumilu\CGS4183\Entity\Invoice;
use Nasumilu\CGS4183\Entity\InvoiceItem;
use Nasumilu\CGS4183\Entity\Product;
use Nasumilu\CGS4183\Tests\Fixture\TestDataLoader;
use PHPUnit\Framework\TestCase;

class InvoiceTotalTest extends TestCase
{

    private static ORMExecutor $executor;
    private static EntityManagerInterface $em;

    public static function setUpBeforeClass(): void
    {

        $loader = new Loader();
        $loader->addFixture(new TestDataLoader());
        self::$em = require_once __DIR__.'/../bootstrap.php';
        self::$executor = new ORMExecutor(self::$em, new ORMPurger());
        self::$executor->execute($loader->getFixtures());
    }

    /**
     * @test
     * @return void
     */
    public function total(): void  {
        $invoices = self::$em->getRepository(Invoice::class)->findAll();
        $totalGroupedByCustomer = array_map(fn(Invoice $invoice): array => [$invoice->getCustomer()->getName(), $invoice->getTotal()], $invoices);
        foreach($totalGroupedByCustomer as $total) {
            echo sprintf("\nCustomer %s spent $%0.2f dollars!", $total[0], $total[1] / 100);
        }

        $this->assertNotNull($invoices);
    }
}