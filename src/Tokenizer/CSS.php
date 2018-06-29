<?php

namespace Highlight\Tokenizer;

use Highlight\TokenizerInterface;

/**
 * Class CSS
 *
 * @package Highlight\Tokenizer
 */
class CSS implements TokenizerInterface
{
    const _x_whitespace = '(\s+)';

    const _x_number = '(\d+)';

    const _x_str = '("[\w\W]*"|\'[\w\W]*\')';

    const _x_operator = '([\~|&<>+-]*=)';

    const _x_combinator = '([+>\~])';

    const _x_key_punc = '([,;])';

    const _x_punc = '([{}:])';

    const _x_identifier = '([a-z][\\w-]*)';

    const _x_id_class = '(#|[.])(' . self::_x_identifier . ')';

    const _x_attr = '\['
    . self::_x_whitespace . '?' . self::_x_identifier
    . self::_x_whitespace . '?' . '(?:' .self::_x_operator . '(?:' . self::_x_identifier . '|' . self::_x_number . '|' . self::_x_str . '))?'
    . self::_x_whitespace . '?' . '\]';

    const _x_pseudo = ':' . self::_x_identifier . '(?:' . self::_x_whitespace  . '?' . '(\([\w\W]*\)))?';

    const _x_xeywords = '([@!])' . self::_x_identifier;

    const _x_variable = '\$' . self::_x_identifier;

    const _x_interpolate = '(#\{)' . self::_x_whitespace . '?' . self::_x_identifier . self::_x_whitespace . '?' . '(\})';

    const _x_definition = self::_x_identifier . self::_x_whitespace . '?' . ':([\w\W]+);';

    const _x_function = self::_x_identifier . self::_x_whitespace . '?' . '(\([\w\W]*\))';

    private function parse(string $str)
    {
        $regex = [
            'space'       => self::_x_whitespace,
            'kpunc'       => self::_x_key_punc,
            'punc'        => self::_x_punc,
            'xeyword'     => self::_x_xeywords,
            'variable'    => self::_x_variable,
            'interpolate' => self::_x_interpolate,
            'definition'  => self::_x_definition,
            'function'    => self::_x_function,
        ];
    }

    public function tokenize(string $str): array
    {
        return self::parse($str);
    }
}