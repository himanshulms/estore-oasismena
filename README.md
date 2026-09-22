# estore.oasismena

WordPress site for estore.oasismena, served locally from
`http://localhost/al_shirawi/estore-oasismena/`.

## Quick start

1. Create the database and import nothing - the install is already seeded:
   `al_shirawi_estoreoasismena`, table prefix `eo_`.
2. Copy `wp-config-sample.php` conventions from an existing environment, or ask
   a teammate for `wp-config.php` (it is gitignored).
3. `.htaccess` is gitignored too; its `RewriteBase` must be
   `/al_shirawi/estore-oasismena/`.

## Working on the theme

All project work happens in `wp-content/themes/estore-child/`.
`wp-content/themes/blankslate/` is the unmodified parent - do not edit it.

Styles are SCSS. After every change to `assets/scss/style.scss`, recompile:

    ./bin/build-css.sh

CSS drift is the single easiest thing to get wrong here, so the script also
warns if the compiled file is older than the source.

## Design

Figma: https://www.figma.com/design/zUw58tm0BPO6C8YlF7mJHx/estore.oasismena
