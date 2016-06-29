Installation
************

From GitHub
===========

1. Launch a terminal or console and navigate to the webroot folder. Clone the Arcella repository from
   https://github.com/nplhse/Arcella to a folder in the webroot of your server, e.g. ~/webroot/arcella.

.. code-block:: bash

    cd ~/webroot
    git clone https://github.com/nplhse/Arcella.git

2. Install the vendor bundles by using Composer:

.. code-block:: bash

    cd ~/webroot/arcella
    composer install

3. Setup the database

.. code-block:: bash

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:create

4. Install all the node bundles by using npm

.. code-block:: bash

    npm install

5. And finally you might want to populate the database with some (fake) data, but this is an totally optional step

.. code-block:: bash

    php bin/console doctrine:fixtures:load
