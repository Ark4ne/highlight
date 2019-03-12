<?php

namespace Highlight;

use Highlight\SimpleCache\Memory;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Factory
 *
 * Highlight
 */
class Factory
{
    /**
     * @var CacheInterface|string
     */
    protected static $defaultCache = Memory::class;

    /**
     * @param CacheInterface|string $defaultCache
     *
     * @return CacheInterface|string
     */
    public static function setDefaultCache($defaultCache)
    {
        $cache = self::$defaultCache;

        self::$defaultCache = $defaultCache;

        return $cache;
    }

    /**
     * @param LanguageInterface|string $tokenizer
     * @param RenderInterface|string   $render
     * @param array                    $options
     *
     * @return \Highlight\Highlighter
     */
    public static function factory($tokenizer, $render, array $options = [])
    {
        if (self::$defaultCache && !(self::$defaultCache instanceof CacheInterface)) {
            $cache = self::$defaultCache;

            self::$defaultCache = new $cache();
        }
        if (!($tokenizer instanceof LanguageInterface)) {
            $tokenizer = new $tokenizer($options);
        }
        if (!($render instanceof RenderInterface)) {
            $render = new $render($options);
        }
        return new Highlighter($tokenizer, $render, self::$defaultCache);
    }
}
