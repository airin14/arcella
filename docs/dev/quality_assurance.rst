Quality assurance
*****************

Validate the Code
=================

To make sure that our code does not violate the Symfony coding standards we're using PHPCodeSniffer that automatically detects violations to these coding standards.

    $ cd ~/webroot/arcella
    $ php vendor/bin/phpcs src -v --standard=Symfony2
