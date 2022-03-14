<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.7.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,
        // Class, trait and interface elements must be separated with one or none blank line.
        'class_attributes_separation' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('api.ignore')
        ->exclude('backend/domain/Layout/Builder.ignore')
        ->in('app')
        ->in('tests')
    )
;
