# Hero

<!-- prettier-ignore-start -->

Behind your headline, a video can play without sound. On a phone, visitors get a still photo if that's what you uploaded for small screens. This is the big banner at the top of a page, often called a hero, and it works just as well with one picture or a few rotating slides.

Set a background for your theme, the overall look of your site, and every page's banner follows it. One banner or one slide can still have its own picture, or none at all.

Videos are ordinary files you upload yourself (MP4 or WebM), with separate versions for big screens and phones. A YouTube or Vimeo link can't be used. The editor's defaults are that the video starts on its own, loops, stays silent and pauses by itself once someone scrolls past, and you can change each one. A pause button is there for anyone who'd like it still. If you upload a picture too, visitors see it while the video loads.

Slides change every eight seconds and wait while a mouse rests on them. With no slides added, the banner shows the hero title and text saved on the page itself.

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

## Media uploads and modes

Each upload explains whether local files are in use. Choose **Use custom media here** beside an upload to activate Custom mode for that theme, widget or slide. Switching to Inherit or Off retains saved files. Desktop, tablet and mobile uploads retain their existing fallback behaviour. Widget Inherit uses theme media; a theme set to Off also disables Custom widget media. Slide Inherit uses the resolved widget base; a Custom slide can replace that disabled base.

---

For developers: see the [README](../README.md).

<!-- prettier-ignore-end -->
