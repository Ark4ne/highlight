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
        'line_number' => "color:#999;padding:0 5px 0 2px;margin-right:5px;background-color:#444;border-bottom:1px solid #444;border-right:1px solid #555;width:2.4rem;display:inline-block;text-align:right;-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;",
        'line_selected' => "display:inline-block;background-color:#444;width:100%",
        'pre' => 'font-size:12px;line-height:1.3;background-color: #2b2b2b;margin:0;color:#f7f7f7',
        TokenizerInterface::TOKEN_NAMESPACE => 'color:#90caf9',
        TokenizerInterface::TOKEN_VAR => 'color:#ce93d8',
        TokenizerInterface::TOKEN_KEY => 'color:#fb8c00;',
        TokenizerInterface::TOKEN_FUNCTION => 'color:#fdd835;',
        TokenizerInterface::TOKEN_STRING => 'color:#6A8759',
        TokenizerInterface::TOKEN_INT => 'color:#6897BB ',
        TokenizerInterface::TOKEN_COMMENT => 'color:#808080 ',
        TokenizerInterface::TOKEN_BLOCK_COMMENT => 'color:#808080 ',
    ];

    public static function styles(array $colors)
    {
        self::$styles = array_merge(self::$styles, $colors);
    }

    /**
     * Render an list of tokens
     *
     * @param array $tokens
     * @param array $options
     *
     * @return string
     */
    public function render(array $tokens, array $options = [])
    {
        $styles = array_merge(self::$styles, isset($options['styles']) ? $options['styles'] : []);

        $html = '';

        if(empty($options['inlineStyle'])){
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
            } elseif($token['type'] == TokenizerInterface::TOKEN_SPACE) {
                $str .= str_replace(' ', '&nbsp;', $token['value']);
            } else {
                $str .= $token['value'];
            }
        }

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $str));

        if (!empty($options['withLineNumber'])) {
            foreach ($lines as $idx => $line) {
                if (isset($ctx)) {
                    $lines[$idx] = '<code class="' . $ctx . 'line_number">' . ($idx+1) . '</code>' . $line;
                } else {
                    $lines[$idx] = '<code style="' . $styles['line_number'] . '">' . ($idx+1) . '</code>' . $line;
                }
            }
        }

        if(isset($options['lineSelected'])){
            if (isset($ctx)) {
                $lines[$options['lineSelected']-1] = '<code class="' . $ctx . 'line_selected">' . $lines[$options['lineSelected']-1] . '</code>';
            } else {
                $lines[$options['lineSelected']-1] = '<code style="' . $styles['line_selected'] . '">' . $lines[$options['lineSelected']-1] . '</code>';
            }
        }

        if (isset($options['lineOffset']) && isset($options['lineLimit'])) {
            $lines = array_slice($lines, $options['lineOffset'], $options['lineLimit']);
        }

        if(empty($options['noPre'])){
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
