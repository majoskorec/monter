<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'array_syntax'      => [
            'syntax' => 'short',
        ],
        'no_useless_else'   => true,
        'no_useless_return' => true,
        'strict_comparison' => true,
        'strict_param'      => true,
        'no_unused_imports' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('tests')
            ->exclude('var')
            ->in(__DIR__)
    );
