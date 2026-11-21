import SwiftUI

/// `<native:parallax>`: children that move slower than the page scrolls.
///
/// The container scrolls with the page. Each frame it reads its own position on screen, and how far it moved up
/// from where it first appeared; its children follow only `speed` of that, so they slide out from under its bottom
/// edge. Pulling the page down past the top stretches the children upward instead (`stretch`, on by default).
///
/// The clip cuts the bottom and the sides only: a child pushed down never shows over the content below, while a
/// stretched child may still reach above the container.
struct ParallaxRenderer: View {
    let node: NativeUINode

    /// Where the container sat when it first appeared, in screen coordinates.
    @State private var restY: CGFloat? = nil

    var body: some View {
        let speed = CGFloat(min(max(node.props.getFloat("speed", default: 0.5), 0), 1))
        let stretch = node.props.getBool("stretch", default: true)

        GeometryReader { geo in
            let minY = geo.frame(in: .global).minY
            let rest = restY ?? minY
            // Positive once the page scrolled up, negative while it is pulled down past the top.
            let scrolled = rest - minY
            let pull = stretch ? max(-scrolled, 0) : 0

            ZStack {
                ForEach(node.children) { child in
                    RenderNode(node: child)
                }
            }
            .frame(width: geo.size.width, height: geo.size.height + pull)
            .offset(y: scrolled > 0 ? scrolled * (1 - speed) : -pull)
            .onAppear {
                if restY == nil {
                    restY = minY
                }
            }
        }
        .clipShape(OpenTopRectangle())
    }
}

/// The container's rectangle with no top edge: everything below or beside the container is cut, nothing above it.
private struct OpenTopRectangle: Shape {
    func path(in rect: CGRect) -> Path {
        Path(CGRect(x: rect.minX, y: rect.minY - 10_000, width: rect.width, height: rect.height + 10_000))
    }
}
