<?php

namespace Shortdramadb\MobileParallax;

use Illuminate\Support\ServiceProvider;

/**
 * The parallax element. NativePHP registers it from nativephp.json (its element, Blade component and the native
 * renderers in resources/ios and resources/android); the provider exists so the plugin can be registered.
 */
class ParallaxServiceProvider extends ServiceProvider
{
    public function register(): void {}
}
