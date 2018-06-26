<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\TokenizerInterface;

/**
 * Class Shell
 *
 * @package     Highlight\Renders
 */
class Shell implements RenderInterface
{

    const COLOR_RED    = 31;
    const COLOR_GREEN  = 32;
    const COLOR_YELLOW = 33;
    const COLOR_BLUE   = 34;
    const COLOR_PURPLE = 35;
    const COLOR_CYAN   = 36;
    const COLOR_GRAY   = 37;

    public function render(array $tokens): string
    {
        $str = '';

        foreach ($tokens as $token) {
            switch ($token['type']){
                case TokenizerInterface::TOKEN_SPACE:
                case TokenizerInterface::TOKEN_VAR:
                    $str .= $token['value'];
                    break;
                case TokenizerInterface::TOKEN_PUNCTUATION:
                case TokenizerInterface::TOKEN_OPERATOR:
                    $str .= $this->colorize($token['value'], self::COLOR_GRAY);
                    break;
                case TokenizerInterface::TOKEN_KEY:
                    $str .= $this->colorize($token['value'], self::COLOR_PURPLE);
                    break;
                case TokenizerInterface::TOKEN_FUNCTION:
                    $str .= $this->colorize($token['value'], self::COLOR_YELLOW);
                    break;
                case TokenizerInterface::TOKEN_STRING:
                    $str .= $this->colorize($token['value'], self::COLOR_CYAN);
                    break;
                case TokenizerInterface::TOKEN_INT:
                    $str .= $this->colorize($token['value'], self::COLOR_BLUE);
                    break;
            }
        }

        return $str;
    }

    private function colorize(string $str, int $color): string
    {
        return "\033[0;{$color}m$str\033[0m";
    }
}
