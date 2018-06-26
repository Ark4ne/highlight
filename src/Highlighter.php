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

    public function highlight(string $str): string
    {
        return $this->render->render($this->tokenizer->tokenize($str));
    }

    public static function factory(string $tokenizerClass, string $renderClass): self
    {
        return new self(new $tokenizerClass, new $renderClass);
    }
}
