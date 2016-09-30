<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('src/arcella')
;

return new Sami($iterator, array(
    'title'               => 'Arcella API',
    'theme'               => 'default',
    'build_dir'           => 'var/sami/build/',
    'cache_dir'           => 'var/sami/cache/',
    'include_parent_data' => false,
));
