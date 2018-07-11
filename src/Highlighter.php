<?php

namespace Highlight;

/**
 * Class Highlighter
 *
 * @package     Highlight
 */
class Highlighter
{
    /** @var \Highlight\TokenizerInterface */
    private $tokenizer;

    /** @var \Highlight\RenderInterface */
    private $render;

    public function __construct(TokenizerInterface $tokenizer, RenderInterface $render)
    {
        $this->tokenizer = $tokenizer;
        $this->render = $render;
    }

    /**
     * @param \string $str
     * @param array   $options
     *
     * @return \string
     */
    public function highlight($str, array $options = [])
    {
        return $this->render->render($this->tokenizer->tokenize($str), $options);
    }

    /**
     * @param \string $tokenizerClass
     * @param \string $renderClass
     *
     * @return \Highlight\Highlighter
     */
    public static function factory($tokenizerClass, $renderClass)
    {
        return new self(new $tokenizerClass, new $renderClass);
    }
}
