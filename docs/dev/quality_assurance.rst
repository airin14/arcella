=================
Quality assurance
=================

Make use of Gulp
================

Of cause you can run the tests and validate the code manually, but there is a also more elegant way: Arcella supports
`Gulp`_ with automates and enhances the workflow. And there are a lot of useful standard tasks included with Arcella.
The most simple way is just to execute the default task and then gulp will run all tests, validate the code and watch
if something changes. And if you make some changes to the code it will automatically run all the tests and validate all
the code again. Pretty awsome, isn't it?

.. code-block:: bash

    cd ~/webroot/arcella
    gulp


Run the tests
=============

As Arcella uses PHPUnit for testing it's quite easy to run the tests. Just navigate to the project folder, e.g.
~/webroot/arcella and run PHPunit.

.. code-block:: bash

    cd ~/webroot/arcella
    php vendor/bin/phpunit

Or do the same thing with Gulp:

.. code-block:: bash

    cd ~/webroot/arcella
    gulp tests


Validate the code
=================

To make sure that our code does not violate the Symfony coding standards we're using PHPCodeSniffer that automatically
detects violations to these coding standards.

.. code-block:: bash

    cd ~/webroot/arcella
    php vendor/bin/phpcs src -v --standard=Symfony2

Or make use of Gulp for this task:

.. code-block:: bash

    cd ~/webroot/arcella
    gulp validate

If this command fails with an error, saying that the Symfony2 coding standard is not available than just add it with the
following command:

.. code-block:: bash
    php vendor/bin/phpcs --config-set installed_paths vendor/escapestudios/symfony2-coding-standard


.. _Gulp: http://gulpjs.com/
