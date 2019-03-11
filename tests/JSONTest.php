<?php

namespace Test;

use Highlight\Languages\JSON;
use Highlight\Token;
use PHPUnit\Framework\TestCase;

class JSONTest extends TestCase
{

    public function dataTestSimple()
    {
        $datas = [
            '{"abc":123}' => [
                [Token::TOKEN_PUNCTUATION, '{'],
                [Token::TOKEN_VARIABLE, '"abc"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_NUMBER, '123'],
                [Token::TOKEN_PUNCTUATION, '}'],
            ],
            '{
    "true": true,
    "false": false,
    "null": null,
    "number": 123,
    "string": "ab\\"c",
    "array": [1, 2.2, 3.33],
    "object": {
        "prop1": "zer",
        "prop2": "tyu",
        "prop3": "iop"
    },
    "dir\"ty
    key":null
}' => [
                [Token::TOKEN_PUNCTUATION, '{'],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"true"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'true'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"false"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'false'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"null"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'null'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"number"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '123'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"string"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, '"ab\\"c"'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"array"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_PUNCTUATION, '['],
                [Token::TOKEN_NUMBER, '1'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '2.2'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '3.33'],
                [Token::TOKEN_PUNCTUATION, ']'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"object"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_PUNCTUATION, '{'],
                [Token::TOKEN_WHITESPACE, "\n        "],
                [Token::TOKEN_VARIABLE, '"prop1"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, '"zer"'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n        "],
                [Token::TOKEN_VARIABLE, '"prop2"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, '"tyu"'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n        "],
                [Token::TOKEN_VARIABLE, '"prop3"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, '"iop"'],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_PUNCTUATION, '}'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, "\n    "],
                [Token::TOKEN_VARIABLE, '"dir\"ty
    key"'],
                [Token::TOKEN_PUNCTUATION, ':'],
                [Token::TOKEN_KEYWORD, 'null'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_PUNCTUATION, '}'],
            ]
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
        $this->assertTokenize($str, $tokens);
    }

    private function assertTokenize($str, $tokens)
    {
        $this->assertEquals($tokens, (new JSON)->tokenize($str));
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
