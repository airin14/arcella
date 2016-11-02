<?php

$iterator = Symfony\Component\Finder\Finder::create()
    ->files()
    ->name('*.php')
    ->in('src/arcella')
;

$options = array(
    'title'               => 'Arcella API',
    'theme'               => 'default',
    'build_dir'           => 'var/sami/build/',
    'cache_dir'           => 'var/sami/cache/',
    'include_parent_data' => false,
);

$sami = new Sami\Sami($iterator, $options);

return $sami;
