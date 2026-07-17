# Hero

<!-- prettier-ignore-start -->

## What it does

Hero provides the default home-page hero widget: the large headline area at the top of a page. It can use a shared theme background or a page-specific background, image, or video treatment.

## Do I need to do anything?

Usually no. After installation, add the Hero widget in Layout Builder and set the page title, supporting content, and media treatment. For a new installation, the Hero setup command can install or rebuild the managed home-layout defaults.

## Where it shows up

In **Layout Builder**, as a Hero widget you can add and edit like any other widget. Its background defaults also appear in the theme settings.

## Good to know

- There is nothing to switch on; the Hero widget appears in the block list. Run `capell:hero-setup --force` only when you need to install or rebuild the Hero-managed home-layout defaults.
- Set shared background colour, overlay style, overlay opacity, and accent colours in the theme. A widget can override those defaults, and an individual hero slide can override the widget.
- Choose inherited, disabled, or custom media for a Hero. Custom media supports separate desktop, tablet, and mobile images and MP4 or WebM video uploads.
- For custom video, you can control autoplay, looping, muted playback, pause-when-out-of-view, and preload behaviour. Provide a responsive image as a useful visual fallback, then preview the public page at each breakpoint before publishing.

---

For developers: see the [README](../README.md).

<!-- prettier-ignore-end -->
