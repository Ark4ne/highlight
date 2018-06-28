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
     *
     * @return \string
     */
    public function highlight(string $str): string
    {
        return $this->render->render($this->tokenizer->tokenize($str));
    }

    /**
     * @param \string $tokenizerClass
     * @param \string $renderClass
     *
     * @return \Highlight\Highlighter
     */
    public static function factory(string $tokenizerClass, string $renderClass): self
    {
        return new self(new $tokenizerClass, new $renderClass);
    }
}
