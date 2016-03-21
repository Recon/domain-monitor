# Website Monitor

### Installing

- Clone repo
- Run `composer install`
- Run `composer dump-autoload`
- Copy `propel.yml.dist` do `propel.yml`
- In `propel.yml`, edit the following entries with appropriate values:
    - `propel.database.connection.default.dsn`
    - `propel.database.connection.default.user`
    - `propel.database.connection.default.password`
- Run `vendor/bin/propel config:convert`
- Run `vendor/bin/propel migrate`