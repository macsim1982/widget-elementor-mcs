# Elementor MCS Widgets

Custom Elementor widgets for MCS projects.

## Description

This plugin registers custom Elementor widgets and a dynamic tag for MCS.
It includes:

- `Mon_Widget_Elementor`
- `List_Widget_Elementor`
- `Booking_List_Widget`
- `MCS_Dynamic_Tag`

## Features

- Automatic Elementor dependency detection
- Custom Elementor category `Mes Widgets MCS`
- Conditional registration of widget assets
- Secure output with proper escaping

## Installation

1. Copy the plugin folder into `wp-content/plugins/widget-elementor-mcs`
2. Activate the plugin from the WordPress admin
3. Make sure Elementor is installed and active

## Structure

- `widget-elementor-mcs.php` - main plugin bootstrap
- `widgets/class-mcs-base-widget.php` - shared widget base class
- `widgets/class-mon-widget.php` - sample widget
- `widgets/class-list-widget.php` - list widget
- `widgets/class-booking-list-widget.php` - booking widget
- `classes/class-mcs-dynamic-tag.php` - dynamic tag implementation
- `assets/js/` and `assets/css/` - optional widget assets

## Notes

- Widget classes now use a consistent asset handle prefix: `mcs-{slug}-{type}`
- The plugin waits for Elementor to be loaded before registering widgets
- The booking widget now escapes output and removes debug dumps

## Potential improvements

- Add PHPUnit tests if a WordPress test environment is available
- Add translations using `.pot` / `.po` / `.mo` files
- Add a dedicated editor stylesheet instead of inline CSS
