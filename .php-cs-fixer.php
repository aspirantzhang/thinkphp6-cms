<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.7.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@Symfony' => true,
        '@PHP81Migration' => true,
        'array_indentation' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'method_chaining_indentation' => true,
        'multiline_comment_opening_closing' => true,
        'types_spaces' => ['space'=>'single'],
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->in(__DIR__)
    )
;
