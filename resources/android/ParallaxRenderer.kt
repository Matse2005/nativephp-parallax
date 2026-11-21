package com.shortdramadb.plugins.mobile_parallax

import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableFloatStateOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clipToBounds
import androidx.compose.ui.graphics.graphicsLayer
import androidx.compose.ui.layout.onGloballyPositioned
import androidx.compose.ui.layout.positionInWindow
import com.nativephp.mobile.ui.nativerender.NativeUINode
import com.nativephp.mobile.ui.nativerender.RenderNode

/**
 * `<native:parallax>`: children that move slower than the page scrolls.
 *
 * The container scrolls with the page and clips its children. It reads its own position in the window whenever the
 * page moves; the children follow only `speed` of the distance it moved up from where it was first placed. The offset
 * is read inside graphicsLayer, so scrolling only redraws the layer and never recomposes. Android's own overscroll
 * stretches the page, so there is no separate stretch here.
 */
object ParallaxRenderer {
    @Composable
    fun Render(node: NativeUINode, modifier: Modifier) {
        val speed = node.props.getFloat("speed", 0.5f).coerceIn(0f, 1f)

        var restY by remember { mutableStateOf<Float?>(null) }
        var currentY by remember { mutableFloatStateOf(0f) }

        Box(
            modifier = modifier
                .clipToBounds()
                .onGloballyPositioned { coordinates ->
                    val top = coordinates.positionInWindow().y
                    if (restY == null) {
                        restY = top
                    }
                    currentY = top
                }
        ) {
            Box(
                modifier = Modifier
                    .fillMaxSize()
                    .graphicsLayer {
                        val scrolled = ((restY ?: currentY) - currentY).coerceAtLeast(0f)
                        translationY = scrolled * (1f - speed)
                    }
            ) {
                node.children.forEach { child ->
                    RenderNode(child)
                }
            }
        }
    }
}
