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
        TokenizerInterface::TOKEN_NAMESPACE => 'color:#eceff1',
        TokenizerInterface::TOKEN_VAR => 'color:#9876AA',
        TokenizerInterface::TOKEN_KEY => 'color:#CC7832;',
        TokenizerInterface::TOKEN_FUNCTION => 'color:#ffc66d',
        TokenizerInterface::TOKEN_STRING => 'color:#6A9759',
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
            } else {
                $str .= $token['value'];
            }
        }

        return $str;
    }
}
