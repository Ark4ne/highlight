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
        $clines = 0;
        $ldx = 0;

        $line = '';

        $withLimit = isset($this->options['lineOffset'], $this->options['lineLimit']);
        if($withLimit){
            $offset = $this->options['lineOffset'] + 1;
            $limit = $this->options['lineLimit'];
        } else {
            $offset = 0;
            $limit = INF;
        }

        $withLineNumber = !empty($this->options['withLineNumber']);
        $lineNumberStyle = isset($ctx)
            ? 'class="' . $ctx . 'line_number"'
            : 'style="' . $styles['line_number'] . '"';

        $lineSelected = isset($this->options['lineSelected'])
            ? $this->options['lineSelected']
            : null;
        $lineSelectedStyle = isset($ctx)
            ? 'class="' . $ctx . 'line_selected"'
            : 'style="' . $styles['line_selected'] . '"';

        foreach ($tokens as $token) {
            $isWhiteSpace = $token['type'] == Token::TOKEN_WHITESPACE;

            // prepare value
            $value = str_replace(["\r\n", "\r"], "\n", $token['value']);
            // explode lines
            $parts = explode("\n", $value);
            $count = count($parts);

            // default dom tag style
            $style = '';
            if (!empty($styles[$token['type']])) {
                $style = isset($ctx)
                    ? ' class="' . $ctx . $token['type'] . '"'
                    : ' style="' . $styles[$token['type']] . '"';
            }

            foreach ($parts as $idx => $part) {
                if ($ldx >= $offset && $limit > $clines) {
                    if ($isWhiteSpace) {
                        // if white-space, don't add tag, only spaces
                        $line .= str_replace(' ', '&nbsp;', $part);
                    } else {
                        // add tag with style, encode value
                        $line .= '<code' . $style . '>' . htmlspecialchars($part) . '</code>';
                    }
                }

                // last part must never add a new line.
                if (($idx + 1) < $count) {
                    if ($ldx >= $offset && $limit > $clines) {
                        if ($withLineNumber) {
                            $line = '<code ' . $lineNumberStyle . '>' . ($ldx + 1) . '</code>' . $line;
                        }
                        if ($lineSelected === ($ldx + 1)) {
                            $line = '<code ' . $lineSelectedStyle . '>' . $line . '</code>';
                        }
                        $lines[] = $line;
                        $clines++;

                        if ($clines >= $limit) {
                            // limit reached, stop the loops
                            break 2;
                        }
                    }
                    $ldx++;
                    $line = '';
                }
            }
        }

        if ($ldx >= $offset && $limit > $clines) {
            if ($withLineNumber) {
                $line = '<code ' . $lineNumberStyle . '>' . ($ldx + 1) . '</code>' . $line;
            }
            if ($lineSelected === ($ldx + 1)) {
                $line = '<code ' . $lineSelectedStyle . '>' . $line . '</code>';
            }
            $lines[] = $line;
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
