=================
Quality assurance
=================

Run the tests
=============

As Arcella uses PHPUnit for testing it's quite easy to run the tests. Just navigate to the project folder, e.g.
~/webroot/arcella and run PHPunit.

.. code-block:: bash

    cd ~/webroot/arcella
    php vendor/bin/phpunit


Validate the code
=================

To make sure that our code does not violate the Symfony coding standards we're using PHPCodeSniffer that automatically
detects violations to these coding standards.

.. code-block:: bash

    cd ~/webroot/arcella
    php vendor/bin/phpcs src -v --standard=Symfony2

If this command fails with an error, saying that the Symfony2 coding standard is not available than just add it with the
following command:

.. code-block:: bash
    php vendor/bin/phpcs --config-set installed_paths vendor/escapestudios/symfony2-coding-standard
