<?php

require __DIR__ . '/../vendor/symfony-classloader/UniversalClassLoader.php';

$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader;
$loader->registerNamespaces(array(
    'Frosas' => __DIR__ . '/../src',
    'Zend' => __DIR__ . '/../vendor/zend-framework-2/library'
));
$loader->register();