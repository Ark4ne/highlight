<?php

namespace Highlight;

use Psr\SimpleCache\CacheInterface;

/**
 * Class Highlighter
 *
 * @package     Highlight
 */
class Highlighter
{
    /** @var \Highlight\LanguageInterface */
    private $tokenizer;

    /** @var \Highlight\RenderInterface */
    private $render;

    /** @var \Psr\SimpleCache\CacheInterface|null */
    private $cache;

    /** @var string */
    private $key;

    public function __construct(
        LanguageInterface $tokenizer,
        RenderInterface $render,
        CacheInterface $cache = null
    ) {
        $this->tokenizer = $tokenizer;
        $this->render = $render;
        $this->cache = $cache;

        $this->key = get_class($tokenizer);
    }

    /**
     * @param \string $str
     *
     * @return \string
     */
    public function highlight($str)
    {
        $key = $this->key . md5($str);

        if ($this->cache && $this->cache->has($key)) {
            $tokens = $this->cache->get($key);
        } else {
            $tokens = $this->tokenizer->format($this->tokenizer->tokenize($str));

            if ($this->cache) {
                $this->cache->set($key, $tokens);
            }
        }

        return $this->render->render($tokens);
    }


    /**
     * @param \string $file
     *
     * @return \string
     */
    public function highlightFile($file)
    {
        $key = $this->key . $file;

        if ($this->cache && $this->cache->has($key)) {
            $tokens = $this->cache->get($key);
        } else {
            $tokens = $this->tokenizer->format($this->tokenizer->tokenize(file_get_contents($file)));

            if ($this->cache) {
                $this->cache->set($key, $tokens);
            }
        }

        return $this->render->render($tokens);
    }

    /**
     * @deprecated
     *
     * @param LanguageInterface|string $tokenizer
     * @param RenderInterface|string $render
     * @param array   $options
     *
     * @return \Highlight\Highlighter
     */
    public static function factory($tokenizer, $render, array $options = [])
    {
        return Factory::factory($tokenizer, $render, $options);
    }
}
