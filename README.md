# Chexem (CHeck EXternal EMail)

Chexem (CHeck EXternal EMail) assigns a Flarum group to users whose verified email exists in an external database.

## Requirements

- Flarum 1.8
- Connection details to the external database.
- Access and write rights to the Flarum `config.php` file.
- Singular name of the Flarum group that should be used.

## Configuration

1. Include in the `config.php` file of your Flarum root folder the connection details to the external database, the name of the table, and the column with emails that would be checked after the Flarum user activates its account.

2. Include the singular name of the [Flarum group](https://docs.flarum.org/extend/permissions/#groups) that will be assigned if the email exists in the external database. The Flarum group must exists or the extension would not work. If you don't know, check the admin Permisions page of your Flarum admin section, or the `groups` table in your Flarum database. You can create or edit the group from the `Permissions` admin page as well.

For example, a PostgresSQL external database in the same server where Flarum forum is running. Change the values to adapt to your case.

```php
'external_database' =>
  array (
    'driver'    => 'pgsql',
    'host'      => '127.0.0.1',
    'port'      => '5432',
    'database'  => 'mypgsqldb',
    'username'  => 'myuser',
    'password'  => 'mysecret',
    'table'     => 'mytable',
    'column'    => 'emails',
    'flarum_group' => 'Outsider',
  ),
```

## Installation

Install the extension with composer.

```
$ composer require rodrom/chexem
```

4. Activate the extension on the admin section.

## License

Distributed under the MIT License. See <a href="https://github.com/rodrom/chexem/blob/main/LICENSE">`LICENSE`</a> for more information.


## Contact

[rodrom.eu](https://rodrom.eu) · dev@rodrom.eu

## Useful Links

- [Flarum](https://flarum.org)
- [GitHub](https://github.com/rodrom/chexem)
- [Composer](https://getcomposer.org/)
- [Packagist](https://packagist.org/)
