<?php
defined( 'ABSPATH' ) || die( 'Cannot access pages directly.' );

function pr_included_post_types() {
	return get_option( 'pr_included_post_types' ) ? get_option( 'pr_included_post_types' ) : array();
}

function pr_included_categories() {
	return get_option( 'pr_included_categories' ) ? array_map(
		function( $cat_id ) {
			return + $cat_id;
		},
		get_option( 'pr_included_categories' )
	) : array();
}

function pr_halt() {
	$nocategory = get_option( 'pr_filter_by_category' ) && empty( pr_included_categories() );

	return $nocategory || empty( pr_included_post_types() );
}

function selected_post() {
	$query_args = array(
		'numberposts'      => 1,
		'post_type'        => pr_included_post_types(),
		'post_status'      => 'publish',
		'orderby'          => 'post_date',
		'order'            => 'ASC',
		'suppress_filters' => false,
		'fields'           => 'ids',
	);

	if ( get_option( 'pr_filter_by_category' ) && ! empty( pr_included_categories() ) ) {
		$query_args['category__in'] = pr_included_categories();
	}

	if ( get_option( 'pr_exclude_if_no_featured_image' ) ) {
		$query_args['meta_key'] = '_thumbnail_id';
	}

	$query   = get_posts( $query_args );
	$located = false;

	foreach ( $query as $value ) {
		$located = $value;
	}

	return $located;
}

if ( get_option( 'pr_enabled' ) && ! pr_halt() ) {
	$query_args       = array(
		'numberposts'            => 1,
		'post_type'              => pr_included_post_types(),
		'post_status'            => 'publish',
		'orderby'                => 'post_date',
		'order'                  => 'DESC',
		'suppress_filters'       => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);
	$query            = get_posts( $query_args );
	$latest_post_time = false;

	foreach ( $query as $key => $value ) {
		$latest_post_time = $value->post_date;
	}

	$latest_post_time_unix = strtotime( $latest_post_time );
	$this_moment           = strtotime( current_time( 'mysql' ) );
	$key_moment            = ( get_option( 'pr_fixed' ) && get_option( 'pr_latest_rotation_time' ) ) ? + get_option( 'pr_latest_rotation_time' ) : $latest_post_time_unix;
	$discrepancy           = $this_moment - $key_moment;
	$pr_interval           = ( + get_option( 'pr_interval' )['hours'] * 3600 ) + ( + get_option( 'pr_interval' )['minutes'] * 60 );
	$selected_post         = selected_post();

	if ( $discrepancy >= $pr_interval && $selected_post ) {
		if ( get_option( 'pr_enforce_punctuality' ) ) {
			$new_date_unix = $key_moment + $pr_interval;
			$new_date      = date( 'Y-m-d H:i:s', $new_date_unix );

			update_option( 'pr_latest_rotation_time', $new_date_unix );
		} else {
			$new_date = current_time( 'mysql' );

			update_option( 'pr_latest_rotation_time', $this_moment );
		}

		$new_date_gmt = get_gmt_from_date( $new_date );

		global $wpdb;

		$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_date = %s, post_date_gmt = %s WHERE ID = %d", $new_date, $new_date_gmt, $selected_post ) );

		if ( get_option( 'pr_also_alter_last_modified' ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_modified = %s, post_modified_gmt = %s WHERE ID = %d", $new_date, $new_date_gmt, $selected_post ) );
		}

		$wpdb->flush();
	}
}
