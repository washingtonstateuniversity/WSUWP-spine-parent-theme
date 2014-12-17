# Spine Parent Theme - Builder Templates

The WSUWP Platform makes use of the page builder functionality found in the open source theme [Make](https://thethemefoundry.com/wordpress-themes/make/), by [The Theme Foundry](thethemefoundry.com).

This functionality is extended through the use of admin and front-end templates that are used when creating content through the page builder.

## Admin Templates

Templates in the `builder-templates/admin/` directory provide the layout and functionality that will be used for adding section types and adding pieces of content.

## Front End Templates

Templates in the `builder-templates/front-end/` directory provide templates for the views of each section on the front end.

These are used when saving pages to compile the final content that will be stored in the database for display on output. If a front-end template changes, the page must be resaved to properly rebuild that content.

## Default Templates

* H1 Header
* Single
* Side Left (`side-left` or `margin-left` layouts)
* Side Right (`side-right` or `margin-right` layouts)
* Halves
* Thirds (`thirds` or `triptych` layouts)
* Quarters
* Banner (Slideshow)