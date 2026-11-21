<?php

namespace Shortdramadb\MobileParallax\Elements;

use Native\Mobile\Edge\CallbackRegistry;
use Native\Mobile\Edge\Element;

/**
 * A container whose children move slower than the page scrolls: the parallax hero of a detail page.
 *
 *     <native:scroll-view>
 *         <native:parallax speed="0.5" class="w-full h-[520px]">
 *             <native:image :src="$poster" fit="cover" class="w-full h-full" />
 *         </native:parallax>
 *         ...
 *     </native:scroll-view>
 *
 * `speed` is the fraction of the scroll the children follow: 1 scrolls with the page, 0.5 at half speed, 0 stands
 * still. The container itself scrolls with the page and clips its children, so they slide out from under it. Pulling
 * the page down past the top stretches the children instead, as iOS headers do. The native side reads the container's
 * own position on screen each frame, so no scroll events travel to PHP.
 */
class Parallax extends Element
{
    protected string $type = 'parallax';

    /** @var array<string, mixed> */
    protected array $parallaxProps = ['speed' => 0.5];

    public static function make(Element ...$children): static
    {
        $element = new static;
        $element->children = $children;

        return $element;
    }

    /**
     * @param  array<string, mixed>  $attrs
     */
    public function applyAttributes(array $attrs): void
    {
        if (isset($attrs['speed'])) {
            $this->speed((float) $attrs['speed']);
        }

        if (array_key_exists('stretch', $attrs)) {
            $this->stretch(filter_var($attrs['stretch'], FILTER_VALIDATE_BOOL));
        }
    }

    /** The fraction of the scroll the children follow, between 0 and 1. */
    public function speed(float $speed): static
    {
        $this->parallaxProps['speed'] = max(0.0, min(1.0, $speed));

        return $this;
    }

    /** Stretch the children when the page is pulled down past the top (default on). */
    public function stretch(bool $stretch = true): static
    {
        $this->parallaxProps['stretch'] = $stretch;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolveProps(CallbackRegistry $registry): array
    {
        return $this->parallaxProps;
    }
}
