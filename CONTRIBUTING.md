How to contribute
=================

Any contribution to Arcella is appreciated, whether it is related to fixing bugs, suggestions or improvements. Feel free to take your part in the development of Arcella!

However you should follow these simple guidelines for your contribution to be properly recived:

* Arcella uses the [GitFlow branching model](http://nvie.com/posts/a-successful-git-branching-model/) for the process from development to release. 
* Because of GitFlow we accept contributions only via pull requests on [Github](https://github.com/nplhse/arcella).
* Please keep in mind, that Arcella tries to follow [SemVer v2.0.0](http://semver.org/).
* In order to foster an inclusive, kind, harassment-free, and cooperative community, you should follow our [Code of Conduct](CODE_OF_CONDUCT.md).
* Also you should make sure to follow the [PHP Standards Recommendations](http://www.php-fig.org/psr/) and the [Symfony best practices](http://symfony.com/doc/current/best_practices/index.html).

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
    $ php bin/console doctrine:fixtures:load
    ```

# Contributing

## Pull requests

To make a long story short you should at first fork and install Arcella from this repo. Now you make sure all the tests pass. Make your changes to the code and add tests for your changes. If all the tests pass push to your fork and submit a pull request to the `development` branch.

* **Add tests** - None of your code will be accepted if it doesn't have proper tests.
* **Stick to the standards** - Make sure to follow the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html).
* **Document any change in behaviour** - Make sure the [Readme](README.md) and any other relevant documentation are kept up-to-date.
* **Create feature branches** - Don't ever ask us to pull from your master branch.
* **One pull request per feature** - If you want to contribute more than one thing, please send multiple pull requests.
* **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Run the Tests

As Arcella uses [PHPUnit](https://phpunit.de/) for testing it's quite easy to run the tests. Just navigate to the project folder, e.g. `~/webroot/arcella` and run PHPunit.

    $ cd ~/webroot/arcella
    $ php vendor/bin/phpunit
    
## Validate the Code

To make sure that our code does not violate the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html) we're using [PHPCodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) that automatically detects violations to these coding standards.

    $ cd ~/webroot/arcella
    $ php vendor/bin/phpcs src -v --standard=Symfony2