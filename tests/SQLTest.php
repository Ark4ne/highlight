<?php

namespace Test;

use Highlight\Languages\SQL;
use Highlight\Languages\SQL2;
use Highlight\Token;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLTest
 *
 * @package Test
 */
class SQLTest extends TestCase
{
    public function dataTestSimple(){
        $datas = [
            "SELECT col FROM tab" => [
                [Token::TOKEN_KEYWORD, 'SELECT'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'FROM'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "SELECT tab.col, tab.col_a FROM tab WHERE col >= 123 ORDER BY col_a" => [
                [Token::TOKEN_KEYWORD, 'SELECT'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'col'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'col_a'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'FROM'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'WHERE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '>='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '123'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'ORDER BY'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col_a'],
            ],
            "SELECT tab.col, tab.col_a FROM tab WHERE col >= 123 AND col_a LIKE '%abc%' AND col_b IN(:abc) ORDER BY col_a" => [
                [Token::TOKEN_KEYWORD, 'SELECT'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'col'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'col_a'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'FROM'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'WHERE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '>='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '123'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'AND'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col_a'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'LIKE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, "'%abc%'"],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'AND'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col_b'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'IN'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_STRING, ":abc"],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'ORDER BY'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'col_a'],
            ],
            "SELECT COUNT(*) as nb FROM tab" => [
                [Token::TOKEN_KEYWORD, 'SELECT'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_FUNCTION, 'COUNT'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_OPERATOR, '*'],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'as'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'nb'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'FROM'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "SELECT My_FUNC(*) as nb FROM tab" => [
                [Token::TOKEN_KEYWORD, 'SELECT'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'My_FUNC'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_OPERATOR, '*'],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'as'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'nb'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'FROM'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "UPDATE LOW_PRIORITY DELAYED `videos_index` # Update video index
SET `views` = 2.52 -- Set view
/* condition */
WHERE `video_id` = 123 AND `name` LIKE '⺇dz''d⺧'" => [
                [Token::TOKEN_KEYWORD, 'UPDATE LOW_PRIORITY DELAYED'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, '`videos_index`'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_COMMENT, '# Update video index'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'SET'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, '`views`'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '2.52'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_COMMENT, '-- Set view'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_BLOCK_COMMENT, '/* condition */'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'WHERE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, '`video_id`'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '123'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'AND'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, '`name`'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'LIKE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_STRING, "'⺇dz''d⺧'"],
            ],
            "DELIMITER //
CREATE TRIGGER INSERT_VIDEOS_name BEFORE INSERT ON videos_table
FOR EACH ROW
BEGIN
  UPDATE atable SET videos_count = videos_count + 1 WHERE id = NEW.id;
END
//

CREATE TRIGGER DELETE_VIDEOS_name AFTER DELETE ON videos_table
FOR EACH ROW
BEGIN
  UPDATE atable SET videos_count = videos_count - 1 WHERE id = OLD.id;
END
//" => [
                [Token::TOKEN_KEYWORD, 'DELIMITER'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_PUNCTUATION, '//'],
                [Token::TOKEN_WHITESPACE, "\n"],

                [Token::TOKEN_KEYWORD, 'CREATE TRIGGER'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'INSERT_VIDEOS_name'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'BEFORE INSERT ON'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_table'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'FOR EACH ROW'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'BEGIN'],
                [Token::TOKEN_WHITESPACE, "\n  "],
                [Token::TOKEN_KEYWORD, 'UPDATE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'atable'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'SET'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_count'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_count'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '+'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '1'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'WHERE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'id'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'NEW'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'id'],
                [Token::TOKEN_PUNCTUATION, ';'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'END'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_PUNCTUATION, '//'],

                [Token::TOKEN_WHITESPACE, "\n\n"],

                [Token::TOKEN_KEYWORD, 'CREATE TRIGGER'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'DELETE_VIDEOS_name'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'AFTER DELETE ON'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_table'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'FOR EACH ROW'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'BEGIN'],
                [Token::TOKEN_WHITESPACE, "\n  "],
                [Token::TOKEN_KEYWORD, 'UPDATE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'atable'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'SET'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_count'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'videos_count'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '-'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NUMBER, '1'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_KEYWORD, 'WHERE'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_VARIABLE, 'id'],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_WHITESPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'OLD'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VARIABLE, 'id'],
                [Token::TOKEN_PUNCTUATION, ';'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_KEYWORD, 'END'],
                [Token::TOKEN_WHITESPACE, "\n"],
                [Token::TOKEN_PUNCTUATION, '//'],
            ]
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
        $this->assertTokenize($str, $tokens);
    }

    private function assertTokenize($str, $tokens)
    {
        $this->assertEquals($tokens, (new SQL())->tokenize($str));
    }
}
