<?php

namespace Test;

use Highlight\Languages\PHP;
use Highlight\Token;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPTest
 *
 * @package     Test
 */
class PHPTest extends TestCase
{
    public function dataTestSimple()
    {
        $datas = [
            '$a = 123;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_NUMBER, "123"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = "ab\\"c";' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_STRING, '"ab\"c"'],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = \'ab\\\'c\';' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_STRING, "'ab\'c'"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = [];' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "["],
                [Token::TOKEN_PUNCTUATION, "]"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a ();' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ")"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            'a ();' => [
                [Token::TOKEN_WORD, 'a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ")"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            'Abc\a ();' => [
                [Token::TOKEN_NAMESPACE, 'Abc\\'],
                [Token::TOKEN_WORD, 'a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ")"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a -> a ();' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "->"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_FUNCTION, "a"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ")"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = new Abc\a;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_KEYWORD, "new"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_NAMESPACE, "Abc\\a"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = new Abc\a();' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_KEYWORD, "new"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_NAMESPACE, "Abc\\"],
                [Token::TOKEN_WORD, "a"],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ")"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = <<<EOF
Heredoc string
EOF;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_WORD, "<<<EOF" . "\n"],
                [Token::TOKEN_STRING, "Heredoc string" . "\n"],
                [Token::TOKEN_WORD, "EOF"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a -> b;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "->"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_VARIABLE, "b"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = __DIR__ . __FILE__;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_VARIABLE, "__DIR__"],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "."],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_VARIABLE, "__FILE__"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = A\B::$c;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_NAMESPACE, "A\B"],
                [Token::TOKEN_PUNCTUATION, ":"],
                [Token::TOKEN_PUNCTUATION, ":"],
                [Token::TOKEN_VARIABLE, '$c'],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = A\B::C;' => [
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_NAMESPACE, "A\B"],
                [Token::TOKEN_PUNCTUATION, "::"],
                [Token::TOKEN_VARIABLE, 'C'],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '// a is true.
$a = true;' => [
                [Token::TOKEN_COMMENT, '// a is true.'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_VARIABLE, '$a'],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_WHITESPACE, " "],
                [Token::TOKEN_KEYWORD, "true"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '/* Block Comment */
/**
 * Dob Block
 */' => [
                [Token::TOKEN_BLOCK_COMMENT, '/* Block Comment */'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_BLOCK_COMMENT, '/**
 * Dob Block
 */'],
            ],
            '~~' => [
                ['unknown', '~'],
                ['unknown', '~'],
            ],
        ];

        foreach ($datas as $k => $v) {
            $datas[$k] = [$k, $this->expandTokens($v)];
        }

        return $datas;
    }

    /**
     * @dataProvider dataTestSimple
     *
     * @param $str
     * @param $tokens
     */
    public function testSimple($str, $tokens)
    {
        $this->assertTokenize('<?php ' . $str . ' ?>', array_merge(
            [
                [
                    'type' => Token::TOKEN_KEYWORD,
                    'value' => '<?php',
                ], [
                'type' => Token::TOKEN_WHITESPACE,
                'value' => ' ',
            ],
            ],
            $tokens,
            [
                [
                    'type' => Token::TOKEN_WHITESPACE,
                    'value' => ' ',
                ], [
                'type' => Token::TOKEN_KEYWORD,
                'value' => '?>',
            ],
            ]
        ));
    }

    public function testPhpHtml()
    {
        $src = '<div attr="abc"><span><?= $this->prop ?></span></div>';

        $tokens = [
            ['unknown', '<div attr="abc"><span>'],
            [Token::TOKEN_KEYWORD, '<?='],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$this'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_VARIABLE, 'prop'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_KEYWORD, '?>'],
            ['unknown', '</span></div>'],
        ];

        $this->assertTokenize($src, $this->expandTokens($tokens));
    }

    public function testPhpClass()
    {
        $src = '<?php

namespace Abc;

use B;

/**
 * Class C
 */
class C extends A
{
    const CC = "CC";

    private static $cc = 0xFF0000;

    protected $b;

    public function __construct(B $b, $opts = [])
    {
        parent::__construct($opts);

        $this->b = $b;
    }
}';

        $tokens = [
            [Token::TOKEN_KEYWORD, '<?php'],
            [Token::TOKEN_WHITESPACE, "\n\n"],
            [Token::TOKEN_KEYWORD, 'namespace'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'Abc'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n"],
            [Token::TOKEN_KEYWORD, 'use'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'B'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n"],
            [Token::TOKEN_BLOCK_COMMENT, '/**
 * Class C
 */'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_KEYWORD, 'class'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'C'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_KEYWORD, 'extends'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'A'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_PUNCTUATION, '{'],
            [Token::TOKEN_WHITESPACE, "\n    "],
            [Token::TOKEN_KEYWORD, 'const'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, 'CC'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_STRING, '"CC"'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n    "],
            [Token::TOKEN_KEYWORD, 'private'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_KEYWORD, 'static'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$cc'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_NUMBER, '0xFF0000'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n    "],
            [Token::TOKEN_KEYWORD, 'protected'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$b'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n    "],
            [Token::TOKEN_KEYWORD, 'public'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_KEYWORD, 'function'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_FUNCTION, '__construct'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_NAMESPACE, 'B'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$b'],
            [Token::TOKEN_PUNCTUATION, ','],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$opts'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '['],
            [Token::TOKEN_PUNCTUATION, ']'],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_WHITESPACE, "\n    "],
            [Token::TOKEN_PUNCTUATION, '{'],
            [Token::TOKEN_WHITESPACE, "\n        "],
            [Token::TOKEN_KEYWORD, 'parent'],
            [Token::TOKEN_PUNCTUATION, '::'],
            [Token::TOKEN_FUNCTION, '__construct'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_VARIABLE, '$opts'],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n\n        "],
            [Token::TOKEN_VARIABLE, '$this'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_VARIABLE, 'b'],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_WHITESPACE, ' '],
            [Token::TOKEN_VARIABLE, '$b'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n    "],
            [Token::TOKEN_PUNCTUATION, '}'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_PUNCTUATION, '}'],
        ];

        $this->assertTokenize($src, $this->expandTokens($tokens));
    }

    public function testOther()
    {
        $src = '<?php
$this->{"get" . ucfirst("var")}();
$this->$$var;
echo $this->view->start()->render("front/index", "index")->finish()->getContent();';

        $tokens = [
            [Token::TOKEN_KEYWORD, '<?php'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_VARIABLE, '$this'],
            [Token::TOKEN_PUNCTUATION, '-'],
            [Token::TOKEN_PUNCTUATION, '>'],
            [Token::TOKEN_PUNCTUATION, '{'],
            [Token::TOKEN_STRING, '"get"'],
            [Token::TOKEN_WHITESPACE, " "],
            [Token::TOKEN_PUNCTUATION, '.'],
            [Token::TOKEN_WHITESPACE, " "],
            [Token::TOKEN_WORD, "ucfirst"],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_STRING, '"var"'],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, '}'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_VARIABLE, '$this'],
            [Token::TOKEN_PUNCTUATION, '-'],
            [Token::TOKEN_PUNCTUATION, '>'],
            [Token::TOKEN_KEYWORD, '$$var'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_WHITESPACE, "\n"],
            [Token::TOKEN_KEYWORD, 'echo'],
            [Token::TOKEN_WHITESPACE, " "],
            [Token::TOKEN_VARIABLE, '$this'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_VARIABLE, 'view'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_FUNCTION, 'start'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_FUNCTION, 'render'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_STRING, '"front/index"'],
            [Token::TOKEN_PUNCTUATION, ','],
            [Token::TOKEN_WHITESPACE, " "],
            [Token::TOKEN_STRING, '"index"'],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_FUNCTION, 'finish'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_FUNCTION, 'getContent'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_PUNCTUATION, ')'],
            [Token::TOKEN_PUNCTUATION, ';'],
        ];

        $this->assertTokenize($src, $this->expandTokens($tokens));
    }

    /**
     * for profile only
     */
    public function __testHugeFile()
    {
        $file = file_get_contents(__DIR__ . '/../vendor/phpunit/phpunit/src/Framework/TestCase.php');

        $start = microtime(true);
        $tokens = (new PHP)->tokenize($file);
        $time = microtime(true) - $start;

        $profiles = PHP::$profiles;

        foreach ($profiles as $type => &$profile) {
            $profile['token op/s'] = $profile['in'] ? $profile['in'] / $profile['time'] : 'n/a';
            $profile['preg_y op/s'] = $profile['in'] ? $profile['in'] / ($profile['preg_y']) : 'n/a';
            $profile['preg_n op/s'] = $profile['out'] ? $profile['out'] / ($profile['preg_n']) : 'n/a';
            $profile['preg op/s'] = ($profile['in'] + $profile['out']) ? ($profile['in'] + $profile['out']) / ($profile['preg_y'] + $profile['preg_n']) : 'n/a';
        }

        $max = [];

        foreach ($profiles as $type => &$profile) {
            foreach ($profile as $name => &$value) {
                if (is_numeric($value)) {
                    $value = number_format($value, is_int($value) ? 0 : 4, '.', ' ');
                }

                if (isset($max[$name])) {
                    $max[$name] = max(strlen($value), $max[$name]);
                } else {
                    $max[$name] = strlen($value);
                }
            }
        }

        foreach ($profiles as $type => $profile) {
            echo "- " . str_pad($type . ' ', array_sum($max) + (count($max) * 2) - 2, '-') . PHP_EOL;

            foreach (array_keys($profile) as $name) {
                echo "  " . str_pad($name, $max[$name]);
            }
            echo PHP_EOL;
            foreach ($profile as $name => $value) {
                echo "  " . str_pad($value, $max[$name]);
            }
            echo PHP_EOL;
        }

        $acceptable = 0.15; // PHP 7 : 75Kb file , max 150ms parsing

        if (PHP_MAJOR_VERSION == 5) {
            $acceptable = 0.25; // PHP 5 : 75Kb file , max 250ms parsing
        }

        $this->assertTrue($time < $acceptable, $time);
    }

    private function assertTokenize($str, $tokens)
    {
        $this->assertEquals($tokens, (new PHP)->tokenize($str));
    }

    private function expandTokens(array $tokens)
    {
        $_ = [];

        foreach ($tokens as $token) {
            $_[] = ['type' => $token[0], 'value' => $token[1]];
        }

        return $_;
    }
}
