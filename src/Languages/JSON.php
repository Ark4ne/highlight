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

    const X_OPEN = '^[\[\{]';

    const X_CLOSE = '^[\]\}]';

    const X_KEYWORDS = '^(true|false|null)';

    const X_NUMBER = '^(-?\d+(?:[.]\d+)?)';

    const X_STRING = '^"[\W\w]*';

    const X_PROPERTY = '^:';

    const X_PUNCTUATION = '^,';

    const RX = [
        'whitespaces' => '~' . self::X_WHITESPACES . '~',
        'open'        => '~' . self::X_OPEN . '~',
        'close'       => '~' . self::X_CLOSE . '~',
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
            case 'open':
            case 'close':
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
    }

    private function parse($str)
    {
        if (empty($str)) {
            return [];
        }

        $tokens = [];

        $rx = self::RX;

        while (!empty($str)) {
            $previous = null;

            while ($item = current($rx)) {
                $type = key($rx);

                if (preg_match($item, $str, $match)) {
                    $token = $this->token($type, $match, $previous);

                    $str = substr($str, strlen($token['match']));

                    $tokens = array_merge($tokens, $token['tokens']);

                    if (empty($str)) {
                        break;
                    }

                    end($tokens);
                    while ($current = current($tokens)) {
                        if ($current['type'] !== Token::TOKEN_WHITESPACE) {
                            $previous = &$tokens[key($tokens)];
                            break;
                        }
                        prev($tokens);
                    }

                    reset($rx);
                } else {
                    next($rx);
                }
            }

            if (!empty($str)) {
                reset($rx);

                $tokens[] = ['type' => 'unknown', 'value' => $str[0]];

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
        switch (isset($this->options['format']) ? $this->options['format'] : null) {
            case self::FORMAT_EXPAND:
                // TODO Implement format
            case self::FORMAT_NESTED:
                // TODO Implement format
                $formatted = [];

                $indent = 0;
                $nextIndent = false;
                foreach ($tokens as $token) {
                    switch ($token['type']) {
                        case Token::TOKEN_WHITESPACE:
                            continue 2;
                        case Token::TOKEN_PUNCTUATION:
                            switch ($token['value']) {
                                case '{':
                                case '[':
                                    $indent++;
                                    break;
                                case ']':
                                case '}':
                                    $indent--;
                                    break;
                            }
                    }

                    if ($indent && $nextIndent) {
                        $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => str_repeat(' ', $indent * 4)];
                        $nextIndent = false;
                    }

                    $formatted[] = $token;

                    switch ($token['type']) {
                        case Token::TOKEN_PUNCTUATION:
                            switch ($token['value']) {
                                case ',':
                                case '{':
                                case '[':
                                    $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => "\n"];
                                case ']':
                                case '}':
                                    $nextIndent = true;
                                    break;
                            }
                    }
                }

                return $formatted;
            case self::FORMAT_COMPRESS:
                return array_filter($tokens, function ($token) {
                    return $token['type'] !== Token::TOKEN_WHITESPACE;
                });
        }

        return $tokens;
    }
}
