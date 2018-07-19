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

    /*private*/ const _x_ponc = '(?:\(|\)|\.|,|;)';

    const X_PONC = '^(' . self::_x_ponc . ')';

    /*private*/ const _x_create = '(?:CREATE|DROP|ALTER|RENAME)(?:\s+(?:DATABASE|EVENT|FUNCTION|LOGFILE|GROUP|PROCEDURE|SERVER|TABLE|TABLESPACE|VIEW|INDEX|TRIGGER))+';
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
        . 'ON DUPLICATE KEY UPDATE|INTERSECT|PARTITION|UNION ALL|DATABASE|GROUP BY|ORDER BY|EXCEPT|HAVING|OFFSET|SELECT|VALUES|LIMIT|UNION|WHERE|FROM|FOR EACH ROW|BEGIN|END|SET|AFTER)';

    /*private*/ const _x_keywords_ln = '(?:' . self::_x_keywords_top . '|REFERENCES|XOR|AND|OR|ADD)';

    /*private*/ const _x_keywords = '(?:' . self::_x_keywords_ln . '|HIGH_PRIORITY|LOW_PRIORITY|VARCHARACTER|CONSTRAINT|REFERENCES|MEDIUMBLOB|MEDIUMTEXT|VARBINARY|DUPLICATE|EXPANSION|INTERSECT|MEDIUMINT|DATABASE|DISTINCT|LANGUAGE|OPTIMIZE|SMALLTEXT|SMALLINT|TINYTEXT|TINYBLOB|LONGTEXT|LONGBLOB|UNSIGNED|VIRTUAL|TRIGGER|ANALYZE|BETWEEN|BOOLEAN|COMMENT|RESTRICT|CASCADE|DEFAULT|DELAYED|EXPLAIN|FOREIGN|INTEGER|NATURAL|PRIMARY|TINYINT|VARCHAR|BINARY|UNLOCK|BEFORE|USAGE|BIGINT|CREATE|DELETE|EXCEPT|GLOBAL|HAVING|INSERT|OFFSET|SELECT|UNIQUE|UPDATE|VALUES|INDEX|GROUP|DOUBLE|INNER|LIMIT|ORDER|OUTER|QUERY|RIGHT|TABLE|UNION|WHERE|FALSE|NAME|BLOB|CHAR|DESC|FROM|INTO|JOIN|LEFT|LIKE|MODE|NULL|TRUE|TEXT|WITH|ALL|ADD|AND|XOR|ASC|INT|KEY|NOT|SET|USE|AS|BY|IN|ON|OR|IS)';

    const X_KEYWORD = '(?:^(' . self::_x_keywords . ')((?:\s|' . self::_x_ponc . '))|^(' . self::_x_keywords . ')$)';

    /*private*/ const _x_operator = '(?:\|\*\=|\^\-\=|\<\=|\&\=|%\=|/\=|\*\=|\-\=|\+\=|\<\>|\>\=|\-|\<|\>|\=|\^|\&|%|/|\*|\+)';

    const X_OPERATOR = '^(' . self::_x_operator . ')';

    /*private*/ const _x_bind = '(?:(?:@|:)\w+:?|\?)';

    /*private*/ const _x_str = '(?:[\'"][\w\W]*)';

    /*private*/ const _x_number = '\d+(?:\.\d+)?';

    const X_VALUABLE = '^((?:' . self::_x_number . '|' . self::_x_str . '|' . self::_x_bind . '))';

    /*private*/ const _x_var = '`?(?:[a-zA-Z_]\w*)`?';

    const X_VAR = '^(' . self::_x_var . ')';

    const X_NAMESPACE = '^(?:(' . self::_x_var . '\.' . self::_x_var . ')|('
    . '((?:ALTER TABLE|FROM|TABLE|REFERENCES|' . self::_x_join . '|' . self::_x_insert . '|' . self::_x_update . ')\s+)(' . self::_x_var . ')))';
    //const X_NAMESPACE = '^(?:(' . self::_x_var . '\.' . self::_x_var . '))';

    /*private*/ const _x_func = '(?:GEOMETRYCOLLECTIONFROMTEXT|GEOMETRYCOLLECTIONFROMWKB|MULTILINESTRINGFROMTEXT|MULTILINESTRINGFROMWKB|MULTIPOLYGONFROMTEXT|UNCOMPRESSED_LENGTH|MULTIPOLYGONFROMWKB|MULTIPOINTFROMTEXT|GEOMETRYCOLLECTION|GROUP_UNIQUE_USERS|LINESTRINGFROMTEXT|CURRENT_TIMESTAMP|LINESTRINGFROMWKB|MULTIPOINTFROMWKB|GEOMCOLLFROMTEXT|NUMINTERIORRINGS|CHARACTER_LENGTH|GEOMETRYFROMTEXT|GEOMETRYFROMWKB|BDMPOLYFROMTEXT|POLYGONFROMTEXT|MULTILINESTRING|GEOMCOLLFROMWKB|MASTER_POS_WAIT|SUBSTRING_INDEX|POLYGONFROMWKB|LOCALTIMESTAMP|POINTONSURFACE|MPOINTFROMTEXT|LAST_INSERT_ID|UNIX_TIMESTAMP|BDMPOLYFROMWKB|BDPOLYFROMTEXT|MPOINTFROMWKB|CONNECTION_ID|POINTFROMTEXT|NUMGEOMETRIES|UTC_TIMESTAMP|MBRINTERSECTS|SYMDIFFERENCE|TIMESTAMPDIFF|INTERIORRINGN|MPOLYFROMTEXT|FROM_UNIXTIME|MLINEFROMTEXT|BDPOLYFROMWKB|OLD_PASSWORD|LINEFROMTEXT|MLINEFROMWKB|IS_USED_LOCK|IS_FREE_LOCK|EXTRACTVALUE|INTERSECTION|MULTIPOLYGON|MPOLYFROMWKB|GROUP_CONCAT|GEOMFROMTEXT|EXTERIORRING|GEOMETRYTYPE|OCTET_LENGTH|POINTFROMWKB|CURRENT_TIME|TIMESTAMPADD|SESSION_USER|RELEASE_LOCK|COERCIBILITY|CURRENT_DATE|UNIQUE_USERS|POLYFROMTEXT|CURRENT_USER|MBRDISJOINT|TIME_TO_SEC|CHAR_LENGTH|STR_TO_DATE|PERIOD_DIFF|FIND_IN_SET|SYSTEM_USER|LINEFROMWKB|SEC_TO_TIME|TIME_FORMAT|GEOMFROMWKB|STDDEV_SAMP|DATE_FORMAT|POLYFROMWKB|MICROSECOND|AES_DECRYPT|MBROVERLAPS|DES_ENCRYPT|DES_DECRYPT|AES_ENCRYPT|MBRCONTAINS|INTERSECTS|UNCOMPRESS|GET_FORMAT|WEEKOFYEAR|MBRTOUCHES|BIT_LENGTH|LINESTRING|NAME_CONST|PERIOD_ADD|STDDEV_POP|STARTPOINT|DIFFERENCE|DAYOFMONTH|EXPORT_SET|FOUND_ROWS|MULTIPOINT|CONVERT_TZ|CONVEXHULL|UPDATEXML|DIMENSION|NUMPOINTS|FROM_DAYS|DAYOFYEAR|LOCALTIME|LOAD_FILE|DATE_DIFF|DAYOFWEEK|INET_NTOA|INET_ATON|ROW_COUNT|COLLATION|TIMESTAMP|GEOMETRYN|MBRWITHIN|CONCAT_WS|SUBSTRING|MONTHNAME|BIT_COUNT|BENCHMARK|MBREQUAL|UTC_DATE|UTC_TIME|VARIANCE|OVERLAPS|MAKETIME|PASSWORD|MAKEDATE|TRUNCATE|POSITION|VAR_SAMP|TIMEDIFF|MAKE_SET|YEARWEEK|COMPRESS|ISSIMPLE|DATABASE|COALESCE|CONTAINS|ENVELOPE|ENDPOINT|CENTROID|DISTANCE|INTERVAL|ISCLOSED|GET_LOCK|DISJOINT|BOUNDARY|LAST_DAY|ASBINARY|DATEDIFF|DATE_ADD|DATE_SUB|GREATEST|QUARTER|DAYNAME|REVERSE|CONVERT|DEGREES|POLYGON|REPLACE|RELATED|EXTRACT|DEFAULT|CROSSES|CURDATE|SOUNDEX|ENCRYPT|RADIANS|AGAINST|GLENGTH|ISEMPTY|ADDDATE|WEEKDAY|VERSION|ADDTIME|VAR_POP|BIT_AND|TO_DAYS|TOUCHES|BIT_XOR|SYSDATE|ENGINE|SUBTIME|CHARSET|SUBDATE|CURTIME|CEILING|BIT_OR|CONCAT|REPEAT|SUBSTR|BUFFER|ASTEXT|SCHEMA|SECOND|STRCMP|STDDEV|WITHIN|LENGTH|POINTN|IFNULL|ENCODE|FORMAT|NULLIF|DECODE|MINUTE|LOCATE|INSERT|ISNULL|ISRING|EQUALS|FIELD|INSTR|LOWER|UPPER|UNHEX|ATAN2|LCASE|SPACE|FLOOR|LTRIM|UCASE|ASCII|MONTH|SLEEP|MATCH|QUOTE|RIGHT|POWER|LEAST|LOG10|CRC32|COUNT|ROUND|RTRIM|POINT|LEFT|ATAN|CASE|CAST|YEAR|TIME|CEIL|ACOS|TRIM|WEEK|CONV|CHAR|UUID|LOG2|SHA1|WHEN|THEN|SIGN|RPAD|SQRT|SRID|AREA|ASIN|USER|HOUR|RAND|DATE|LPAD|NOW|AVG|BIN|LOG|MIN|MAX|TAN|DAY|COT|POW|ORD|COS|OCT|ELT|EXP|SHA|SIN|STD|MOD|HEX|MID|MD5|SUM|ABS|LN|IF|PI)';

    const X_FUNC = '^(' . self::_x_func . ')(\s*(?:\(.+\))?)';

    const X_COMMENT = '^((?:#|--)[^\n\r]+|(/\*)[\w\W]+)';

    const X_DELIMITER = '^((DELIMITER)(\s+)([^\s]+))';

    const RX = [
        'space'     => self::X_WHITESPACE,
        'delimiter' => self::X_DELIMITER,
        'ponc'      => self::X_PONC,
        'namespace' => self::X_NAMESPACE,
        'key'       => self::X_KEYWORD,
        'func'      => self::X_FUNC,
        'var'       => self::X_VAR,
        'comment'   => self::X_COMMENT,
        'operator'  => self::X_OPERATOR,
        'value'     => self::X_VALUABLE,
    ];

    const FORMAT_NONE = 0;
    const FORMAT_COMPRESS = 1;
    const FORMAT_NESTED = 2;
    const FORMAT_EXPAND = 4;

    public static $delimiter = ';';

    private static $compute_delimiter = ';';

    private static $rx_delimiter = '^(;)';

    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Set SQL delimiter
     *
     * @param string $str
     */
    private function delimiter($str)
    {
        self::$compute_delimiter = $str;
        self::$rx_delimiter = '^(' . preg_quote($str, '~') . ')';
    }

    private function token($type, $match)
    {
        switch ($type) {
            case 'delimiter':
                $this->delimiter($match[4]);
                return [
                    ['type' => Token::TOKEN_KEYWORD, 'value' => $match[2]],
                    ['type' => Token::TOKEN_WHITESPACE, 'value' => $match[3]],
                    ['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[4]],
                ];
            case 'space':
                return [['type' => Token::TOKEN_WHITESPACE, 'value' => $match[1]]];
            case 'ponc':
                return [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[1]]];
            case 'key':
                if (!empty($match[1])) {
                    return array_merge(
                        [['type' => Token::TOKEN_KEYWORD, 'value' => preg_replace('~\s+~', ' ', $match[1])]],
                        $this->parse(substr($match[0], strlen($match[1])))
                    );
                }

                return [['type' => Token::TOKEN_KEYWORD, 'value' => $match[3]]];
            case 'operator':
                return [['type' => Token::TOKEN_OPERATOR, 'value' => $match[1]]];
            case 'var':
                return [['type' => Token::TOKEN_VARIABLE, 'value' => $match[1]]];
            case 'namespace':
                if (isset($match[4])) {
                    if (preg_match('~^' . self::X_KEYWORD . '~i', $match[4])) {
                        return array_merge(
                            $this->parse($match[3]),
                            [['type' => Token::TOKEN_KEYWORD, 'value' => $match[4]]]
                        );
                    }
                    if (preg_match('~^' . self::X_KEYWORD . '~i', $match[3], $m)) {
                        return array_merge(
                            $this->token('key', $m),
                            [['type' => Token::TOKEN_NAMESPACE, 'value' => $match[4]]]
                        );
                    }

                    return array_merge(
                        $this->parse($match[3]),
                        [['type' => Token::TOKEN_NAMESPACE, 'value' => $match[4]]]
                    );
                }

                $parts = explode('.', $match[0]);

                return [
                    ['type' => Token::TOKEN_NAMESPACE, 'value' => $parts[0]],
                    ['type' => Token::TOKEN_PUNCTUATION, 'value' => '.'],
                    ['type' => Token::TOKEN_VARIABLE, 'value' => $parts[1]],
                ];
                break;
            case 'value':
                $str = $match[0];

                if (preg_match('&^' . self::_x_number . '$&', $str)) {
                    return [['type' => Token::TOKEN_NUMBER, 'value' => $str]];
                }
                if (preg_match('&^(' . self::_x_number . ')&', $str, $m)) {
                    return array_merge(
                        [['type' => Token::TOKEN_NUMBER, 'value' => $m[1]]],
                        $this->parse(substr($str, strlen($m[1])))
                    );
                }
                if (preg_match('&^(' . self::_x_bind . ')&', $str, $m)) {
                    return array_merge(
                        [['type' => Token::TOKEN_STRING, 'value' => $m[1]]],
                        $this->parse(substr($str, strlen($m[1])))
                    );
                }

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
                    return [['type' => Token::TOKEN_STRING, 'value' => $str]];
                }
                return array_merge(
                    [['type' => Token::TOKEN_STRING, 'value' => substr($str, 0, $next + 1)]],
                    $this->parse(substr($str, $next + 1))
                );
            case 'func':
                if (!empty($match[2])) {
                    return array_merge(
                        [['type' => Token::TOKEN_FUNCTION, 'value' => $match[1]]],
                        $this->parse($match[2])
                    );
                }

                return [['type' => Token::TOKEN_FUNCTION, 'value' => $match[1]]];
            case 'comment':
                if (!empty($match[2])) {
                    $stop = strpos($match[0], '*/');

                    return array_merge(
                        [['type' => Token::TOKEN_BLOCK_COMMENT, 'value' => substr($match[0], 0, $stop + 2)]],
                        $this->parse(substr($match[0], $stop + 2))
                    );
                }

                return [['type' => Token::TOKEN_COMMENT, 'value' => $match[1]]];
            case 'punc_delimiter':
                return [['type' => Token::TOKEN_PUNCTUATION, 'value' => $match[1]]];
        }

        return [['type' => 'unknown', 'value' => $match[0]]];
    }

    /**
     * Tokenize a string
     *
     * @param string $str
     *
     * @return array
     */
    private function parse($str)
    {
        if (empty($str)) {
            return [];
        }

        $tokens = [];

        $rx = ['punc_delimiter' => self::$rx_delimiter] + self::RX;

        while ($item = current($rx)) {
            $type = key($rx);

            if (preg_match('~' . $item . '~i', $str, $match)) {
                $str = substr($str, strlen($match[0]));

                $tokens = array_merge($tokens, $this->token($type, $match));

                if (empty($str)) {
                    break;
                }

                $rx['punc_delimiter'] = self::$rx_delimiter;
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

    /**
     * Tokenize and pre format a SQL string
     *
     * @param string $str
     *
     * @return array
     */
    public function tokenize($str)
    {
        $this->delimiter(self::$delimiter);

        return $this->parse($str);
    }

    public function format(array $tokens)
    {
        $style = isset($this->options['format'])
            ? $this->options['format']
            : self::FORMAT_NONE;

        switch ($style) {
            case self::FORMAT_COMPRESS:
                $_tokens = [];
                foreach ($tokens as $token) {
                    if ($token['type'] == Token::TOKEN_WHITESPACE
                        || $token['type'] == Token::TOKEN_COMMENT
                        || $token['type'] == Token::TOKEN_BLOCK_COMMENT) {
                        continue;
                    }

                    if ($token['type'] == Token::TOKEN_PUNCTUATION) {
                        if ($token['value'] == ',' || $token['value'] == ')' || $token['value'] == '.') {
                            array_pop($_tokens);
                        }
                    }

                    $_tokens[] = $token;

                    if (!($token['type'] == Token::TOKEN_FUNCTION
                        || ($token['type'] == Token::TOKEN_PUNCTUATION
                            && ($token['value'] == '(' || $token['value'] == '.')))
                    ) {
                        $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                    }
                }
                return $_tokens;
            case self::FORMAT_NESTED:
            case self::FORMAT_EXPAND:
                $_tokens = [];

                foreach ($tokens as $token) {
                    if ($token['type'] != Token::TOKEN_WHITESPACE) {
                        $_tokens[] = $token;
                    }
                }

                $tokens = $_tokens;
                $_tokens = [];
                $indent = 0;
                $indents = [];
                $next_indent = false;

                foreach ($tokens as $idx => $token) {
                    switch ($token['type']) {
                        case Token::TOKEN_KEYWORD:
                            if ($token['value'] == 'END') {
                                do {
                                    $indent && $indent--;
                                } while ($indent && array_pop($indents) != 'BEGIN');
                                $_tokens = $this->ln($_tokens);
                                $next_indent = true;
                            } elseif (preg_match('~^(' . self::_x_keywords_top . ')~i', $token['value'])
                                && !(
                                    ($upper = strtoupper($token['value']))
                                    && ($upper == 'UPDATE' || $upper == 'DELETE')
                                    && ($prev = (isset($tokens[$idx - 1]) ? $tokens[$idx - 1] : null))
                                    && $prev['type'] == Token::TOKEN_KEYWORD
                                )
                            ) {
                                $_tokens = $this->ln($_tokens);
                                $next_indent = true;
                                $indent && $indent--;
                                array_pop($indents);
                            } elseif (preg_match('~^(' . self::_x_keywords_ln . ')~i', $token['value'])) {
                                $_tokens = $this->ln($_tokens);
                                $next_indent = true;
                            }
                            break;
                        case Token::TOKEN_PUNCTUATION:
                            if ($token['value'] == ')') {
                                if (array_pop($indents) == 'indent') {
                                    $indent--;
                                    if ($style == self::FORMAT_EXPAND) {
                                        $_tokens = $this->ln($_tokens);
                                        $next_indent = true;
                                    }
                                }
                            } elseif ($token['value'] == self::$compute_delimiter) {
                                $indent = 0;
                                $indents = [];
                            }
                    }

                    if ($next_indent) {
                        $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => str_repeat(' ', $indent * 4)];
                        $next_indent = false;
                    }

                    $_tokens[] = $token;

                    switch ($token['type']) {
                        case Token::TOKEN_FUNCTION:
                            if (($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null)) && !($next['value'] == '(')) {
                                $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                            }
                            break;
                        case Token::TOKEN_KEYWORD:
                            if (preg_match('~^(' . self::_x_keywords_top . ')~i', $token['value'])) {
                                if ($style == self::FORMAT_EXPAND) {
                                    $_tokens = $this->ln($_tokens);
                                    $next_indent = true;
                                } else {
                                    $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                                }
                                $indent++;
                                $indents[] = $token['value'];
                            } elseif (($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null))
                                && !($next['value'] == ','
                                    || $next['value'] == ')'
                                    || $next['value'] == '.'
                                    || $next['value'] == ';')) {
                                $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                            }
                            break;
                        case Token::TOKEN_COMMENT:
                            $_tokens = $this->ln($_tokens);
                            $next_indent = true;
                            break;
                        case Token::TOKEN_PUNCTUATION:
                            switch ($token['value']) {
                                case self::$compute_delimiter;
                                    $indents = [];
                                    $indent = 0;
                                    $_tokens = $this->ln($_tokens);
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
                                        $_tokens = $this->ln($_tokens);
                                        $next_indent = true;
                                    }
                                    break;
                                case ',':
                                    switch (isset($indents[count($indents) - 1]) ? $indents[count($indents) - 1] : null) {
                                        case 'indent':
                                        default:
                                            if ($style == self::FORMAT_EXPAND
                                                && ($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null))
                                                && !($next['type'] == Token::TOKEN_NUMBER
                                                    || $next['type'] == Token::TOKEN_STRING
                                                    || $next['type'] == Token::TOKEN_COMMENT)) {
                                                $_tokens = $this->ln($_tokens);
                                                $next_indent = true;
                                                break;
                                            } elseif ($style == self::FORMAT_NESTED
                                                && ($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null))
                                                && !($next['type'] == Token::TOKEN_NUMBER
                                                    || $next['type'] == Token::TOKEN_STRING
                                                    || $next['type'] == Token::TOKEN_COMMENT)) {
                                                for ($jdx = $idx - 1; isset($tokens[$jdx]); $jdx--) {
                                                    if ($tokens[$jdx]['value'] == ','
                                                        || $tokens[$jdx]['value'] == '(') {
                                                        break;
                                                    }
                                                    if ($tokens[$jdx]['type'] == Token::TOKEN_KEYWORD
                                                        && !preg_match('~^(' . self::_x_keywords_ln . ')~i', $tokens[$jdx]['value'])) {
                                                        $_tokens = $this->ln($_tokens);
                                                        $next_indent = true;
                                                        break 2;
                                                    }
                                                }
                                            }
                                        case 'inline':
                                            $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                                    }
                                    break;
                                case ')':
                                default:
                                    if (($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null))
                                        && !($next['value'] == ','
                                            || $next['value'] == ')'
                                            || $next['value'] == '.'
                                            || $next['value'] == ';')) {
                                        $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                                    }
                            }
                            break;
                        default:
                            if (($next = (isset($tokens[$idx + 1]) ? $tokens[$idx + 1] : null))
                                && !($next['value'] == ','
                                    || $next['value'] == ')'
                                    || $next['value'] == '.'
                                    || $next['value'] == ';')) {
                                $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => ' '];
                            }
                    }
                }
                return $_tokens;
                break;
            case self::FORMAT_NONE:
            default:
                return $tokens;
        }
    }

    private function ln(array $_tokens)
    {
        if (!empty($_tokens) && ($_tokens[count($_tokens) - 1]['value']) != PHP_EOL) {
            $_tokens[] = ['type' => Token::TOKEN_WHITESPACE, 'value' => PHP_EOL];
        }
        return $_tokens;
    }
}

