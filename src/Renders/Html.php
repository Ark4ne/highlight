<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\TokenizerInterface;

/**
 * Class Html
 *
 * @package Highlight\Renders
 */
class Html implements RenderInterface
{

    public function render(array $tokens): string
    {
        $str = '';

        foreach ($tokens as $token) {
            if ($token['type'] != TokenizerInterface::TOKEN_SPACE) {
                $str .= '<code class="token ' . $token['type'] . '">' . $token['value'] . '</code>';
            } else {
                $str .= $token['value'];
            }
        }

        return $str;
    }
}
