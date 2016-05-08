Getting started with Arcella: Installation
******************************************

From GitHub
===========

Launch a terminal or console and navigate to the webroot folder. Clone the Arcella repository from https://github.com/nplhse/Arcella to a folder in the webroot of your server, e.g. ~/webroot/arcella.

$ cd ~/webroot
$ git clone https://github.com/nplhse/Arcella.git

Install the vendor bundles by using Composer:

$ cd ~/webroot/arcella
$ composer install

Setup the database

$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:create

And finally you might want to populate the database with some (fake) data, but this is an totally optional step

$ php bin/console hautelook_alice:doctrine:fixtures:load
