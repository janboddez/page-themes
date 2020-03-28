# Page Themes
Assign a specific WordPress theme to any post or page.

## Gutenberg
This plugin uses WordPress's Meta Box API—supported by Gutenberg—to store
per-post theme settings, which makes it 100% compatible with the new block
editor.

## Menus, Widgets, and More
You may want to temporarily activate (e.g., on a staging site) each theme to
properly set up menus and widgets. (When viewing a page in the Customizer, you
should see the theme options for that page's theme, but the menu and widget
locations will likely be those of the overall site theme. This probably won't be
fixed ~~anytime soon~~ ever.)

Note that (using WP-CLI, for example) you _can_ copy theme settings to, e.g.,
_child themes_. Like, you could set up your overall site, then assign a child
theme to a specific page, copy over the parent theme's options (think widgets,
and menus), and then start customizing (like, assigning a different layout or
color scheme to said page). No need to temporarily mess up your entire site.

## Custom Post Types
As WordPress sets up the active theme (the thing we are overriding) before most
plugins define their [Custom Post Types](https://wordpress.org/support/article/post-types/),
there is no out-of-the-box support for them.
