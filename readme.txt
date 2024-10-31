=== Post Rotation ===

Contributors: digitalemphasis
Tags: automatic, post rotator, rotation, rotator, interval
Requires at least: 4.0
Tested up to: 5.7
Stable tag: 1.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Set the rotation interval or the allowed time without new posts... and automatically an older post becomes the latest one!


== Description ==

The main goal of this plugin is to avoid too much time without recent posts.
'Post Rotation' takes the oldest post that matches with your criteria and automatically converts it in the most recent one, as just published.

= Features =

* Easy configuration.
* You can specify the rotation interval or the allowed time without new posts.
* Enforce punctuality only if you want it.
* You can also choose if you want to alter the 'last_modified' value.
* You can exclude from rotation posts without featured image.
* You can activate a filter and select which categories will be affected and which ones will be ignored by the plugin.
* By default, the plugin works with the conventional 'post' type... but you can even rotate custom post types.
* Clean uninstall option: If this option is enabled, the plugin will leave absolutely no traces when uninstalling.
* Visit [digitalemphasis.com](https://digitalemphasis.com) for more info.


== Installation ==

1. Upload the 'post-rotation' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin through the 'Posts -> Post Rotation' administration panel.


== Frequently Asked Questions ==

= Will the rotation start once the plugin is activated? =
No. Firstly you need to visit the administration panel, enable the rotation, configure the settings and save the changes.

= Can I add a post after the rotation is activated? =
Sure! But if you don't want a delay in the rotation, the 'Fixed rotation' option must be checked.

= Should I check the 'Enforce punctuality' option? =
By default (unchecked), the new 'post_date' value will be your database's date and time when the rotation really occurs (when someone visits your site), resulting in some sort of human behaviour (humans usually don't write posts with such a precise interval). However, if this option is checked the new 'post_date' value will be precisely calculated.

= Do I need to select some category? =
If the 'Filter by category' option is checked, you should select some category and only posts that belongs to a selected category will rotate. Otherwise, with the 'Filter by category' option unchecked, the posts will be taken into consideration regardless of the category they belongs to.


== Screenshots ==

1. Post Rotation - administration panel


== Changelog ==

= 1.9 =
* Ensure compatibility with WordPress 5.2, 5.3, 5.4, 5.5, 5.6 and 5.7
* Fixed: 'A non-numeric value encountered in...' warning when 'pr_latest_rotation_time' has no value yet.
* The administration panel displays more accurate information.
* Code optimization.

= 1.8 =
* Ensure compatibility with WordPress 5.0 and 5.1
* New option: filter by category is optional from now on.
* Added support for custom post types.
* Code optimization.

= 1.7 =
* Ensure compatibility with WordPress 4.9
* New option: exclude posts without featured image.
* Improved adherence to WordPress Coding Standards.

= 1.6 =
* Ensure compatibility with WordPress 4.8
* Fixed: no more 'array_map(): Argument #2 should be an array...' warnings when no category is selected.
* Code optimization.

= 1.5 =
* Ensure compatibility with WordPress 4.6 and 4.7
* New option: you can specify a more accurate rotation interval using minutes.
* Fixed: from now on, the administration panel won't show pending rotations with negative time values anymore.
* Improved performance.
* Improved adherence to WordPress Coding Standards.
* Code optimization.

= 1.4 =
* Ensure compatibility with WordPress 4.5
* New option: enforce punctuality.
* Code optimization.

= 1.3 =
* Ensure compatibility with WordPress 4.4
* New option: fixed rotation.
* Fixed: characters of unexpected output during activation.
* Improved security: data escaping on output.
* Improved the HTML output.
* Code optimization.

= 1.2 =
* Ensure compatibility with WordPress 4.3
* Improved adherence to WordPress Coding Standards.

= 1.1 =
* Removed 'goto' from code.
* Minor code optimization.

= 1.0 =
* Initial release.
