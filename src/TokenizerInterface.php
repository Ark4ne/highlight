<?php

namespace Highlight;

/**
 * Interface TokenizerInterface
 *
 * @package Highlight\Tokenizer
 */
interface TokenizerInterface
{
    const TOKEN_SPACE         = 'whitespace';
    const TOKEN_PUNCTUATION   = 'punctuation';
    const TOKEN_KEY           = 'keyword';
    const TOKEN_NAMESPACE     = 'namespace';
    const TOKEN_VAR           = 'variable';
    const TOKEN_OPERATOR      = 'operator';
    const TOKEN_STRING        = 'string';
    const TOKEN_INT           = 'int';
    const TOKEN_FUNCTION      = 'function';
    const TOKEN_COMMENT       = 'comment';
    const TOKEN_BLOCK_COMMENT = 'block_comment';

    public function tokenize(string $str): array;
}
