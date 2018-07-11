<?php

namespace Highlight\Tokenizer;

use Highlight\TokenizerInterface;

/**
 * Class Php
 *
 * @package     Highlight\Tokenizer
 */
class PHP implements TokenizerInterface
{
    const _x_whitespaces = '(\s+)';

    const _x_words = '([a-zA-Z_]\w*)';

    const _x_identifier = '(\\\\?' . self::_x_words . '(?:\\\\?' . self::_x_words . ')*)';

    const X_WHITESPACE = '^' . self::_x_whitespaces;

    const X_TAG_OPEN = '^(<\?(?:php|=)?)';

    const X_TAG_CLOSE = '^(\?>)';

    const X_KEYWORDS = '^((?:a(?:bstract|nd|rray|s))|(?:c(?:a(?:llable|se|tch)|l(?:ass|one)|on(?:st|tinue)))|(?:d(?:e(?:clare|fault)|ie|o))|(?:e(?:cho|lse(?:if)?|mpty|nd(?:declare|for(?:each)?|if|switch|while)|val|x(?:it|tends)))|(?:f(?:inal|or(?:each)?|unction))|(?:g(?:lobal|oto))|(?:i(?:f|mplements|n(?:clude(?:_once)?|st(?:anceof|eadof)|terface)|sset))|(?:n(?:amespace|ew))|(?:p(?:r(?:i(?:nt|vate)|otected)|ublic))|(?:re(?:quire(?:_once)?|turn))|(?:s(?:tatic|elf|witch))|(?:t(?:hrow|r(?:ait|y)))|(?:u(?:nset|se))|(?:__halt_compiler|break|list|(?:x)?or|var|while))\b';

    const X_PUNCTUATIONS = '^([\[\]{}()<>=|&.,:;!/*+-]+)';

    const X_FUNCTIONS = '^((' . self::_x_identifier . '\\\\)|(->|::))?' . self::_x_words . self::_x_whitespaces . '?\(';

    const X_CLASSES = '^' . self::_x_identifier;

    const X_CONST = '^(__(?:CLASS|FUNCTION|FILE|DIR|NAMESPACE|METHOD|TRAIT|LINE)__|(INF|NAN|SOMAXCONN|STDIN|STDOUT|STDERR)|(?:(?:AF|ARRAY|ASSERT|CAL|CASE|CHAR|CONNECTION|COUNT|CREDITS|CRYPT|CURL(?:E|AUTH|FTP|METHOD|SSL|GSSAPI|HEADER|INFO|M|OPT|MSG|PAUSE|PIPE|PROTO|PROXY|SHOPT|SSH|SSLOPT|USE|VERSION)*|DATE|DEBUG|DEFAULT|DIRECTORY|DNS|DOM|DOMSTRING|E|ENT|EXTR|FILE|FILTER|FNM|FORCE|GD|GLOB|HASH|HTML|ICONV|IMAGETYPE|IMG|INFO|INI|INPUT|IP|IPPROTO|IPV6|JSON|LC|LIBXML|LOCK|LOG|M|MB|MCAST|MCRYPT|MHASH|MSG|OPENSSL|PASSWORD|PATH|PATHINFO|PCRE|PEAR|PHP|PKCS7|PNG|PREG|PSFS|SCANDIR|SEEK|SO|SOCK|SOCKET|SOL|SORT|STR|STREAM|SUNFUNCS|T|TCP|TOKEN|UPLOAD|X509|XML|ZEND|ZLIB?)_\w+))';

    const X_VARIABLE = '^(\$' . self::_x_words . '|(->|::)' . self::_x_words . ')';

    const X_WORDS = '^' . self::_x_words;

    const X_NUMBER = '^(\d+(?:\.\d+)?)';

    const X_STRING = '^(?:(["\'])[\W\w]*|(<<<\s*(?P<here>[a-zA-Z]+)(?:\r\n|\r|\n)?)([\W\w]*)((?:\r\n|\r|\n)?(?P=here);))';

    const X_COMMENT = '^(//[^\n\r]+|(/[*])[\w\W]+)';

    const RX = [
        'whitespace'  => self::X_WHITESPACE,
        'tag_open'    => self::X_TAG_OPEN,
        'tag_close'   => self::X_TAG_CLOSE,
        'keywords'    => self::X_KEYWORDS,
        'function'    => self::X_FUNCTIONS,
        'variable'    => self::X_VARIABLE,
        'number'      => self::X_NUMBER,
        'string'      => self::X_STRING,
        'const'       => self::X_CONST,
        'classes'     => self::X_CLASSES,
        'comment'     => self::X_COMMENT,
        'words'       => self::X_WORDS,
        'punctuation' => self::X_PUNCTUATIONS,
    ];

    /** @var string Current context (html, php) */
    private $context = 'html';

    private function token($type, $match)
    {
        switch ($type) {
            case 'whitespace':
                return [
                    'match'  => $match[0],
                    'tokens' => [['type' => self::TOKEN_SPACE, 'value' => $match[0]]],
                ];
            case 'tag_open':
                $this->context = 'php';

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => self::TOKEN_KEY, 'value' => $match[1]]]
                ];
            case 'tag_close':
                $this->context = 'html';

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => self::TOKEN_KEY, 'value' => $match[1]]]
                ];
            case 'keywords':
                return [
                    'match' => $match[1],
                    'tokens' => [['type'  => self::TOKEN_KEY, 'value' => $match[1]]]
                ];
            case 'function':
                $matched = $match[1] . $match[7];

                if ($match[2]) {
                    $tokens[] = [
                        'type' => self::TOKEN_NAMESPACE,
                        'value' => $match[2]
                    ];
                }
                if ($match[6]) {
                    $tokens[] = [
                        'type' => self::TOKEN_PUNCTUATION,
                        'value' => $match[6]
                    ];
                }

                $tokens[] = [
                    'type' => self::TOKEN_FUNCTION,
                    'value' => $match[7]
                ];

                return [
                    'match' => $matched,
                    'tokens' => $tokens
                ];
            case 'const':
                if (!empty($match[2])) {
                    $tokens[] = [
                        'type' => self::TOKEN_PUNCTUATION,
                        'value' => $match[2]
                    ];
                    $tokens[] = [
                        'type' => self::TOKEN_VAR,
                        'value' => $match[3]
                    ];
                } else {
                    $tokens[] = [
                        'type' => self::TOKEN_VAR,
                        'value' => $match[1]
                    ];
                }

                return [
                    'match' => $match[1],
                    'tokens' => $tokens
                ];
            case 'punctuation':
                return [
                    'match' => $match[1],
                    'tokens' => [['type'  => self::TOKEN_PUNCTUATION, 'value' => $match[1]]]
                ];
            case 'words':
                return [
                    'match' => $match[1],
                    'tokens' => [['type'  => self::TOKEN_NAMESPACE, 'value' => $match[1]]]
                ];
            case 'classes':
                return [
                    'match' => $match[1],
                    'tokens' => [['type'  => self::TOKEN_NAMESPACE, 'value' => $match[1]]]
                ];
            case 'variable':
                if(!empty($match[3])) {
                    $tokens[] = [
                        'type'  => self::TOKEN_PUNCTUATION,
                        'value' => $match[3]
                    ];
                    $tokens[] = [
                        'type'  => self::TOKEN_VAR,
                        'value' => $match[4]
                    ];
                } else {
                    $tokens[] = [
                        'type'  => self::TOKEN_VAR,
                        'value' => $match[1]
                    ];
                }
                return [
                    'match' => $match[1],
                    'tokens' => $tokens
                ];
            case 'number':
                return [
                    'match' => $match[1],
                    'tokens' => [['type'  => self::TOKEN_INT, 'value' => $match[1]]]
                ];
            case 'string':

                if (!empty($match['here'])) {
                    $str = $match[4];

                    $quote = $match['here'] . ';';

                    $tokens[] = [
                        'type'  => self::TOKEN_NAMESPACE,
                        'value' => $match[2]
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
                    $next !== false && $next > 1
                    && (
                        // "\" string escape
                        ($str[$next - 1] == '\\' && $str[$next - 2] != '\\')
                    )
                );

                if($next === false){
                    $tokens[] = [
                        'type' => self::TOKEN_STRING,
                        'value' => $value = $str
                    ];
                } else {
                    $tokens[] = [
                        'type' => self::TOKEN_STRING,
                        'value' => $value = substr($str, 0, $next + (isset($match[5]) ? 0 : 1))
                    ];
                }

                $matched .= $value;

                if(isset($match[5])){
                    $tokens[] = [
                        'type'  => self::TOKEN_NAMESPACE,
                        'value' => $match[5]
                    ];
                    $matched .= $match[5];
                }

                return [
                    'match' => $matched,
                    'tokens' => $tokens
                ];
            case 'comment':
                if (!empty($match[2])) {
                    $stop = strpos($match[0], '*/');

                    $value = substr($match[0], 0, $stop + 2);

                    return [
                        'match' => $value,
                        'tokens' => [['type' => self::TOKEN_BLOCK_COMMENT, 'value' => $value]]
                    ];
                }

                return [
                    'match' => $match[1],
                    'tokens' => [['type' => self::TOKEN_COMMENT, 'value' => $match[1]]]
                ];
        }

        return [
            'match' => $match[0],
            'tokens' => [['type' => 'unknown', 'value' => $match[0]]]
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
            'match' => $value,
            'tokens' => [['type' => 'unknown', 'value' => $value]]
        ];
    }

    private function parse($str)
    {
        if (empty($str)) {
            return [];
        }

        $tokens = [];

        $rx = self::RX;

        while ($item = current($rx)) {
            if ($this->context !== 'php') {
                $token = $this->extractOtherContext($str);

                $str = substr($str, strlen($token['match']));

                $tokens = array_merge($tokens, $token['tokens']);

                if (empty($str)) {
                    break;
                }
            }

            $type = key($rx);

            if (preg_match('~' . $item . '~', $str, $match)) {
                $token = $this->token($type, $match);

                $str = substr($str, strlen($token['match']));

                $tokens = array_merge($tokens, $token['tokens']);

                if (empty($str)) {
                    break;
                }

                reset($rx);
            } else {
                next($rx);
            }
        }

        if (!empty($str)) {
            $tokens[] = ['type' => 'unknown', 'value' => $str[0]];

            $tokens = array_merge($tokens, $this->parse(substr($str, 1)));
        }

        return $tokens;
    }

    public function tokenize($str)
    {
        return $this->parse($str);
    }
}
