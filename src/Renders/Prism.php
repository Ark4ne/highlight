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

    public function render(array $tokens)
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
