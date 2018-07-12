<?php

namespace Test;

use Highlight\Tokenizer\PHP;
use Highlight\TokenizerInterface;
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
            '$a = 123;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_INT,
                    'value' => "123"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = "ab\\"c";' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_STRING,
                    'value' => '"ab\"c"'
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = \'ab\\\'c\';' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_STRING,
                    'value' => "'ab\'c'"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = [];' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "[];"
                ]
            ],
            '$a ();' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "();"
                ]
            ],
            'a ();' => [
                [
                    'type' => TokenizerInterface::TOKEN_WORD,
                    'value' => 'a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "("
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ");"
                ]
            ],
            'Abc\a ();' => [
                [
                    'type' => TokenizerInterface::TOKEN_NAMESPACE,
                    'value' => 'Abc\\'
                ], [
                    'type' => TokenizerInterface::TOKEN_WORD,
                    'value' => 'a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "("
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ");"
                ]
            ],
            '$a -> a ();' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "->"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_FUNCTION,
                    'value' => "a"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "("
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ");"
                ]
            ],
            '$a = new Abc\a;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_KEY,
                    'value' => "new"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_NAMESPACE,
                    'value' => "Abc\\a"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = new Abc\a();' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_KEY,
                    'value' => "new"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_NAMESPACE,
                    'value' => "Abc\\"
                ], [
                    'type' => TokenizerInterface::TOKEN_WORD,
                    'value' => "a"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "("
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ");"
                ]
            ],
            '$a = <<<EOF
Heredoc string
EOF;' => [
                [
                    'type'  => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a',
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => " ",
                ], [
                    'type'  => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "=",
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => " ",
                ], [
                    'type'  => TokenizerInterface::TOKEN_WORD,
                    'value' => "<<<EOF" . PHP_EOL,
                ], [
                    'type'  => TokenizerInterface::TOKEN_STRING,
                    'value' => "Heredoc string" . PHP_EOL,
                ], [
                    'type'  => TokenizerInterface::TOKEN_WORD,
                    'value' => "EOF",
                ], [
                    'type'  => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";",
                ],
            ],
            '$a -> b;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "->"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => "b"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = __DIR__ . __FILE__;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => "__DIR__"
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "."
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => "__FILE__"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = A\B::$c;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_NAMESPACE,
                    'value' => "A\B"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "::"
                ], [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$c'
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '$a = A\B::C;' => [
                [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a'
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "="
                ], [
                    'type' => TokenizerInterface::TOKEN_SPACE,
                    'value' => " "
                ], [
                    'type' => TokenizerInterface::TOKEN_NAMESPACE,
                    'value' => "A\B"
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "::"
                ], [
                    'type' => TokenizerInterface::TOKEN_VAR,
                    'value' => 'C'
                ], [
                    'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";"
                ]
            ],
            '// a is true.
$a = true;' => [
                [
                    'type'  => TokenizerInterface::TOKEN_COMMENT,
                    'value' => '// a is true.',
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => PHP_EOL,
                ], [
                    'type'  => TokenizerInterface::TOKEN_VAR,
                    'value' => '$a',
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => " ",
                ], [
                    'type'  => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => "=",
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => " ",
                ], [
                    'type'  => TokenizerInterface::TOKEN_KEY,
                    'value' => "true",
                ], [
                    'type'  => TokenizerInterface::TOKEN_PUNCTUATION,
                    'value' => ";",
                ],
            ],
            '/* Block Comment */
/**
 * Dob Block
 */' => [
                [
                    'type'  => TokenizerInterface::TOKEN_BLOCK_COMMENT,
                    'value' => '/* Block Comment */',
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => PHP_EOL,
                ], [
                    'type'  => TokenizerInterface::TOKEN_BLOCK_COMMENT,
                    'value' => '/**
 * Dob Block
 */',
                ],
            ],
            '~~' => [
                [
                    'type'  => 'unknown',
                    'value' => '~',
                ],
                [
                    'type'  => 'unknown',
                    'value' => '~',
                ],
            ],
        ];

        foreach ($datas as $k => $v) {
            $datas[$k] = [$k, $v];
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
                    'type'  => TokenizerInterface::TOKEN_KEY,
                    'value' => '<?php',
                ], [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => ' ',
                ],
            ],
            $tokens,
            [
                [
                    'type'  => TokenizerInterface::TOKEN_SPACE,
                    'value' => ' ',
                ], [
                    'type'  => TokenizerInterface::TOKEN_KEY,
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
                'type' => TokenizerInterface::TOKEN_KEY,
                'value' => '<?='
            ],
            [
                'type' => TokenizerInterface::TOKEN_SPACE,
                'value' => ' '
            ],
            [
                'type' => TokenizerInterface::TOKEN_VAR,
                'value' => '$this'
            ],
            [
                'type' => TokenizerInterface::TOKEN_PUNCTUATION,
                'value' => '->'
            ],
            [
                'type' => TokenizerInterface::TOKEN_VAR,
                'value' => 'prop'
            ],
            [
                'type' => TokenizerInterface::TOKEN_SPACE,
                'value' => ' '
            ],
            [
                'type' => TokenizerInterface::TOKEN_KEY,
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
