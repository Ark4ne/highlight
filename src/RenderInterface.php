<?php

namespace Highlight;

/**
 * Interface RenderInterface
 *
 * @package Highlight\Renders
 */
interface RenderInterface
{
    /**
     * Render an list of tokens
     *
     * @param array $tokens
     * @param array $options
     *
     * @return string
     */
    public function render(array $tokens, array $options = []);
}
