# Parallax for NativePHP Mobile

A native container whose children move slower than the page scrolls, for the hero image of a detail page.

```blade
<native:scroll-view class="w-full flex-1">
    <native:parallax speed="0.5" class="w-full h-[520px]">
        <native:image :src="$poster" fit="cover" class="w-full h-full" />
    </native:parallax>
    {{-- the rest of the page scrolls at full speed --}}
</native:scroll-view>
```

| Attribute | Default | Meaning |
| --- | --- | --- |
| `speed` | `0.5` | The fraction of the scroll the children follow: 1 scrolls with the page, 0 stands still. |
| `stretch` | `true` | iOS: pulling the page down past the top stretches the children upward. |

The container scrolls with the page and clips its children at its bottom edge, so they slide out from under it.
Each renderer reads the container's own position on screen every frame (SwiftUI `GeometryReader`, Compose
`onGloballyPositioned` with `graphicsLayer`), so no scroll events travel to PHP. Put overlays such as fades next to
it in a `stack`, not inside it, so they scroll at full speed.

Children are drawn by the app's own renderers (`RenderNode`), so any element works inside, such as `native:image`
from `nativephp/mobile-ui`.

## Install

```bash
composer require shortdramadb/nativephp-parallax
php artisan native:plugin:register shortdramadb/nativephp-parallax
```

The renderers are compiled into the app's own builds. The Jump preview app does not contain them, so the element
shows nothing there: keep that in mind when previewing with Jump.
