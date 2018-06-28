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

    private static $styles = [
        TokenizerInterface::TOKEN_NAMESPACE => 'color:#90caf9',
        TokenizerInterface::TOKEN_VAR => 'color:#ce93d8',
        TokenizerInterface::TOKEN_KEY => 'color:#fb8c00;',
        TokenizerInterface::TOKEN_FUNCTION => 'color:#fdd835;font-style:italic',
        TokenizerInterface::TOKEN_STRING => 'color:#a5d6a7',
        TokenizerInterface::TOKEN_INT => 'color:#6897BB ',
    ];

    public static function styles(array $colors)
    {
        self::$styles = array_merge(self::$styles, $colors);
    }

    public function render(array $tokens): string
    {
        $str = '';

        foreach ($tokens as $token) {
            if (isset(self::$styles[$token['type']])) {
                $str .= '<code style="' . self::$styles[$token['type']] . '">' . $token['value'] . '</code>';
            } elseif($token['type'] == TokenizerInterface::TOKEN_SPACE) {
                $str .= str_replace(' ', '&nbsp;', $token['value']);
            } else {
                $str .= $token['value'];
            }
        }

        return $str;
    }
}
