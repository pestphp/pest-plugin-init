<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'src')
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'stubs')
    ->append(['.php_cs']);

$rules = [
    '@Symfony'               => true,
    'phpdoc_no_empty_return' => false,
    'array_syntax'           => ['syntax' => 'short'],
    'yoda_style'             => false,
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'align',
            '='  => 'align',
        ],
    ],
    'concat_space'            => ['spacing' => 'one'],
    'not_operator_with_space' => false,
];

$rules['increment_style'] = ['style' => 'post'];

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules($rules)
    ->setFinder($finder);
