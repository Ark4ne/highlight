<?php

namespace Test;

use Highlight\Tokenizer\PHP;
use Highlight\TokenizerInterface as Token;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPTest
 *
 * @package     Test
 */
class PHPTest extends TestCase
{
    public function dataTestSimple(){
        $datas = [
            '$a = 123;'         => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_INT, "123"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = "ab\\"c";'    => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_STRING, '"ab\"c"'],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = \'ab\\\'c\';' => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_STRING, "'ab\'c'"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = [];'          => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "[];"],

            ],
            '$a ();'            => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "();"],

            ],
            'a ();'             => [
                [Token::TOKEN_WORD, 'a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ");"],

            ],
            'Abc\a ();'         => [
                [Token::TOKEN_NAMESPACE, 'Abc\\'],
                [Token::TOKEN_WORD, 'a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ");"],

            ],
            '$a -> a ();'       => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "->"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_FUNCTION, "a"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ");"],

            ],
            '$a = new Abc\a;'   => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_KEY, "new"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_NAMESPACE, "Abc\\a"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a = new Abc\a();' => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_KEY, "new"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_NAMESPACE, "Abc\\"],
                [Token::TOKEN_WORD, "a"],
                [Token::TOKEN_PUNCTUATION, "("],
                [Token::TOKEN_PUNCTUATION, ");"],

            ],
            '$a = <<<EOF
Heredoc string
EOF;'                  => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_WORD, "<<<EOF" . "\n"],
                [Token::TOKEN_STRING, "Heredoc string" . "\n"],
                [Token::TOKEN_WORD, "EOF"],
                [Token::TOKEN_PUNCTUATION, ";"],

            ],
            '$a -> b;' => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "->"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_VAR, "b"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = __DIR__ . __FILE__;' => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_VAR, "__DIR__"],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "."],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_VAR, "__FILE__"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = A\B::$c;'            => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_NAMESPACE, "A\B"],
                [Token::TOKEN_PUNCTUATION, "::"],
                [Token::TOKEN_VAR, '$c'],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '$a = A\B::C;' => [
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_NAMESPACE, "A\B"],
                [Token::TOKEN_PUNCTUATION, "::"],
                [Token::TOKEN_VAR, 'C'],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '// a is true.
$a = true;'      => [
                [Token::TOKEN_COMMENT, '// a is true.'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_VAR, '$a'],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_PUNCTUATION, "="],
                [Token::TOKEN_SPACE, " "],
                [Token::TOKEN_KEY, "true"],
                [Token::TOKEN_PUNCTUATION, ";"],
            ],
            '/* Block Comment */
/**
 * Dob Block
 */' => [
                [Token::TOKEN_BLOCK_COMMENT, '/* Block Comment */'],
                [Token::TOKEN_SPACE, "\n"],
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
                    'type'  => Token::TOKEN_KEY,
                    'value' => '<?php',
                ], [
                    'type'  => Token::TOKEN_SPACE,
                    'value' => ' ',
                ],
            ],
            $tokens,
            [
                [
                    'type'  => Token::TOKEN_SPACE,
                    'value' => ' ',
                ], [
                    'type'  => Token::TOKEN_KEY,
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
            [Token::TOKEN_KEY, '<?='],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$this'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_VAR, 'prop'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_KEY, '?>'],
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
            [Token::TOKEN_KEY, '<?php'],
            [Token::TOKEN_SPACE, "\n\n"],
            [Token::TOKEN_KEY, 'namespace'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'Abc'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n\n"],
            [Token::TOKEN_KEY, 'use'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'B'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n\n"],
            [Token::TOKEN_BLOCK_COMMENT, '/**
 * Class C
 */'],
            [Token::TOKEN_SPACE, "\n"],
            [Token::TOKEN_KEY, 'class'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'C'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_KEY, 'extends'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_NAMESPACE, 'A'],
            [Token::TOKEN_SPACE, "\n"],
            [Token::TOKEN_PUNCTUATION, '{'],
            [Token::TOKEN_SPACE, "\n    "],
            [Token::TOKEN_KEY, 'const'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, 'CC'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_STRING, '"CC"'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n\n    "],
            [Token::TOKEN_KEY, 'private'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_KEY, 'static'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$cc'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_INT, '0xFF0000'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n\n    "],
            [Token::TOKEN_KEY, 'protected'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$b'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n\n    "],
            [Token::TOKEN_KEY, 'public'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_KEY, 'function'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_FUNCTION, '__construct'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_NAMESPACE, 'B'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$b'],
            [Token::TOKEN_PUNCTUATION, ','],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$opts'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '[])'],
            [Token::TOKEN_SPACE, "\n    "],
            [Token::TOKEN_PUNCTUATION, '{'],
            [Token::TOKEN_SPACE, "\n        "],
            [Token::TOKEN_KEY, 'parent'],
            [Token::TOKEN_PUNCTUATION, '::'],
            [Token::TOKEN_FUNCTION, '__construct'],
            [Token::TOKEN_PUNCTUATION, '('],
            [Token::TOKEN_VAR, '$opts'],
            [Token::TOKEN_PUNCTUATION, ');'],
            [Token::TOKEN_SPACE, "\n\n        "],
            [Token::TOKEN_VAR, '$this'],
            [Token::TOKEN_PUNCTUATION, '->'],
            [Token::TOKEN_VAR, 'b'],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_PUNCTUATION, '='],
            [Token::TOKEN_SPACE, ' '],
            [Token::TOKEN_VAR, '$b'],
            [Token::TOKEN_PUNCTUATION, ';'],
            [Token::TOKEN_SPACE, "\n    "],
            [Token::TOKEN_PUNCTUATION, '}'],
            [Token::TOKEN_SPACE, "\n"],
            [Token::TOKEN_PUNCTUATION, '}'],
        ];

        $this->assertTokenize($src, $this->expandTokens($tokens));
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
