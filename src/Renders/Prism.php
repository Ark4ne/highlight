<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\Token;

/**
 * Class Prism
 *
 * @package Highlight\Renders
 */
class Prism implements RenderInterface
{
    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Render an list of tokens
     *
     * @param array $tokens
     *
     * @return string
     */
    public function render(array $tokens)
    {
        $str = '';

        foreach ($tokens as $token) {
            switch ($token['type']) {
                case Token::TOKEN_WHITESPACE:
                    $str .= $token['value'];
                    break;
                case Token::TOKEN_OPERATOR:
                case Token::TOKEN_VARIABLE:
                case Token::TOKEN_KEYWORD:
                case Token::TOKEN_FUNCTION:
                case Token::TOKEN_STRING:
                case Token::TOKEN_NUMBER:
                case Token::TOKEN_PUNCTUATION:
                case Token::TOKEN_NAMESPACE:
                case Token::TOKEN_COMMENT:
                case Token::TOKEN_BLOCK_COMMENT:
                default:
                    $class = isset($this->options['classes'][$token['type']])
                        ? $this->options['classes'][$token['type']]
                        : $token['type'];

                    $str .= '<code class="token ' . $class . '">' . $token['value'] . '</code>';
            }
        }

        return $str;
    }
}
