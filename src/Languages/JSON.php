<?php

namespace Highlight\Languages;

use Highlight\LanguageInterface;
use Highlight\Token;

/**
 * Class JSON
 *
 * @package Highlight\Languages
 */
class JSON implements LanguageInterface
{
    const X_WHITESPACES = '^(\s+)';

    const X_PUNCTUATION = '^[\[\{\]\},]';

    const X_KEYWORDS = '^(true|false|null)';

    const X_NUMBER = '^(-?\d+(?:[.]\d+)?)';

    const X_STRING = '^"[\W\w]*';

    const X_PROPERTY = '^:';

    const RX = [
        'whitespaces' => '~' . self::X_WHITESPACES . '~',
        'punctuation' => '~' . self::X_PUNCTUATION . '~',
        'property'    => '~' . self::X_PROPERTY . '~',
        'keywords'    => '~' . self::X_KEYWORDS . '~i',
        'number'      => '~' . self::X_NUMBER . '~',
        'string'      => '~' . self::X_STRING . '~',
    ];

    const FORMAT_NONE = 0;
    const FORMAT_COMPRESS = 1;
    const FORMAT_NESTED = 2;
    const FORMAT_EXPAND = 4;

    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    private function token($type, $match, &$previous = null)
    {
        switch ($type) {
            case 'whitespaces':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_WHITESPACE, 'value' => $match[0]]],
                ];
            case 'punctuation':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[0]]],
                ];
            case 'keywords':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_KEYWORD, 'value' => $match[0]]],
                ];
            case 'property':
                $previous['type'] = Token::TOKEN_VARIABLE;

                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[0]]],
                ];
            case 'number':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_NUMBER, 'value' => $match[0]]],
                ];
            case 'string':
                $str = $match[0];

                $separator = $str[0];

                $next = 0;

                do {
                    $next = strpos($str, $separator, $next + 1);
                } while (
                    $next !== false
                    && $next > 1
                    && $str[$next - 1] == '\\'
                    && $str[$next - 2] != '\\'
                );

                if ($next === false) {
                    return [
                        'match'  => $str,
                        'tokens' => [['type' => Token::TOKEN_STRING, 'value' => $str]],
                    ];
                }
                return [
                    'match'  => $value = substr($str, 0, $next + 1),
                    'tokens' => [['type' => Token::TOKEN_STRING, 'value' => $value]],
                ];
        }

        return [
            'match'  => $match[0],
            'tokens' => [['type' => 'unknown', 'value' => $match[0]]],
        ];
    }

    private function parse($str)
    {
        if (empty($str)) {
            return [];
        }

        $tokens = [];
        $ctokens = 0;
        $previous = null;

        $rx = self::RX;

        while (!empty($str)) {
            $token = null;

            foreach ($rx as $type => $regex) {
                if (preg_match($regex, $str, $match)) {
                    $token = $this->token($type, $match, $previous);

                    if ($token['match'] != '') {
                        $str = substr($str, strlen($token['match']));
                    }

                    foreach ($token['tokens'] as $tok) {
                        $tokens[] = $tok;
                        if ($tok['type'] !== Token::TOKEN_WHITESPACE) {
                            $previous = &$tokens[$ctokens];
                        }
                        $ctokens++;
                    }
                    break;
                }
            }

            if (!isset($token)) {
                $tokens[] = ['type' => 'unknown', 'value' => $str[0]];
                $ctokens++;

                $str = substr($str, 1);
            }
        }

        return $tokens;
    }

    /**
     * Tokenize a string
     *
     * @param string $str
     *
     * @return array
     */
    public function tokenize($str)
    {
        return $this->parse($str);
    }

    /**
     * Format tokens
     *
     * @param array $tokens
     *
     * @return array
     */
    public function format(array $tokens)
    {
        $format = isset($this->options['format'])
            ? $this->options['format']
            : null;

        switch ($format) {
            case self::FORMAT_COMPRESS:
                return array_filter($tokens, function ($token) {
                    return $token['type'] !== Token::TOKEN_WHITESPACE;
                });
            case self::FORMAT_EXPAND:
            case self::FORMAT_NESTED:
                $formatted = [];
                $tokens = array_filter($tokens, function ($token) {
                    return $token['type'] !== Token::TOKEN_WHITESPACE;
                });
                $indent = 0;
                $indentSize = isset($this->options['indent'])
                    ? $this->options['indent']
                    : 4;
                $nextIndent = false;
                foreach ($tokens as $idx => $token) {
                    if ($token['type'] == Token::TOKEN_PUNCTUATION) {
                        switch ($token['value']) {
                            case '{':
                            case '[':
                                $indent++;
                                break;
                            case ']':
                            case '}':
                                $indent--;
                                if ($format === self::FORMAT_EXPAND) {
                                    $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => "\n"];
                                    $nextIndent = true;
                                }
                                break;
                        }
                    }

                    if ($indent && $nextIndent) {
                        $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => str_repeat(' ', $indent * $indentSize)];
                        $nextIndent = false;
                    }

                    $formatted[] = $token;

                    if ($token['type'] == Token::TOKEN_PUNCTUATION) {
                        switch ($token['value']) {
                            case ',':
                            case '{':
                            case '[':
                                $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => "\n"];
                            case ']':
                            case '}':
                                $nextIndent = isset($tokens[$idx + 1]['value']) ? $tokens[$idx + 1]['value'] !== ',' : true;
                                break;
                            case ':':
                                $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => " "];
                        }
                    }
                }

                return $formatted;
        }

        return $tokens;
    }
}
