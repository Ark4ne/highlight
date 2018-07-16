<?php

namespace Highlight;

/**
 * Interface TokenizerInterface
 *
 * @package Highlight\Tokenizer
 */
interface LanguageInterface
{
    /**
     * LanguageInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []);

    /**
     * Tokenize a string
     *
     * @param string $str
     *
     * @return array
     */
    public function tokenize($str);

    /**
     * Format tokens
     *
     * @param array $tokens
     *
     * @return array
     */
    public function format(array $tokens);
}
