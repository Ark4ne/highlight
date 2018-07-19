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
            . 'margin:0',

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

        if(empty($this->options['inlineStyle'])){
            $ctx = uniqid("hl");
            $html = "<style rel='stylesheet'>";
            foreach ($styles as $key => $style) {
                $html .= ".$ctx$key {{$style}}";
            }
            $html .= "</style>";
        }

        $str = '';

        foreach ($tokens as $token) {
            $token['value'] = htmlspecialchars($token['value']);

            if (!empty($styles[$token['type']])) {
                $parts = [];
                foreach (explode("\n", str_replace(["\r\n", "\r"], "\n", $token['value'])) as $w) {
                    if (isset($ctx)) {
                        $parts[] = '<code class="' . $ctx . $token['type'] . '">' . $w . '</code>';
                    } else {
                        $parts[] = '<code style="' . $styles[$token['type']] . '">' . $w . '</code>';
                    }
                }

                $str .= implode("\n", $parts);
            } elseif($token['type'] == Token::TOKEN_WHITESPACE) {
                $str .= str_replace(' ', '&nbsp;', $token['value']);
            } else {
                $str .= $token['value'];
            }
        }

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $str));

        if (!empty($this->options['withLineNumber'])) {
            foreach ($lines as $idx => $line) {
                if (isset($ctx)) {
                    $lines[$idx] = '<code class="' . $ctx . 'line_number">' . ($idx+1) . '</code>' . $line;
                } else {
                    $lines[$idx] = '<code style="' . $styles['line_number'] . '">' . ($idx+1) . '</code>' . $line;
                }
            }
        }

        if(isset($this->options['lineSelected'])){
            if (isset($ctx)) {
                $lines[$this->options['lineSelected']-1] = '<code class="' . $ctx . 'line_selected">' . $lines[$this->options['lineSelected']-1] . '</code>';
            } else {
                $lines[$this->options['lineSelected']-1] = '<code style="' . $styles['line_selected'] . '">' . $lines[$this->options['lineSelected']-1] . '</code>';
            }
        }

        if (isset($this->options['lineOffset']) && isset($this->options['lineLimit'])) {
            $lines = array_slice($lines, $this->options['lineOffset'], $this->options['lineLimit']);
        }

        if(empty($this->options['noPre'])){
            if (isset($ctx)) {
                return $html . '<pre class="' . $ctx . 'pre">' . implode("\n", $lines) . '</pre>';
            } else {
                return $html . '<pre style="' . $styles['pre'] . '">' . implode("\n", $lines) . '</pre>';
            }
        }

        if (isset($ctx)) {
            return $html . implode("\n", $lines) . '</pre>';
        } else {
            return $html . implode("\n", $lines) . '</pre>';
        }
    }
}
