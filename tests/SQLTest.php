<?php

namespace Test;

use Highlight\Tokenizer\SQL;
use Highlight\TokenizerInterface as Token;
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
                [Token::TOKEN_KEY, 'SELECT'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'FROM'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "SELECT tab.col, tab.col_a FROM tab WHERE col >= 123 ORDER BY col_a" => [
                [Token::TOKEN_KEY, 'SELECT'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'col'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'col_a'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'FROM'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'WHERE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '>='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '123'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'ORDER BY'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col_a'],
            ],
            "SELECT tab.col, tab.col_a FROM tab WHERE col >= 123 AND col_a LIKE '%abc%' AND col_b IN(:abc) ORDER BY col_a" => [
                [Token::TOKEN_KEY, 'SELECT'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'col'],
                [Token::TOKEN_PUNCTUATION, ','],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'col_a'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'FROM'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'WHERE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '>='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '123'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'AND'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col_a'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'LIKE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_STRING, "'%abc%'"],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'AND'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col_b'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'IN'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_STRING, ":abc"],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'ORDER BY'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'col_a'],
            ],
            "SELECT COUNT(*) as nb FROM tab" => [
                [Token::TOKEN_KEY, 'SELECT'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_FUNCTION, 'COUNT'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_OPERATOR, '*'],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'as'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'nb'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'FROM'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "SELECT My_FUNC(*) as nb FROM tab" => [
                [Token::TOKEN_KEY, 'SELECT'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'My_FUNC'],
                [Token::TOKEN_PUNCTUATION, '('],
                [Token::TOKEN_OPERATOR, '*'],
                [Token::TOKEN_PUNCTUATION, ')'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'as'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'nb'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'FROM'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'tab'],
            ],
            "UPDATE LOW_PRIORITY DELAYED `videos_index` # Update video index
SET `views` = 2.52 -- Set view
/* condition */
WHERE `video_id` = 123 AND `name` LIKE '⺇dz''d⺧'" => [
                [Token::TOKEN_KEY, 'UPDATE LOW_PRIORITY DELAYED'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, '`videos_index`'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_COMMENT, '# Update video index'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'SET'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, '`views`'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '2.52'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_COMMENT, '-- Set view'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_BLOCK_COMMENT, '/* condition */'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'WHERE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, '`video_id`'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '123'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'AND'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, '`name`'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'LIKE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_STRING, "'⺇dz''d⺧'"],
            ],
            "DELIMITER //
CREATE TRIGGER OR_VIDEOS_name BEFORE INSERT ON videos_table
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
                [Token::TOKEN_KEY, 'DELIMITER'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_PUNCTUATION, '//'],
                [Token::TOKEN_SPACE, "\n"],

                [Token::TOKEN_KEY, 'CREATE TRIGGER'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'OR_VIDEOS_name'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'BEFORE INSERT ON'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_table'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'FOR EACH ROW'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'BEGIN'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_SPACE,  '  '],
                [Token::TOKEN_KEY, 'UPDATE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'atable'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'SET'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_count'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_count'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '+'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '1'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'WHERE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'id'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'NEW'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'id'],
                [Token::TOKEN_PUNCTUATION, ';'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'END'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_PUNCTUATION, '//'],

                [Token::TOKEN_SPACE, "\n\n"],

                [Token::TOKEN_KEY, 'CREATE TRIGGER'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'DELETE_VIDEOS_name'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'AFTER DELETE ON'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_table'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'FOR EACH ROW'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'BEGIN'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_SPACE,  '  '],
                [Token::TOKEN_KEY, 'UPDATE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'atable'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'SET'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_count'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'videos_count'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '-'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_INT, '1'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_KEY, 'WHERE'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_VAR, 'id'],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_OPERATOR, '='],
                [Token::TOKEN_SPACE, ' '],
                [Token::TOKEN_NAMESPACE, 'OLD'],
                [Token::TOKEN_PUNCTUATION, '.'],
                [Token::TOKEN_VAR, 'id'],
                [Token::TOKEN_PUNCTUATION, ';'],
                [Token::TOKEN_SPACE, "\n"],
                [Token::TOKEN_KEY, 'END'],
                [Token::TOKEN_SPACE, "\n"],
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
        SQL::$style = SQL::STYLE_NONE;

        $this->assertEquals($tokens, (new SQL())->tokenize($str));
    }
}
