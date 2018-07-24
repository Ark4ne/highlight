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
    const COLOR_NONE = 0;
    const COLOR_BASIC = 1;
    const COLOR_256 = 2;
    const COLOR_16M = 3;

    private static $stylesBasic = [
        'default'             => "0;37",
        Token::TOKEN_VARIABLE => "1;35",
        Token::TOKEN_KEYWORD  => "1;31",
        Token::TOKEN_FUNCTION => "0;33",
        Token::TOKEN_STRING   => "0;32",
        Token::TOKEN_NUMBER   => "0;36",
    ];
    private static $styles256 = [
        'default'                  => "38;5;250",
        Token::TOKEN_VARIABLE      => "38;5;97",
        Token::TOKEN_KEYWORD       => "38;5;130",
        Token::TOKEN_FUNCTION      => "38;5;221",
        Token::TOKEN_STRING        => "38;5;107",
        Token::TOKEN_NUMBER        => "38;5;111",
        Token::TOKEN_COMMENT       => "38;5;244",
        Token::TOKEN_BLOCK_COMMENT => "38;5;244",
    ];
    private static $styles16m = [
        'default'                  => "38;2;169;183;198",
        Token::TOKEN_VARIABLE      => "38;2;152;118;170",
        Token::TOKEN_KEYWORD       => "38;2;204;120;50",
        Token::TOKEN_FUNCTION      => "38;2;255;198;109",
        Token::TOKEN_STRING        => "38;2;106;135;89",
        Token::TOKEN_NUMBER        => "38;2;104;151;187",
        Token::TOKEN_COMMENT       => "38;2;128;128;128",
        Token::TOKEN_BLOCK_COMMENT => "38;2;128;128;128",
        Token::TOKEN_NAMESPACE     => "38;2;169;183;198",
    ];

    private $options = [];

    private $hasColors;

    private $colorsLevel;

    /**
     * Shell constructor.
     *
     * @param array $options
     *                  - styles : array.<token, color> Define color for each token type.
     *                  - colors : int  Force coloration level
     *                                  Shell::COLOR_NONE  : no colors
     *                                  Shell::COLOR_BASIC : 16  colors
     *                                  Shell::COLOR_256   : 256 colors
     *                                  Shell::COLOR_16M   : truecolor
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        if (isset($options['colors'])) {
            $this->hasColors = true;
            switch ($options['colors']) {
                case self::COLOR_BASIC:
                    $this->colorsLevel = self::$stylesBasic;
                    break;
                case self::COLOR_256:
                    $this->colorsLevel = self::$styles256;
                    break;
                case self::COLOR_16M:
                    $this->colorsLevel = self::$styles16m;
                    break;
            }
        }

        if (!isset($this->hasColors)) {
            $this->hasColors = self::hasColorSupport();
        }
        if (!isset($this->colorsLevel)) {
            $this->colorsLevel = self::colorLevel();
        }
    }

    public function render(array $tokens)
    {
        $styles = array_merge(
            $this->getStyles(),
            isset($this->options['styles']) ? $this->options['styles'] : []
        );

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
                case Token::TOKEN_COMMENT:
                case Token::TOKEN_NAMESPACE:
                case Token::TOKEN_PUNCTUATION:
                default:
                    if (isset($styles[$token['type']])) {
                        $str .= $this->colorize($token['value'], $styles[$token['type']]);
                    } elseif(isset($styles['default'])) {
                        $str .= $this->colorize($token['value'], $styles['default']);
                    } else {
                        $str .= $token['value'];
                    }
            }
        }

        return $str;
    }

    private function colorize($str, $color)
    {
        if ($this->hasColors) {
            return "\033[{$color}m$str\033[0m";
        }

        return $str;
    }

    private function getStyles()
    {
        switch ($this->colorsLevel) {
            case self::COLOR_BASIC:
                return self::$stylesBasic;
            case self::COLOR_256:
                return self::$styles256;
            case self::COLOR_16M:
                return self::$styles16m;
        }

        return [];
    }

    private static function hasColorSupport()
    {
        static $hasColor;

        if (isset($hasColor)) {
            return $hasColor;
        }

        $opts = self::getColor();

        if (isset($opts['no-colors'])) {
            return false;
        }
        if(isset($opts['colors'])){
            return true;
        }

        if (DIRECTORY_SEPARATOR === '\\') {
            return $hasColor =
                (10 == PHP_WINDOWS_VERSION_MAJOR && PHP_WINDOWS_VERSION_BUILD >= 10586)
                || false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
                || 'xterm' === getenv('TERM');
        }

        return $hasColor = function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    private static function colorLevel()
    {
        static $colorLvl;

        if (isset($colorLvl)) {
            return $colorLvl;
        }

        if (self::hasColorSupport()) {
            $opts = self::getColor();

            switch (isset($opts['colors']) ? $opts['colors'] : null) {
                case 'full':
                case '16m':
                    return $colorLvl = self::COLOR_16M;
                case '256';
                    return $colorLvl = self::COLOR_256;
                case 'basic':
                    return $colorLvl = self::COLOR_BASIC;
            }

            if (DIRECTORY_SEPARATOR === '\\'
                && 10 == PHP_WINDOWS_VERSION_MAJOR && PHP_WINDOWS_VERSION_BUILD >= 10586) {
                return $colorLvl = PHP_WINDOWS_VERSION_BUILD >= 14931 ?
                    self::COLOR_16M :
                    self::COLOR_256;
            }

            if (getenv('ANSICON') && getenv('ANSICON_DEF') == 7) {
                return $colorLvl = self::COLOR_BASIC;
            }

            if (getenv('COLORTERM') == 'truecolor' || getenv('TERM') == 'xterm') {
                return $colorLvl = self::COLOR_16M;
            }

            if (preg_match('/-256(color)?$/', getenv('TERM'))) {
                return $colorLvl = self::COLOR_256;
            }

            return $colorLvl = self::COLOR_BASIC;
        }

        return $colorLvl = self::COLOR_NONE;
    }

    private static function getColor()
    {
        $opts = getopt('', ['no-colors::', 'colors::']);
        if (isset($opts['colors']) || isset($opts['no-colors'])) {
            return $opts;
        }

        $opts = [];
        foreach ($GLOBALS['argv'] as $arg) {
            $kv = explode('=', $arg);

            $opts[ltrim($kv[0], '-')] = isset($kv[1]) ? $kv[1] : false;
        }

        return $opts;
    }
}
