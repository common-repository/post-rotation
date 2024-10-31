<?php
defined( 'ABSPATH' ) || die( 'Cannot access pages directly.' );

function convert( $seconds ) {
	$s = $seconds % 60;
	$m = floor( ( $seconds % 3600 ) / 60 );
	$h = floor( ( $seconds % 86400 ) / 3600 );
	$d = floor( $seconds / 86400 );

	return "$d days, $h hours, $m' and $s''";
}
?>

<div class="wrap">

<h2>Post Rotation</h2>

<?php settings_errors(); ?>

<form action="options.php" method="post">

<?php
settings_fields( 'pr-settings-group' );

if ( get_option( 'pr_enabled' ) ) {
	echo '<h3>Rotation is enabled</h3>' . "\n";

	$selected_post = selected_post();

	if ( ! $selected_post || pr_halt() ) {
		echo '<p class="detail">... but there is no post that matches with your criteria!</p>' . "\n";
	} else {
		global $discrepancy;
		global $pr_interval;

		switch ( $discrepancy >= $pr_interval ) {
			case true:
				echo '<p class="detail">A pending change has just been applied to a post.</p>' . "\n";
				break;

			case false:
				echo '<p class="detail">ID: ' . esc_html( $selected_post ) . '</p><p class="detail">Title: ' . esc_html( get_post( $selected_post )->post_title ) . '</p>' . "\n";
				echo '<p class="detail">In ' . esc_html( convert( $pr_interval - $discrepancy ) ) . ' the post will be ready to rotate.</p>' . "\n";
				break;
		}
	}
} else {
	echo '<h3>Rotation is disabled</h3>' . "\n";
}
?>

<table class="form-table">
<tr>
<td>
<input type="checkbox" id="pr-enabled" name="pr_enabled" value="1" <?php checked( get_option( 'pr_enabled' ) ); ?> />
<label for="pr-enabled">Enable rotation.</label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" id="pr-fixed" name="pr_fixed" value="1" <?php checked( get_option( 'pr_fixed' ) ); ?> />
<label for="pr-fixed">Fixed rotation.</label>
</td>
</tr>

<tr>
<td>
<p>Interval<span class="extended <?php echo get_option( 'pr_fixed' ) ? 'hidden' : ''; ?>"> without new posts</span>:</p>
<input type="number" min="0" max="999" id="pr-interval-hours" name="pr_interval[hours]" value="<?php echo esc_attr( get_option( 'pr_interval' )['hours'] ); ?>" />
<label>hours</label>
<br />
<input type="number" min="0" max="59" id="pr-interval-minutes" name="pr_interval[minutes]" value="<?php echo esc_attr( get_option( 'pr_interval' )['minutes'] ); ?>" />
<label>minutes</label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" id="pr-enforce-punctuality" name="pr_enforce_punctuality" value="1" <?php checked( get_option( 'pr_enforce_punctuality' ) ); ?> />
<label for="pr-enforce-punctuality">Enforce punctuality.</label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" id="pr-also-alter-last-modified" name="pr_also_alter_last_modified" value="1" <?php checked( get_option( 'pr_also_alter_last_modified' ) ); ?> />
<label for="pr-also-alter-last-modified">Also alter 'last_modified'.</label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" id="pr-exclude-if-no-featured-image" name="pr_exclude_if_no_featured_image" value="1" <?php checked( get_option( 'pr_exclude_if_no_featured_image' ) ); ?> />
<label for="pr-exclude-if-no-featured-image">Exclude if no featured image.</label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" id="pr-filter-by-category" name="pr_filter_by_category" value="1" <?php checked( get_option( 'pr_filter_by_category' ) ); ?> />
<label for="pr-filter-by-category">Filter by category.</label>
</td>
</tr>
</table>

<h3 id="included-categories" class="<?php echo get_option( 'pr_filter_by_category' ) ? '' : 'softened'; ?>">Included categories</h3>

<div id="categories-container" class="<?php echo get_option( 'pr_filter_by_category' ) ? '' : 'softened'; ?>">

<?php
$cat_args   = array(
	'hide_empty' => 0,
);
$categories = get_categories( $cat_args );

foreach ( $categories as $key => $value ) {
	echo '<div class="category-block">' . "\n";
	echo '<input type="checkbox" class="chkbx" id="pr-included-categories-' . esc_attr( $value->term_id ) . '" name="pr_included_categories[]" value="' . esc_attr( $value->term_id ) . '"';

	if ( in_array( $value->term_id, pr_included_categories(), true ) ) {
		echo ' checked="checked"';
	}

	echo ' />' . "\n";
	echo '<label for="pr-included-categories-' . esc_attr( $value->term_id ) . '">' . esc_html( $value->name ) . '</label>' . "\n";
	echo '</div>' . "\n";
}
?>

<div id="categories-container-mask" class="<?php echo get_option( 'pr_filter_by_category' ) ? '' : 'active'; ?>"></div>
</div>

<h3>Included post types</h3>

<div id="post-types-container">

<?php
$post_types_args = array(
	'public'   => true,
	'_builtin' => false,
);
$post_types      = array_values( get_post_types( $post_types_args ) );
array_unshift( $post_types, 'post' );

foreach ( $post_types  as $p_ty ) {
	echo '<div class="post-type-block">' . "\n";
	echo '<input type="checkbox" class="chkbx" id="pr-included-post-types-' . esc_attr( $p_ty ) . '" name="pr_included_post_types[]" value="' . esc_attr( $p_ty ) . '"';

	if ( in_array( $p_ty, pr_included_post_types(), true ) ) {
		echo ' checked="checked"';
	}

	echo ' />' . "\n";
	echo '<label for="pr-included-post-types-' . esc_attr( $p_ty ) . '">' . esc_html( $p_ty ) . '</label>' . "\n";
	echo '</div>' . "\n";
}
?>

</div>

<h3>Clean uninstall</h3>

<table class="form-table">
<tr>
<td>
<input type="checkbox" id="pr-clean-uninstall" name="pr_clean_uninstall" value="1" <?php checked( get_option( 'pr_clean_uninstall' ) ); ?> />
<label for="pr-clean-uninstall">Delete all options from database when you delete this plugin (the options won't be deleted on just deactivation).</label>
</td>
</tr>
</table>

<?php submit_button(); ?>

</form>

<h4>Do you like this plugin?</h4>

<ul>
<li>Please, <a href="https://wordpress.org/support/plugin/post-rotation/reviews/" target="_blank">rate it on the repository</a>.</li>
</ul>

<h4>Thank you!</h4>

</div>
