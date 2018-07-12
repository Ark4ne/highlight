<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\TokenizerInterface;

/**
 * Class Prism
 *
 * @package Highlight\Renders
 */
class Prism implements RenderInterface
{
    /**
     * Render an list of tokens
     *
     * @param array $tokens
     * @param array $options
     *
     * @return string
     */
    public function render(array $tokens, array $options = [])
    {
        $str = '';

        foreach ($tokens as $token) {
            switch ($token['type']) {
                case TokenizerInterface::TOKEN_OPERATOR:
                case TokenizerInterface::TOKEN_VAR:
                case TokenizerInterface::TOKEN_KEY:
                case TokenizerInterface::TOKEN_FUNCTION:
                case TokenizerInterface::TOKEN_STRING:
                case TokenizerInterface::TOKEN_INT:
                case TokenizerInterface::TOKEN_PUNCTUATION:
                case TokenizerInterface::TOKEN_NAMESPACE:
                case TokenizerInterface::TOKEN_COMMENT:
                case TokenizerInterface::TOKEN_BLOCK_COMMENT:
                    $str .= '<code class="token ' . $token['type'] . '">' . $token['value'] . '</code>';
                    break;
                case TokenizerInterface::TOKEN_SPACE:
                default:
                    $str .= $token['value'];
                    break;
            }
        }

        return $str;
    }
}
