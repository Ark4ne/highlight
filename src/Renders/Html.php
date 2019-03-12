<?php

namespace Highlight\Renders;

use Highlight\RenderInterface;
use Highlight\Token;

/**
 * Class Html
 *
 * @package Highlight\Renders
 */
class Html implements RenderInterface
{

    private static $styles = [
        'line_number'   =>
            'color:#999;padding:0 5px 0 2px;'
            . 'margin-right:5px;'
            . 'background-color:#444;'
            . 'border-bottom:1px solid #444;'
            . 'border-right:1px solid #555;'
            . 'width:2.4rem;'
            . 'display:inline-block;'
            . 'text-align:right;'
            . '-webkit-touch-callout:none;'
            . '-webkit-user-select:none;'
            . '-khtml-user-select:none;'
            . '-moz-user-select:none;'
            . '-ms-user-select:none;'
            . 'user-select:none;',
        'line_selected' =>
            'display:inline-block;'
            . 'width:100%;'
            . 'background-color:#3a3a3a',
        'pre'           =>
            'font-size:12px;'
            . 'line-height:1.3;'
            . 'background-color: #2b2b2b;'
            . 'color:#a9b7c6;'
            . 'margin:0;'
            . 'padding:0;'
            . 'display:block;'
            . 'overflow:auto;'
            . 'white-space:pre!important;',

        Token::TOKEN_NAMESPACE     => 'color:#a9b7c6',
        Token::TOKEN_VARIABLE      => 'color:#9876aa',
        Token::TOKEN_KEYWORD       => 'color:#cc7832;',
        Token::TOKEN_FUNCTION      => 'color:#ffc66d;',
        Token::TOKEN_STRING        => 'color:#6a8759',
        Token::TOKEN_NUMBER        => 'color:#6897bb ',
        Token::TOKEN_COMMENT       => 'color:#808080 ',
        Token::TOKEN_BLOCK_COMMENT => 'color:#808080 ',
    ];

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
        $styles = array_merge(self::$styles, isset($this->options['styles']) ? $this->options['styles'] : []);

        $html = '';

        if (empty($this->options['inlineStyle'])) {
            $ctx = "hl" . substr(md5(uniqid()), 0, 4) . '_';
            $html = "<style rel='stylesheet'>";
            foreach ($styles as $key => $style) {
                $html .= ".$ctx$key {{$style}}";
            }
            $html .= "</style>";
        }

        $lines = [];
        $ldx = 0;

        $line = '';

        $withLineNumber = !empty($this->options['withLineNumber']);
        $lineSelected = isset($this->options['lineSelected'])
            ? $this->options['lineSelected']
            : null;

        $lineNumberStyle = isset($ctx)
            ? 'class="' . $ctx . 'line_number"'
            : 'style="' . $styles['line_number'] . '"';
        $lineSelectedStyle = isset($ctx)
            ? 'class="' . $ctx . 'line_selected"'
            : 'style="' . $styles['line_selected'] . '"';

        foreach ($tokens as $token) {
            $value = str_replace(["\r\n", "\r"], "\n", $token['value']);

            $parts = explode("\n", $value);
            $count = count($parts);

            if ($token['type'] == Token::TOKEN_WHITESPACE) {
                foreach ($parts as $idx => $part) {
                    $line .= str_replace(' ', '&nbsp;', $part);
                    if ($idx + 1 < $count) {
                        if ($withLineNumber) {
                            $line = '<code ' . $lineNumberStyle . '>' . ($ldx + 1) . '</code>' . $line;
                        }
                        $lines[$ldx] = $line;
                        $line = '';
                        $ldx++;
                    }
                }
            } else {
                $style = '';
                if (!empty($styles[$token['type']])) {
                    $style = isset($ctx)
                        ? ' class="' . $ctx . $token['type'] . '"'
                        : ' style="' . $styles[$token['type']] . '"';
                }

                foreach ($parts as $idx => $part) {
                    $line .= '<code' . $style . '>' . htmlspecialchars($part) . '</code>';
                    if ($idx + 1 < $count) {
                        if ($withLineNumber) {
                            $line = '<code ' . $lineNumberStyle . '>' . ($ldx + 1) . '</code>' . $line;
                        }
                        if ($lineSelected === $ldx) {
                            $line = '<code ' . $lineSelectedStyle . '>' . ($ldx + 1) . '</code>' . $line;
                        }
                        $lines[$ldx] = $line;
                        $line = '';
                        $ldx++;
                    }
                }
            }
        }

        if ($withLineNumber) {
            $line = '<code ' . $lineNumberStyle . '>' . ($ldx + 1) . '</code>' . $line;
        }
        if ($lineSelected === $ldx) {
            $line = '<code ' . $lineSelectedStyle . '>' . ($ldx + 1) . '</code>' . $line;
        }

        $lines[$ldx] = $line;

        if (isset($this->options['lineOffset'], $this->options['lineLimit'])) {
            $lines = array_slice($lines, $this->options['lineOffset'] + 1, $this->options['lineLimit']);
        }

        if (empty($this->options['noPre'])) {
            if (isset($ctx)) {
                return $html . '<pre class="' . $ctx . 'pre">' . implode("\n", $lines) . '</pre>';
            }

            return $html . '<pre style="' . $styles['pre'] . '">' . implode("\n", $lines) . '</pre>';
        }

        return $html . implode("\n", $lines);
    }
}
