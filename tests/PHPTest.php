<?php

namespace Test;

use Highlight\Highlighter;
use Highlight\Renders\Shell;
use Highlight\Tokenizer\PHP;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPTest
 *
 * @package     Test
 */
class PHPTest extends TestCase
{
    public function test()
    {
        $php = Highlighter::factory(PHP::class, Shell::class);

        echo $php->highlight('$a = 123;');
    }
}
