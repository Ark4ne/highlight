<?php

namespace Test;

use Highlight\Highlighter;
use Highlight\Renders\Shell;
use Highlight\Tokenizer\SQL;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLTest
 *
 * @package Test
 */
class SQLTest extends TestCase
{
    public function test()
    {
        $sql = Highlighter::factory(SQL::class, Shell::class);

        echo $sql->highlight("SELECT `actors`.`id`, `actors`.`name`, `actors`.`slug`, `actors`.`thumb`, `actors`.`gender`, `actors`.`videos_count`, `actors`.`views` 
FROM `actors` 
RIGHT JOIN `views_actors` ON `views_actors`.`actor_id` = `actors`.`id` 
WHERE (`actors`.`gender` = :gender) AND (`views_actors`.`date_view` >= :date_view) 
GROUP BY `views_actors`.`actor_id` 
ORDER BY SUM(`views_actors`.`views`) DESC");
        echo $sql->highlight("SELECT
  `actors`.`id`,   #  actor id
  `actors`.`name`, -- actor name
  `actors`.`slug`  /* actor slug */
FROM 
  `actors` /* table */
WHERE
  `actors`.`slug` = :query 
LIMIT :APL0");
        echo PHP_EOL . PHP_EOL;

        echo $sql->highlight("INSERT INTO `views_categories` (`category_id`, `date_view`, `views`) 
VALUES (2, '2018-06-26', 1), (3, '2018-06-26', 1), (60, '2018-06-26''', 1), (13, '2018-06-26', 1) 
ON DUPLICATE KEY UPDATE `views`=`views`+1");
        echo PHP_EOL . PHP_EOL;

        echo $sql->highlight("SELECT `video_id`, MATCH (`tx_index`) AGAINST ('foo bar' IN BOOLEAN MODE) AS `score` 
FROM `videos_index` 
ORDER BY `score` DESC, `video_id` DESC 
LIMIT 41");
        echo PHP_EOL . PHP_EOL;
    }
}
