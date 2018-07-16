<?php

namespace Highlight;

/**
 * Interface RenderInterface
 *
 * @package Highlight\Renders
 */
interface RenderInterface
{
    public function __construct(array $options = []);

    /**
     * Render an list of tokens
     *
     * @param array $tokens
     *
     * @return string
     */
    public function render(array $tokens);
}
