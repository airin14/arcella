Arcella
=======

Arcella is yet another symfony3 based content management system. 

The web is all about publishing and distributing content. Unfortunately most of the time you end up fighting against a content management system, rather than enjoying your content. The idea behind Arcella is to provide an easy to use environment that allows the user to focus on publishing and distributing their content. 

# Requirements

- Webserver (Apache, Nginx, LiteSpeed, IIS, etc.) with PHP 5.6 or higher
- MySQL version 5.6 or higher

# Installation

## From GitHub
	
1. Launch a **terminal** or **console** and navigate to the webroot folder. Clone the Arcella repository from [https://github.com/nplhse/Arcella]() to a folder in the webroot of your server, e.g. `~/webroot/arcella`. 

    ```
    $ cd ~/webroot
    $ git clone https://github.com/nplhse/Arcella.git
    ```
       
2. Install the vendor bundles by using [Composer](https://getcomposer.org/):

    ```
    $ cd ~/webroot/arcella
    $ composer install
    ```
    
3. Setup the database

    ```
    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:schema:create
    ```
    
4. And finally you might want to populate the database with some (fake) data, but this is an totally optional step
 
    ```
    $ php bin/console hautelook_alice:doctrine:fixtures:load
    ```

# Contributing

See [CONTRIBUTING](CONTRIBUTING.md).

## Run the Tests

As Arcella uses [PHPUnit](https://phpunit.de/) for testing it's quite easy to run the tests. Just navigate to the project folder, e.g. `~/webroot/arcella` and run PHPunit.

    $ cd ~/webroot/arcella
    $ php bin/phpunit

# License

See [LICENSE](LICENSE.md).
