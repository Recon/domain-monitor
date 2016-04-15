# Domain Monitor

[![build status](https://gitlab.com/corneliupr/domain-monitor/badges/master/build.svg)](https://gitlab.com/corneliupr/domain-monitor/commits/master)

## Online documentation: 

[docs.domain-monitor.corneliupr.com/0.1/](http://docs.domain-monitor.corneliupr.com/0.1/)

## Online demo:

[demo.domain-monitor.corneliupr.com/](http://demo.domain-monitor.corneliupr.com/)

----

### Installing for production use

Refer to [docs/installation.html entry](https://docs.domain-monitor.corneliupr.com/0.1/installation.html/docs/installation.html)

### Installing a development copy

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

### Issues

This project is hosted on [gitlab.com/corneliupr/website-monitor](https://gitlab.com/corneliupr/website-monitor)

---

[corneliupr.com](https://corneliupr.com)
