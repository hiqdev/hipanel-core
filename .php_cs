<?php

$header = <<<EOF
HiPanel core package

@link      https://hipanel.com/
@package   hipanel-core
@license   BSD-3-Clause
@copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
EOF;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'header_comment'                             => ['header' => $header], /// Add, replace or remove header comment
        'long_array_syntax'                          => false, /// Arrays should use the long syntax
        'php4_constructor'                           => false, /// Convert PHP4-style constructors to __construct. Warning! This could change code behavior
        'phpdoc_var_to_type'                         => false, /// @var should always be written as @type
        'align_double_arrow'                         => false, /// Align double arrow symbols in consecutive lines
        'unalign_double_arrow'                       => false, /// Unalign double arrow symbols in consecutive lines
        'align_equals'                               => false, /// Align equals symbols in consecutive lines
        'unalign_equals'                             => false, /// Unalign equals symbols in consecutive lines
        'phpdoc_no_empty_return'                     => false, /// @return void and @return null annotations should be omitted from phpdocs
        'simplified_null_return'                     => false, /// A return statement wishing to return nothing should be simply "return"
        'blank_line_before_return'                   => false, /// n empty line feed should precede a return statement
        'phpdoc_align'                               => false, /// All items of the @param, @throws, @return, @var, and @type phpdoc tags must be aligned vertically
        'phpdoc_params'                              => false, /// All items of the @param, @throws, @return, @var, and @type phpdoc tags must be aligned vertically
        'phpdoc_scalar'                              => false, /// Scalar types should always be written in the same form. "int", not "integer"; "bool", not "boolean"
        'phpdoc_separation'                          => false, /// Annotations of a different type are separated by a single blank line
        'phpdoc_to_comment'                          => false, /// Docblocks should only be used on structural elements
        'method_argument_space'                      => false, /// In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma
        'concat_without_spaces'                      => false, /// Concatenation should be used without spaces
        'concat_with_spaces'                         =>  true, /// Concatenation should be used with at least one whitespace around
        'ereg_to_preg'                               =>  true, /// Replace deprecated ereg regular expression functions with preg. Warning! This could change code behavior
        'blank_line_after_opening_tag'               =>  true, /// Ensure there is no code on the same line as the PHP open tag and it is followed by a blankline
        'single_blank_line_before_namespace'         =>  true, /// There should be no blank lines before a namespace declaration
        'ordered_imports'                            =>  true, /// Ordering use statements
        'phpdoc_order'                               =>  true, /// Annotations in phpdocs should be ordered so that @param come first, then @throws, then @return
        'pre_increment'                              =>  true, /// Pre incrementation/decrementation should be used if possible
        'short_array_syntax'                         =>  true, /// PHP arrays should use the PHP 5.4 short-syntax
        'strict_comparison'                          =>  true, /// Comparison should be strict. (Risky fixer!)
        'strict_param'                               =>  true, /// Functions should be used with $strict param. Warning! This could change code behavior
        'no_multiline_whitespace_before_semicolons'  =>  true, /// Multi-line whitespace before closing semicolon are prohibited
    ))
    ->finder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->notPath('vendor')
            ->notPath('runtime')
            ->notPath('web/assets')
        )
;
