<?php

namespace Highlight\Languages;

use Highlight\LanguageInterface;
use Highlight\Token;

/**
 * Class SQL
 *
 * @package Highlight\Tokenizer
 */
class SQL implements LanguageInterface
{

    /*private*/ const _x_whitespace = '\s+';

    const X_WHITESPACE = '^(' . self::_x_whitespace . ')';

    /*private*/ const _x_punc = '(?:\(|\)|\.|,|;)';

    const X_PUNC = '^(' . self::_x_punc . ')';

    /*private*/ const _x_create = '(?:CREATE|DROP|ALTER|RENAME)(?:\s+(?:DATABASE|EVENT|FUNCTION|LOGFILE|GROUP|PROCEDURE|SERVER|TABLE|TABLESPACE|VIEW|(?:(?:FULLTEXT|UNIQUE|SPATIAL)\s+)?INDEX|TRIGGER))+';
    /*private*/ const _x_insert = '(?:INSERT|REPLACE)(?:\s+(?:HIGH_PRIORITY|LOW_PRIORITY|DELAYED))*(?:\s+IGNORE)?\s+INTO';
    /*private*/ const _x_update = 'UPDATE(?:\s+(?:HIGH_PRIORITY|LOW_PRIORITY|DELAYED))*(?:\s+IGNORE)?';
    /*private*/ const _x_join = '(?:(?:RIGHT|LEFT|OUTER|INNER|CROSS)\s+)*JOIN';
    /*private*/ const _x_trigger = '(?:BEFORE|AFTER)\s+(?:INSERT|UPDATE|DELETE)\s+ON';

    /*private*/ const _x_keywords_top = '(?:'
        . self::_x_update . '|'
        . self::_x_insert . '|'
        . self::_x_join . '|'
        . self::_x_create . '|'
        . self::_x_trigger . '|'
        . 'ON\s+DUPLICATE\s+KEY\s+UPDATE|INTERSECT|PARTITION|UNION\s+ALL|DATABASE|GROUP\s+BY|ORDER\s+BY|EXCEPT|HAVING|OFFSET|SELECT|VALUES|VARIABLES|LIMIT|UNION|WHERE|FROM|FOR\s+EACH\s+ROW|BEGIN|END|SET|AFTER)';

    /*private*/ const _x_keywords_ln = '(?:' . self::_x_keywords_top . '|REFERENCES|XOR|AND|OR|ADD)';

    /*private*/ const _x_keywords = '(?:' . self::_x_keywords_ln . '|DELAY_KEY_WRITE|HIGH_PRIORITY|LOW_PRIORITY|VARCHARACTER|CONSTRAINT|REFERENCES|MEDIUMBLOB|MEDIUMTEXT|VARBINARY|DUPLICATE|EXPANSION|INTERSECT|MEDIUMINT|DATABASE|DISTINCT|LANGUAGE|OPTIMIZE|SMALLTEXT|SMALLINT|TINYTEXT|TINYBLOB|LONGTEXT|LONGBLOB|UNSIGNED|DESCRIBE|FULLTEXT|VIRTUAL|TRIGGER|ANALYZE|BETWEEN|BOOLEAN|COMMENT|RESTRICT|CASCADE|DEFAULT|DELAYED|COLLATE|EXPLAIN|FOREIGN|INTEGER|NATURAL|PRIMARY|TINYINT|VARCHAR|BINARY|UNLOCK|BEFORE|USAGE|BIGINT|CREATE|DELETE|EXCEPT|GLOBAL|HAVING|INSERT|EXISTS|IF\s+EXISTS|OFFSET|SELECT|UNIQUE|UPDATE|VALUES|INDEX|GROUP|DOUBLE|USING|INNER|LIMIT|ORDER|OUTER|QUERY|STATUS|RIGHT|TABLE|UNION|WHERE|FALSE|NAME|BLOB|CHAR|DESC|FROM|INTO|JOIN|LEFT|LIKE|MODE|SHOW|NULL|TRUE|TEXT|WITH|ALL|ADD|AND|XOR|ASC|INT|KEY|NOT|SET|USE|AS|BY|IN|ON|OR|IS)';

    const X_KEYWORD = '^(' . self::_x_keywords . ')\b';

    /*private*/ const _x_operator = '(?:\|\*\=|\^\-\=|\<\=|\&\=|%\=|/\=|\*\=|\-\=|\+\=|\<\>|\>\=|\-|\<|\>|\=|\^|\&|%|/|\*|\+)';

    const X_OPERATOR = '^(' . self::_x_operator . ')';

    /*private*/ const _x_bind = '(?:(?:@|:)\w+:?|\?)';

    /*private*/ const _x_str = '(?:[\'"][\w\W]*)';

    /*private*/ const _x_number = '\d+(?:\.\d+)?';

    CONST X_NUMBER = '^(' . self::_x_number . ')';
    CONST X_STRING = '^(' . self::_x_str . ')';
    CONST X_BIND = '^(' . self::_x_bind . ')';

    /*private*/ const _x_var = '`?(?:[a-zA-Z_]\w*)`?';

    const X_VAR = '^(' . self::_x_var . ')';

    const X_KEYWORDS_NS = '^(ALTER\s+TABLE|FROM|TABLE|REFERENCES|' . self::_x_join . '|' . self::_x_insert . '|' . self::_x_update . '|' . self::_x_create . ')$';
    const X_NAMESPACE = '^(' . self::_x_var . '\.' . self::_x_var . ')';
    //. '((?:ALTER TABLE|FROM|TABLE|REFERENCES|' . self::_x_join . '|' . self::_x_insert . '|' . self::_x_update . ')' . self::_x_whitespace . ')(' . self::_x_var . ')))';
    //const X_NAMESPACE = '^(?:(' . self::_x_var . '\.' . self::_x_var . '))';

    /*private*/ const _x_func = '(?:GEOMETRYCOLLECTIONFROMTEXT|GEOMETRYCOLLECTIONFROMWKB|MULTILINESTRINGFROMTEXT|MULTILINESTRINGFROMWKB|MULTIPOLYGONFROMTEXT|UNCOMPRESSED_LENGTH|MULTIPOLYGONFROMWKB|MULTIPOINTFROMTEXT|GEOMETRYCOLLECTION|GROUP_UNIQUE_USERS|LINESTRINGFROMTEXT|CURRENT_TIMESTAMP|LINESTRINGFROMWKB|MULTIPOINTFROMWKB|GEOMCOLLFROMTEXT|NUMINTERIORRINGS|CHARACTER_LENGTH|GEOMETRYFROMTEXT|GEOMETRYFROMWKB|BDMPOLYFROMTEXT|POLYGONFROMTEXT|MULTILINESTRING|GEOMCOLLFROMWKB|MASTER_POS_WAIT|SUBSTRING_INDEX|POLYGONFROMWKB|LOCALTIMESTAMP|POINTONSURFACE|MPOINTFROMTEXT|LAST_INSERT_ID|UNIX_TIMESTAMP|BDMPOLYFROMWKB|BDPOLYFROMTEXT|MPOINTFROMWKB|CONNECTION_ID|POINTFROMTEXT|NUMGEOMETRIES|UTC_TIMESTAMP|MBRINTERSECTS|SYMDIFFERENCE|TIMESTAMPDIFF|INTERIORRINGN|MPOLYFROMTEXT|FROM_UNIXTIME|MLINEFROMTEXT|BDPOLYFROMWKB|OLD_PASSWORD|LINEFROMTEXT|MLINEFROMWKB|IS_USED_LOCK|IS_FREE_LOCK|EXTRACTVALUE|INTERSECTION|MULTIPOLYGON|MPOLYFROMWKB|GROUP_CONCAT|GEOMFROMTEXT|EXTERIORRING|GEOMETRYTYPE|OCTET_LENGTH|POINTFROMWKB|CURRENT_TIME|TIMESTAMPADD|SESSION_USER|RELEASE_LOCK|COERCIBILITY|CURRENT_DATE|UNIQUE_USERS|POLYFROMTEXT|CURRENT_USER|MBRDISJOINT|TIME_TO_SEC|CHAR_LENGTH|STR_TO_DATE|PERIOD_DIFF|FIND_IN_SET|SYSTEM_USER|LINEFROMWKB|SEC_TO_TIME|TIME_FORMAT|GEOMFROMWKB|STDDEV_SAMP|DATE_FORMAT|POLYFROMWKB|MICROSECOND|AES_DECRYPT|MBROVERLAPS|DES_ENCRYPT|DES_DECRYPT|AES_ENCRYPT|MBRCONTAINS|INTERSECTS|UNCOMPRESS|GET_FORMAT|WEEKOFYEAR|MBRTOUCHES|BIT_LENGTH|LINESTRING|NAME_CONST|PERIOD_ADD|STDDEV_POP|STARTPOINT|DIFFERENCE|DAYOFMONTH|EXPORT_SET|FOUND_ROWS|MULTIPOINT|CONVERT_TZ|CONVEXHULL|UPDATEXML|DIMENSION|NUMPOINTS|FROM_DAYS|DAYOFYEAR|LOCALTIME|LOAD_FILE|DATE_DIFF|DAYOFWEEK|INET_NTOA|INET_ATON|ROW_COUNT|COLLATION|TIMESTAMP|GEOMETRYN|MBRWITHIN|CONCAT_WS|SUBSTRING|MONTHNAME|BIT_COUNT|BENCHMARK|MBREQUAL|UTC_DATE|UTC_TIME|VARIANCE|OVERLAPS|MAKETIME|PASSWORD|MAKEDATE|TRUNCATE|POSITION|VAR_SAMP|TIMEDIFF|MAKE_SET|YEARWEEK|COMPRESS|ISSIMPLE|DATABASE|COALESCE|CONTAINS|ENVELOPE|ENDPOINT|CENTROID|DISTANCE|INTERVAL|ISCLOSED|GET_LOCK|DISJOINT|BOUNDARY|LAST_DAY|ASBINARY|DATEDIFF|DATE_ADD|DATE_SUB|GREATEST|QUARTER|DAYNAME|REVERSE|CONVERT|DEGREES|POLYGON|REPLACE|RELATED|EXTRACT|DEFAULT|CROSSES|CURDATE|SOUNDEX|ENCRYPT|RADIANS|AGAINST|GLENGTH|ISEMPTY|ADDDATE|WEEKDAY|VERSION|ADDTIME|VAR_POP|BIT_AND|TO_DAYS|TOUCHES|BIT_XOR|SYSDATE|ENGINE|SUBTIME|CHARSET|SUBDATE|CURTIME|CEILING|BIT_OR|CONCAT|REPEAT|SUBSTR|BUFFER|ASTEXT|SCHEMA|SECOND|STRCMP|STDDEV|WITHIN|LENGTH|POINTN|IFNULL|ENCODE|FORMAT|NULLIF|DECODE|MINUTE|LOCATE|INSERT|ISNULL|ISRING|EQUALS|FIELD|INSTR|LOWER|UPPER|UNHEX|ATAN2|LCASE|SPACE|FLOOR|LTRIM|UCASE|ASCII|MONTH|SLEEP|MATCH|QUOTE|RIGHT|POWER|LEAST|LOG10|CRC32|COUNT|ROUND|RTRIM|POINT|LEFT|ATAN|CASE|CAST|YEAR|TIME|CEIL|ACOS|TRIM|WEEK|CONV|CHAR|UUID|LOG2|SHA1|WHEN|THEN|SIGN|RPAD|SQRT|SRID|AREA|ASIN|USER|HOUR|RAND|DATE|LPAD|NOW|AVG|BIN|LOG|MIN|MAX|TAN|DAY|COT|POW|ORD|COS|OCT|ELT|EXP|SHA|SIN|STD|MOD|HEX|MID|MD5|SUM|ABS|LN|IF|PI)\b';

    const X_FUNC = '^(' . self::_x_func . ')(\s*(?:\(.+\))?)';

    const X_COMMENT = '^((?:#|--)[^\n\r]+|(/\*)[\w\W]+)';

    const X_DELIMITER = '^((DELIMITER)(' . self::_x_whitespace . ')([^\s]+))';

    const RX = [
        'space'     => '~' . self::X_WHITESPACE . '~',
        'delimiter' => '~' . self::X_DELIMITER . '~',
        'punc'      => '~' . self::X_PUNC . '~',
        'namespace' => '~' . self::X_NAMESPACE . '~',
        'key'       => '~' . self::X_KEYWORD . '~i',
        'func'      => '~' . self::X_FUNC . '~i',
        'var'       => '~' . self::X_VAR . '~',
        'comment'   => '~' . self::X_COMMENT . '~',
        'operator'  => '~' . self::X_OPERATOR . '~',
        'number'    => '~' . self::X_NUMBER . '~',
        'string'    => '~' . self::X_STRING . '~',
        'bind'      => '~' . self::X_BIND . '~',
    ];

    const FORMAT_NONE = 0;
    const FORMAT_COMPRESS = 1;
    const FORMAT_NESTED = 2;
    const FORMAT_EXPAND = 4;

    private $delimiter = ';';

    private $computedDelimiter = ';';

    private $rxDelimiter = '~^(;)~';

    private $options = [];

    /**
     * LanguageInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        if (isset($options['delimiter'])) {
            $this->delimiter = $options['delimiter'];
        }
    }

    /**
     * Set SQL delimiter
     *
     * @param string $str
     */
    private function delimiter($str)
    {
        $this->computedDelimiter = $str;
        $this->rxDelimiter = '~^(' . preg_quote($str, '~') . ')~';
    }

    private function token($type, $match, &$previous = null)
    {
        switch ($type) {
            case 'delimiter':
                $this->delimiter($match[4]);
                return [
                    'match'  => $match[1],
                    'tokens' => [
                        ['type' => Token::TOKEN_KEYWORD, 'value' => $match[2]],
                        ['type' => Token::TOKEN_WHITESPACE, 'value' => $match[3]],
                        ['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[4]],
                    ],
                ];
            case 'punc_delimiter':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[1]]],
                ];
            case 'space':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_WHITESPACE, 'value' => $match[1]]],
                ];
            case 'punc':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[1]]],
                ];
            case 'operator':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_OPERATOR, 'value' => $match[1]]],
                ];
            case 'key':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_KEYWORD, 'value' => $match[1]]],
                ];
            case 'var':
                if ($previous && $previous['type'] === Token::TOKEN_KEYWORD) {
                    if (preg_match('~' . self::X_KEYWORDS_NS . '~i', $previous['value'])) {
                        return [
                            'match'  => $match[1],
                            'tokens' => [['type' => Token::TOKEN_NAMESPACE, 'value' => $match[1]]],
                        ];
                    }
                    if (preg_match('~(KEY|INDEX|CONSTRAINT)$~i', $previous['value'])) {
                        return [
                            'match'  => $match[1],
                            'tokens' => [['type' => Token::TOKEN_NAMESPACE, 'value' => $match[1]]],
                        ];
                    }
                }

                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_VARIABLE, 'value' => $match[1]]],
                ];
            case 'namespace':
                $parts = explode('.', $match[1]);
                return [
                    'match'  => $match[1],
                    'tokens' => [
                        ['type' => Token::TOKEN_NAMESPACE, 'value' => $parts[0]],
                        ['type' => Token::TOKEN_PUNCTUATION, 'value' => '.'],
                        ['type' => Token::TOKEN_VARIABLE, 'value' => $parts[1]]
                    ],
                ];
                break;
            case 'number':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_NUMBER, 'value' => $match[1]]],
                ];
            case 'bind':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_STRING, 'value' => $match[1]]],
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
                    && (
                        // "\" string escape
                        ($str[$next - 1] == '\\' && $str[$next - 2] != '\\')
                        // double sign " '' " escape
                        || (isset($str[$next + 1]) && $str[$next + 1] == $separator && $next++)
                    )
                );

                if ($next === false) {
                    return [
                        'match'  => $match[0],
                        'tokens' => [['type' => Token::TOKEN_STRING, 'value' => $match[0]]],
                    ];
                }

                return [
                    'match'  => $value = substr($str, 0, $next + 1),
                    'tokens' => [['type' => Token::TOKEN_STRING, 'value' => $value]],
                ];
            case 'func':
                return [
                    'match'  => $match[1],
                    'tokens' => [['type' => Token::TOKEN_FUNCTION, 'value' => $match[1]]],
                ];
                break;
            case 'comment':
                if (!empty($match[2])) {
                    $stop = strpos($match[0], '*/');

                    return [
                        'match'  => $value = substr($match[0], 0, $stop + 2),
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

    private function parse($str)
    {
        if (empty($str)) {
            return [];
        }

        $tokens = [];
        $ctokens = 0;
        $previous = null;

        $rx = ['punc_delimiter' => $this->rxDelimiter] + self::RX;

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

                    $rx['punc_delimiter'] = $this->rxDelimiter;
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
        $this->delimiter($this->delimiter);

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
        $style = isset($this->options['format'])
            ? $this->options['format']
            : self::FORMAT_NONE;

        switch ($style) {
            case self::FORMAT_COMPRESS:
                $formatted = [];
                foreach ($tokens as $token) {
                    if ($token['type'] == Token::TOKEN_KEYWORD) {
                        $token['value'] = preg_replace('/\s+/', ' ', $token['value']);
                    } elseif ($token['type'] == Token::TOKEN_WHITESPACE
                        || $token['type'] == Token::TOKEN_COMMENT
                        || $token['type'] == Token::TOKEN_BLOCK_COMMENT) {
                        continue;
                    }

                    if ($token['type'] == Token::TOKEN_PUNCTUATION) {
                        if ($token['value'] == ',' || $token['value'] == ')' || $token['value'] == '.') {
                            array_pop($formatted);
                        }
                    }

                    $formatted[] = $token;

                    if (!($token['type'] == Token::TOKEN_FUNCTION
                        || ($token['type'] == Token::TOKEN_PUNCTUATION
                            && ($token['value'] == '(' || $token['value'] == '.')))
                    ) {
                        $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                    }
                }
                return $formatted;
            case self::FORMAT_NESTED:
            case self::FORMAT_EXPAND:
                $toks = [];
                foreach ($tokens as $token) {
                    if ($token['type'] !== Token::TOKEN_WHITESPACE) {
                        $toks[] = $token;
                    }
                }

                $tokens = $toks;
                $formatted = [];
                $indent = 0;
                $indents = [];
                $indentSize = isset($this->options['indent'])
                    ? $this->options['indent']
                    : 4;
                $nextIndent = false;

                $prev = null;
                $next = null;

                foreach ($tokens as $idx => $token) {
                    $prev = isset($tokens[$idx - 1]) ? $tokens[$idx - 1] : null;
                    $next = isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null;

                    switch ($token['type']) {
                        case Token::TOKEN_KEYWORD:
                            $token['value'] = preg_replace('/\s+/', ' ', $token['value']);
                            if ($token['value'] == 'END') {
                                do {
                                    $indent && $indent--;
                                } while ($indent && array_pop($indents) != 'BEGIN');
                                $formatted = $this->ln($formatted);
                                $nextIndent = true;
                            } elseif (preg_match('~^(' . self::_x_keywords_top . ')~i', $token['value'])
                                && !(
                                    ($upper = strtoupper($token['value']))
                                    && ($upper == 'UPDATE' || $upper == 'DELETE')
                                    && $prev && $prev['type'] == Token::TOKEN_KEYWORD
                                )
                            ) {
                                $formatted = $this->ln($formatted);
                                $nextIndent = true;
                                $indent && $indent--;
                                array_pop($indents);
                            } elseif ($this->tokenIs(self::_x_keywords_ln, $token)) {
                                $formatted = $this->ln($formatted);
                                $nextIndent = true;
                            }
                            break;
                        case Token::TOKEN_PUNCTUATION:
                            if ($token['value'] == ')') {
                                if (array_pop($indents) == 'indent') {
                                    $indent--;
                                    if ($style == self::FORMAT_EXPAND) {
                                        $formatted = $this->ln($formatted);
                                        $nextIndent = true;
                                    }
                                }
                            } elseif ($token['value'] == $this->computedDelimiter) {
                                $indent = 0;
                                $indents = [];
                            }
                    }

                    if ($nextIndent) {
                        $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => str_repeat(' ', $indent * $indentSize)];
                        $nextIndent = false;
                    }

                    if ($prev && $this->canHasSpaceAfter($prev) && $this->canHasSpaceBefore($token)) {
                        $formatted[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                    }

                    $formatted[] = $token;

                    switch ($token['type']) {
                        case Token::TOKEN_KEYWORD:
                            if ($this->tokenIs(self::_x_keywords_top, $token)) {
                                if ($style == self::FORMAT_EXPAND) {
                                    $formatted = $this->ln($formatted);
                                    $nextIndent = true;
                                }
                                $indent++;
                                $indents[] = $token['value'];
                            }
                            break;
                        case Token::TOKEN_COMMENT:
                            $formatted = $this->ln($formatted);
                            $nextIndent = true;
                            break;
                        case Token::TOKEN_PUNCTUATION:
                            switch ($token['value']) {
                                case $this->computedDelimiter;
                                    $indents = [];
                                    $indent = 0;
                                    $formatted = $this->ln($formatted);
                                    break;
                                case '.';
                                    break;
                                case '(';
                                    $op = 0;
                                    $len = 0;
                                    $jump = 0;
                                    $list = true;
                                    $last = null;
                                    for ($jdx = $idx + 1; isset($tokens[$jdx]) && $len < 60 && $op < 2; $jdx++) {
                                        if ($tokens[$jdx]['value'] == '(') {
                                            $jump++;
                                            continue;
                                        } elseif ($tokens[$jdx]['value'] == ')') {
                                            if ($jump) {
                                                $jump--;
                                                continue;
                                            } else {
                                                break;
                                            }
                                        }
                                        if ($jump) {
                                            continue;
                                        }

                                        if (!($tokens[$jdx]['type'] == Token::TOKEN_NAMESPACE
                                            || $tokens[$jdx]['type'] == Token::TOKEN_VARIABLE
                                            || $tokens[$jdx]['type'] == Token::TOKEN_NUMBER
                                            || $tokens[$jdx]['type'] == Token::TOKEN_STRING
                                            || $tokens[$jdx]['type'] == Token::TOKEN_PUNCTUATION)) {
                                            $list = false;
                                        }

                                        if ($tokens[$jdx]['type'] == Token::TOKEN_OPERATOR) {
                                            $op++;
                                        } elseif ($tokens[$jdx]['value'] == ',') {
                                            if (!($tokens[$jdx - 1]['type'] == Token::TOKEN_NUMBER
                                                || $tokens[$jdx - 1]['type'] == Token::TOKEN_STRING)) {
                                                $op++;
                                            }
                                        } else {
                                            if ($tokens[$jdx]['type'] == Token::TOKEN_KEYWORD) {
                                                $op++;
                                            }
                                            $len += strlen($tokens[$jdx]['value']);
                                        }
                                    }

                                    if ($style == self::FORMAT_NESTED && $list) {
                                        $indents[] = 'inline';
                                    } elseif ($len < 60 && $op < 2) {
                                        $indents[] = 'inline';
                                    } else {
                                        $indents[] = 'indent';
                                        $indent++;
                                        $formatted = $this->ln($formatted);
                                        $nextIndent = true;
                                    }
                                    break;
                                case ',':
                                    $cindent = count($indents);
                                    switch (isset($indents[$cindent - 1]) ? $indents[$cindent - 1] : null) {
                                        case 'indent':
                                        default:
                                            if ($style == self::FORMAT_EXPAND
                                                && $next && !(
                                                    $next['type'] == Token::TOKEN_NUMBER
                                                    || $next['type'] == Token::TOKEN_STRING
                                                    || $next['type'] == Token::TOKEN_COMMENT)) {
                                                $formatted = $this->ln($formatted);
                                                $nextIndent = true;
                                                break;
                                            } elseif ($style == self::FORMAT_NESTED
                                                && $next && !(
                                                    $next['type'] == Token::TOKEN_NUMBER
                                                    || $next['type'] == Token::TOKEN_STRING
                                                    || $next['type'] == Token::TOKEN_COMMENT)) {
                                                for ($jdx = $idx - 1; isset($tokens[$jdx]); $jdx--) {
                                                    if ($tokens[$jdx]['value'] == ','
                                                        || $tokens[$jdx]['value'] == '(') {
                                                        break;
                                                    }
                                                    if ($tokens[$jdx]['type'] == Token::TOKEN_KEYWORD
                                                        && !$this->tokenIs(self::_x_keywords_ln, $tokens[$jdx])) {
                                                        $formatted = $this->ln($formatted);
                                                        $nextIndent = true;
                                                        break 2;
                                                    }
                                                }
                                            }
                                    }
                                    break;
                            }
                            break;
                    }
                }
                return $formatted;
                break;
            case self::FORMAT_NONE:
            default:
                return $tokens;
        }
    }

    private function tokenIs($rx, $token)
    {
        return preg_match('~^(' . $rx . ')~i', $token['value']);
    }

    private function canHasSpaceBefore($token)
    {
        switch ($token['value']) {
            case ',':
            case '.':
            case ')':
            case ';':
                return false;
        }

        return true;
    }

    private function canHasSpaceAfter($token)
    {
        if ($token['type'] == Token::TOKEN_FUNCTION) {
            return false;
        }
        switch ($token['value']) {
            case '.':
            case '(':
                return false;
        }

        return true;
    }

    private function ln(array $_tokens)
    {
        if (!empty($_tokens) && ($_tokens[count($_tokens) - 1]['value']) != PHP_EOL) {
            $_tokens[] = ['type' => 'ln', 'value' => PHP_EOL];
        }
        return $_tokens;
    }
}

