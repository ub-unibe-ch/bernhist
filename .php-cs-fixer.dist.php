<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('config')
    ->exclude('node_modules')
    ->exclude('vendor')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
