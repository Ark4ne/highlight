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
            $tokens = [];
            foreach ($v as $tok) {
                $tokens[] = ['type' => $tok[0], 'value' => $tok[1]];
            }
            $datas[$k] = [$k, $tokens];
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
        $src = <<<PHP
<div attr="abc"><span><?= \$this->prop ?></span></div>
PHP;

        $tokens = [
            [
                'type' => 'unknown',
                'value' => '<div attr="abc"><span>'
            ],
            [
                'type' => Token::TOKEN_KEY,
                'value' => '<?='
            ],
            [
                'type' => Token::TOKEN_SPACE,
                'value' => ' '
            ],
            [
                'type' => Token::TOKEN_VAR,
                'value' => '$this'
            ],
            [
                'type' => Token::TOKEN_PUNCTUATION,
                'value' => '->'
            ],
            [
                'type' => Token::TOKEN_VAR,
                'value' => 'prop'
            ],
            [
                'type' => Token::TOKEN_SPACE,
                'value' => ' '
            ],
            [
                'type' => Token::TOKEN_KEY,
                'value' => '?>'
            ],
            [
                'type' => 'unknown',
                'value' => '</span></div>'
            ],
        ];

        $this->assertTokenize($src, $tokens);
    }

    private function assertTokenize($str, $tokens)
    {
        $this->assertEquals($tokens, (new PHP)->tokenize($str));
    }
}
