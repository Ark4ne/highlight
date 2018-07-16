<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\Token;

/**
 * Class Shell
 *
 * @package     Highlight\Renders
 */
class Shell implements RenderInterface
{

    const C_BLACK        = "0;30";
    const C_BLACK_LIGHT  = "1;30";
    const C_RED          = "0;31";
    const C_RED_LIGHT    = "1;31";
    const C_GREEN        = "0;32";
    const C_GREEN_LIGHT  = "1;32";
    const C_YELLOW       = "0;33";
    const C_YELLOW_LIGHT = "1;33";
    const C_BLUE         = "0;34";
    const C_BLUE_LIGHT   = "1;34";
    const C_PURPLE       = "0;35";
    const C_PURPLE_LIGHT = "1;35";
    const C_CYAN         = "0;36";
    const C_CYAN_LIGHT   = "1;36";
    const C_GRAY         = "0;37";
    const C_GRAY_LIGHT   = "1;37";

    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function render(array $tokens)
    {
        $str = '';

        foreach ($tokens as $token) {
            switch ($token['type']){
                case Token::TOKEN_OPERATOR:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_GRAY
                    );
                    break;
                case Token::TOKEN_VARIABLE:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_GRAY_LIGHT
                    );
                    break;
                case Token::TOKEN_KEYWORD:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_PURPLE
                    );
                    break;
                case Token::TOKEN_FUNCTION:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_YELLOW
                    );
                    break;
                case Token::TOKEN_STRING:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_GREEN_LIGHT
                    );
                    break;
                case Token::TOKEN_NUMBER:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_BLUE
                    );
                    break;
                case Token::TOKEN_COMMENT:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_BLACK_LIGHT
                    );
                    break;
                case Token::TOKEN_NAMESPACE:
                    $str .= $this->colorize(
                        $token['value'],
                        isset($this->options['colors'][$token['type']])
                            ? $this->options['colors'][$token['type']]
                            : self::C_GRAY
                    );
                    break;
                case Token::TOKEN_PUNCTUATION:
                case Token::TOKEN_WHITESPACE:
                default:
                    $str .= $token['value'];
                    break;
            }
        }

        return $str;
    }

    private function colorize($str, $color)
    {
        if (self::hasColorSupport()) {
            return "\033[{$color}m$str\033[0m";
        }

        return $str;
    }

    private static function hasColorSupport()
    {
        static $hasColor;

        if (isset($hasColor)) {
            return $hasColor;
        }

        if (DIRECTORY_SEPARATOR === '\\') {
            return $hasColor =
                '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . '.' . PHP_WINDOWS_VERSION_BUILD
                || false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
                || 'xterm' === getenv('TERM');
        }

        return $hasColor = function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }
}
