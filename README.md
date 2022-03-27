# nasumilu/cgs4183-project9

This is a quick example of object relational mapper using [doctrine/orm][1] and PHP 8.1.

# Install & Setup

```shell
$ git clone git@github.com:nasumilu/cgs4183-project9.git
$ cd cgs4183-project9
$ composer install
```

This was developed using the PostgreSQL database but should work for any database supported by the [doctrine/dbal][5]
project. To configure the database connection first open the `boostrap.php` file and around line #25 add a valid
connection url to the database. 

Included in the dev-dependencies is [doctrine/migrations][2], which is the easiest way to create the project's database. 
To do so use the following command:

```shell
$ vendor/bin/doctrine-migrations migrations:execute Nasumilu\\CGS4183\\Migrations\\Version20220326234445
```

Next run the test case which uses [phpunit/phpunit][3] and [doctrine/data-fixtures][4]. The single unit test will insert 
data and perform a query getting the total amount spent by each customer inserted.

```shell
$ cp phpunit.xml.dist phpunit.xml
$ vendor/bin/phpunit
PHPUnit 9.5.19

.                                                                   1 / 1 (100%)
Customer John Smith spent $329.98 dollars!
Customer Jane Doe spent $450.00 dollars!
Customer Billy Bob spent $49.95 dollars!

Time: 00:00.151, Memory: 6.00 MB

OK (1 test, 1 assertion)
```


## More Information

[Database ERD](./docs/erd.md)
[Object Model](./docs/obj_model.md)

[1]: https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/index.html
[2]: https://www.doctrine-project.org/projects/doctrine-migrations/en/3.3/index.html
[3]: https://phpunit.readthedocs.io/en/9.5/
[4]: https://www.doctrine-project.org/projects/doctrine-data-fixtures/en/latest/index.html
[5]: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/index.html