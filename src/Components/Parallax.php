<?php

namespace Shortdramadb\MobileParallax\Components;

use Native\Mobile\Edge\Components\Native\NativeBladeComponent;

/** <native:parallax>: see Shortdramadb\MobileParallax\Elements\Parallax. */
class Parallax extends NativeBladeComponent
{
    protected function elementType(): string
    {
        return 'parallax';
    }
}
