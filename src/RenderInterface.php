<?php

namespace Highlight;

/**
 * Interface RenderInterface
 *
 * @package Highlight\Renders
 */
interface RenderInterface
{
    public function render(array $tokens): string;
}
