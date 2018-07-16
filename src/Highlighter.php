<?php

namespace Highlight;

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

    public function __construct(LanguageInterface $tokenizer, RenderInterface $render)
    {
        $this->tokenizer = $tokenizer;
        $this->render = $render;
    }

    /**
     * @param \string $str
     *
     * @return \string
     */
    public function highlight($str)
    {
        return
            $this->render->render(
                $this->tokenizer->format(
                    $this->tokenizer->tokenize(
                        $str
                    )
                )
            );
    }

    /**
     * @param \string $tokenizerClass
     * @param \string $renderClass
     * @param array   $options
     *
     * @return \Highlight\Highlighter
     */
    public static function factory($tokenizerClass, $renderClass, array $options = [])
    {
        return new self(new $tokenizerClass($options), new $renderClass($options));
    }
}
