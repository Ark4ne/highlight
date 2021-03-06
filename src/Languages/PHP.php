<?php

namespace Highlight\Languages;

use Highlight\LanguageInterface;
use Highlight\Token;

/**
 * Class Php
 *
 * @package     Highlight\Tokenizer
 */
class PHP implements LanguageInterface
{
    const _x_whitespaces = '(\s+)';

    const _x_words = '([a-zA-Z_]\w*)';

    const _x_identifier = '(\\\\?' . self::_x_words . '(?:\\\\' . self::_x_words . ')*)';

    const X_WHITESPACE = '^' . self::_x_whitespaces;

    const X_TAG_OPEN = '^(<\?(?:php|=)?)';

    const X_TAG_CLOSE = '^(\?>)';

    const X_KEYWORDS = '^(a(?:bstract|nd|rray|s)|c(?:a(?:llable|se|tch)|l(?:ass|one)|on(?:st|tinue))|d(?:e(?:clare|fault)|ie|o)|e(?:cho|lse(?:if)?|mpty|nd(?:declare|for(?:each)?|if|switch|while)|val|x(?:it|tends))|f(?:inal(?:ly)?|or(?:each)?|alse|unction)|g(?:lobal|oto)|i(?:f|mplements|n(?:clude(?:_once)?|st(?:anceof|eadof)|terface)|sset)|n(?:amespace|ew|ull)|p(?:r(?:i(?:nt|vate)|otected)|arent|ublic)|re(?:quire(?:_once)?|turn)|s(?:tatic|elf|witch)|t(?:hrow|r(?:ait|ue|y))|u(?:nset|se)|__halt_compiler|break|list|xor|or|var|while)\b';

    const X_PUNCTUATIONS = '^([\[\]{}()<>=|&.,:;!/*+-])';

    const X_FUNCTIONS = '^(' . self::_x_identifier . '\\\\)?' . self::_x_words . self::_x_whitespaces . '?\(';

    const X_METHOD_CALL = '^(->|::)' . self::_x_whitespaces . '?' . self::_x_words . self::_x_whitespaces . '?\(';
    const X_METHOD_DECLARATION = '^(function)' . self::_x_whitespaces . self::_x_words . self::_x_whitespaces . '?\(';

    const X_CLASSES = '^' . self::_x_identifier;

    const X_CONST = '^(__(?:CLASS|FUNCTION|FILE|DIR|NAMESPACE|METHOD|TRAIT|LINE)__|(INF|NAN|SOMAXCONN|STDIN|STDOUT|STDERR)|(?:(?:AF|ARRAY|ASSERT|CAL|CASE|CHAR|CONNECTION|COUNT|CREDITS|CRYPT|CURL(?:E|AUTH|FTP|METHOD|SSL|GSSAPI|HEADER|INFO|M|OPT|MSG|PAUSE|PIPE|PROTO|PROXY|SHOPT|SSH|SSLOPT|USE|VERSION)*|DATE|DEBUG|DEFAULT|DIRECTORY|DNS|DOM|DOMSTRING|E|ENT|EXTR|FILE|FILTER|FNM|FORCE|GD|GLOB|HASH|HTML|ICONV|IMAGETYPE|IMG|INFO|INI|INPUT|IP|IPPROTO|IPV6|JSON|LC|LIBXML|LOCK|LOG|M|MB|MCAST|MCRYPT|MHASH|MSG|OPENSSL|PASSWORD|PATH|PATHINFO|PCRE|PEAR|PHP|PKCS7|PNG|PREG|PSFS|SCANDIR|SEEK|SO|SOCK|SOCKET|SOL|SORT|STR|STREAM|SUNFUNCS|T|TCP|TOKEN|UPLOAD|X509|XML|ZEND|ZLIB?)_\w+))';

    const X_VARIABLE = '^(\$(\$?)' . self::_x_words . '|(?:(->|::)' . self::_x_whitespaces . '?)' . self::_x_words . ')';

    const X_WORDS = '^' . self::_x_words;

    const X_NUMBER = '^(-?\d+(?:[.b]\d+|x[A-Fa-f0-9]+)?)';

    const X_STRING = '^(?:(["\'])[\W\w]*|(<<<\s*(?P<here>[a-zA-Z]+)(?:\r\n|\r|\n)?)([\W\w]*)(((?:\r\n|\r|\n)?(?P=here));))';

    const X_COMMENT = '^(//[^\n\r]+|(/[*])[\w\W]+)';

    const RX = [
        'whitespace'  => '~' . self::X_WHITESPACE . '~',
        'tag_open'    => '~' . self::X_TAG_OPEN . '~',
        'tag_close'   => '~' . self::X_TAG_CLOSE . '~',
        'method_def'  => '~' . self::X_METHOD_DECLARATION . '~',
        'keywords'    => '~' . self::X_KEYWORDS . '~i',
        'function'    => '~' . self::X_FUNCTIONS . '~',
        'method'      => '~' . self::X_METHOD_CALL . '~',
        'variable'    => '~' . self::X_VARIABLE . '~',
        'number'      => '~' . self::X_NUMBER . '~',
        'string'      => '~' . self::X_STRING . '~',
        'const'       => '~' . self::X_CONST . '~',
        'classes'     => '~' . self::X_CLASSES . '~',
        'comment'     => '~' . self::X_COMMENT . '~',
        'words'       => '~' . self::X_WORDS . '~',
        'punctuation' => '~' . self::X_PUNCTUATIONS . '~',
    ];

    /** @var string Current context (html, php) */
    private $context = 'html';

    private $options = [];

    //public static $profiles = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->context = isset($options['context'])
            ? $options['context']
            : 'html';
    }

    /**
     * @param string     $type
     * @param array      $match
     * @param array|null $previous
     *
     * @return array
     */
    private function token($type, $match, &$previous = null)
    {
        switch ($type) {
            case 'whitespace':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => Token::TOKEN_WHITESPACE, 'value' => $match[0]]],
                ];
            case 'tag_open':
                $this->context = 'php';

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_KEYWORD, 'value' => $match[1]]],
                ];
            case 'tag_close':
                $this->context = 'html';

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_KEYWORD, 'value' => $match[1]]],
                ];
            case 'keywords':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_KEYWORD, 'value' => $match[1]]],
                ];
            case 'function':
                $matched = $match[0];

                if ($match[1]) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_NAMESPACE,
                        'value' => $match[1],
                    ];
                }

                $tokens[] = [
                    'type'  => Token::TOKEN_WORD,
                    'value' => $match[5],
                ];

                if (!empty($match[6])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_WHITESPACE,
                        'value' => $match[6],
                    ];
                }

                $tokens[] = [
                    'type'  => Token::TOKEN_PUNCTUATION,
                    'value' => '(',
                ];

                return [
                    'match'  => $matched,
                    'tokens' => $tokens,
                ];
            case 'method':
                $matched = $match[0];

                if ($match[1]) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_PUNCTUATION,
                        'value' => $match[1],
                    ];
                }
                if ($match[2]) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_WHITESPACE,
                        'value' => $match[2],
                    ];
                }

                $tokens[] = [
                    'type'  => Token::TOKEN_FUNCTION,
                    'value' => $match[3],
                ];

                if (!empty($match[4])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_WHITESPACE,
                        'value' => $match[4],
                    ];
                }

                $tokens[] = [
                    'type'  => Token::TOKEN_PUNCTUATION,
                    'value' => '(',
                ];

                return [
                    'match'  => $matched,
                    'tokens' => $tokens,
                ];
            case 'method_def':
                $matched = $match[0];

                $tokens[] = [
                    'type'  => Token::TOKEN_KEYWORD,
                    'value' => $match[1],
                ];
                $tokens[] = [
                    'type'  => Token::TOKEN_WHITESPACE,
                    'value' => $match[2],
                ];
                $tokens[] = [
                    'type'  => Token::TOKEN_FUNCTION,
                    'value' => $match[3],
                ];

                if (!empty($match[4])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_WHITESPACE,
                        'value' => $match[4],
                    ];
                }

                $tokens[] = [
                    'type'  => Token::TOKEN_PUNCTUATION,
                    'value' => '(',
                ];

                return [
                    'match'  => $matched,
                    'tokens' => $tokens,
                ];
            case 'const':
                $tokens[] = [
                    'type'  => Token::TOKEN_VARIABLE,
                    'value' => $match[1],
                ];

                return [
                    'match'  => $match[1],
                    'tokens' => $tokens,
                ];
            case 'punctuation':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[1]]],
                ];
            case 'words':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_NAMESPACE, 'value' => $match[1]]],
                ];
            case 'classes':
                if (isset($previous['value']) && $previous['value'] === 'const') {
                    $type = Token::TOKEN_VARIABLE;
                } else {
                    $type = Token::TOKEN_NAMESPACE;
                }

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => $type, 'value' => $match[1]]],
                ];
            case 'variable':
                if (!empty($match[4])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_PUNCTUATION,
                        'value' => $match[4],
                    ];
                    if (!empty($match[5])) {
                        $tokens[] = [
                            'type'  => Token::TOKEN_WHITESPACE,
                            'value' => $match[5],
                        ];
                    }
                    $tokens[] = [
                        'type'  => Token::TOKEN_VARIABLE,
                        'value' => $match[6],
                    ];
                } elseif (!empty($match[2])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_KEYWORD,
                        'value' => $match[1],
                    ];
                } else {
                    $tokens[] = [
                        'type'  => Token::TOKEN_VARIABLE,
                        'value' => $match[1],
                    ];
                }
                return [
                    'match'  => $match[1],
                    'tokens' => $tokens,
                ];
            case 'number':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_NUMBER, 'value' => $match[1]]],
                ];
            case 'string':

                if (!empty($match['here'])) {
                    $str = $match[4];

                    $quote = $match['here'] . ';';

                    $tokens[] = [
                        'type'  => Token::TOKEN_WORD,
                        'value' => $match[2],
                    ];
                    $matched = $match[2];
                } else {
                    $str = $match[0];
                    $matched = '';
                    $quote = $match[1];
                }


                $qlen = strlen($quote);

                $next = 0;

                do {
                    $next = strpos($str, $quote, $next + $qlen);
                } while (
                    $next !== false && $next > 1 &&
                    // string escape : 'I\'m.'
                    ($str[$next - 1] == '\\' && $str[$next - 2] != '\\')
                );

                if ($next === false) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_STRING,
                        'value' => $value = $str,
                    ];
                } else {
                    $tokens[] = [
                        'type'  => Token::TOKEN_STRING,
                        'value' => $value = substr($str, 0, $next + (isset($match[5]) ? 0 : 1)),
                    ];
                }

                $matched .= $value;

                if (isset($match[5])) {
                    $tokens[] = [
                        'type'  => Token::TOKEN_WORD,
                        'value' => $match[6],
                    ];
                    $tokens[] = [
                        'type'  => Token::TOKEN_PUNCTUATION,
                        'value' => ';',
                    ];
                    $matched .= $match[5];
                }

                return [
                    'match'  => $matched,
                    'tokens' => $tokens,
                ];
            case 'comment':
                if (!empty($match[2])) {
                    $stop = strpos($match[0], '*/');

                    $value = substr($match[0], 0, $stop + 2);

                    return [
                        'match'  => $value,
                        'tokens' => [['type' => Token::TOKEN_BLOCK_COMMENT, 'value' => $value]],
                    ];
                }

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_COMMENT, 'value' => $match[1]]],
                ];
        }

        return [
            'match'  => $match[0],
            'tokens' => [['type' => 'unknown', 'value' => $match[0]]],
        ];
    }

    /**
     * Extract no php parts
     *
     * @param string $str
     *
     * @return array
     */
    private function extractOtherContext($str)
    {
        $open = strpos($str, '<?');

        if ($open === false) {
            return ['match' => $str, 'tokens' => [['type' => 'unknown', 'value' => $str]]];
        }

        if ($open === 0) {
            return ['match' => '', 'tokens' => []];
        }

        $value = substr($str, 0, $open);

        return [
            'match'  => $value,
            'tokens' => [['type' => 'unknown', 'value' => $value]],
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

        while (!empty($str)) {
            $token = null;

            if ($this->context !== 'php') {
                $token = $this->extractOtherContext($str);

                if ($token['match'] != '') {
                    $str = substr($str, strlen($token['match']));
                }

                foreach ($token['tokens'] as $tok) {
                    $tokens[] = $tok;
                    $ctokens++;
                }

                if (empty($str)) {
                    break;
                }
            }

            foreach (self::RX as $type => $regex) {
                //if (!isset(self::$profiles[$type])) {
                //    self::$profiles[$type] = ['in' => 0, 'out' => 0, 'time' => 0, 'preg_y' => 0, 'preg_n' => 0];
                //}

                //$preg = microtime(true);
                if (preg_match($regex, $str, $match)) {
                    //self::$profiles[$type]['preg_y'] += microtime(true) - $preg;
                    //self::$profiles[$type]['in']++;

                    //$start = microtime(true);
                    $token = $this->token($type, $match, $previous);
                    //self::$profiles[$type]['time'] += microtime(true) - $start;

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

                //self::$profiles[$type]['preg_n'] += microtime(true) - $preg;
                //self::$profiles[$type]['out']++;
            }

            if (!isset($token)) {
                $tokens[] = ['type' => 'unknown', 'value' => $str[0]];
                $ctokens++;

                $str = substr($str, 1);
            }
        }

        return $tokens;
    }

    public function tokenize($str)
    {
        return $this->parse($str);
    }

    public function format(array $tokens)
    {
        return $tokens;
    }
}
