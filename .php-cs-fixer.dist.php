<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['var', 'tests/Support/_generated']);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);
