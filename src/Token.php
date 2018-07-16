<?php

namespace Highlight;

/**
 * Class Token
 *
 * @package Highlight
 */
class Token
{
    const TOKEN_WHITESPACE    = 'whitespace';
    const TOKEN_WORD          = 'word';
    const TOKEN_PUNCTUATION   = 'punctuation';
    const TOKEN_KEYWORD       = 'keyword';
    const TOKEN_NAMESPACE     = 'namespace';
    const TOKEN_VARIABLE      = 'variable';
    const TOKEN_OPERATOR      = 'operator';
    const TOKEN_STRING        = 'string';
    const TOKEN_NUMBER        = 'number';
    const TOKEN_FUNCTION      = 'function';
    const TOKEN_COMMENT       = 'comment';
    const TOKEN_BLOCK_COMMENT = 'block_comment';
}
