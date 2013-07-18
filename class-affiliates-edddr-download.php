<?php
/**
 * class-affiliates-edddr-download.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 * Copyright (c) 2013 FooPlugins www.fooplugins.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur, Brad Vincent
 * @package affiliates-product-rates
 * @since affiliates-product-rates 1.0.0
 */

/**
 * Extends the EDD Download
 */
class Affiliates_EDDDR_Download {

	public static function init() {
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
		}
	}

	/*
	 * Register metabox on EDD download page
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'affiliates_rate_box',
			'Affiliates Rate',
			array( __CLASS__, 'affiliates_rate_box' ),
			AFFILIATES_EDD_DOWNLOAD_RATES_CPT,
			'normal'
		);
	}

	/**
	 * Affiliates download rate meta box renderer.
	 */
	public static function affiliates_rate_box() {

		global $post;

		$rate = get_post_meta( $post->ID, '_affiliates_rate', true );

		_e( 'Referral Rate', 'affiliates-edd-download-rates' );
		echo ' ';
		printf(
			'<input type="text" name="_affiliates_rate" value="%s" title="%s" placeholder="%s" />',
			$rate,
			__( 'Product referral rate for affiliates.', 'affiliates-edd-download-rates' ),
			__( 'default rate applies', 'affiliates-edd-download-rates' )
		);

		echo '<div style="padding:1em">';
		echo __( 'Examples:', 'affiliates-edd-download-rates' ) . '<br/>';
		echo '<ul>';
		echo '<li>' . __( 'Indicate <strong>0.1</strong> for 10% commissions on this product.', 'affiliates-edd-download-rates' ) . '</li>';
		echo '<li>' . __( 'Indicate <strong>0</strong> to exclude this product from commissions.', 'affiliates-edd-download-rates' ) . '</li>';
		echo '<li>' . __( 'Leave empty to have the default referral rate applied.', 'affiliates-edd-download-rates' ) . '</li>';
		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Process data for post being saved.
	 * 
	 * @param int $post_id product post id
	 * @param object $post
	 */
	public static function save_post( $post_id = null, $post = null ) {
		if ( ! ( ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) ) ) {
			if ( $post->post_type == AFFILIATES_EDD_DOWNLOAD_RATES_CPT ) {
				delete_post_meta( $post_id, '_affiliates_rate' );
				if ( strlen( trim( $_POST['_affiliates_rate'] ) ) > 0 ) {
					$rate = Affiliates_Attributes::validate_value( 'referral.rate', trim( $_POST['_affiliates_rate'] ) );
					if ( $rate !== false ) {
						add_post_meta( $post_id, '_affiliates_rate', $rate, true );
					}
				}
			}
		}
	}

	/**
	 * Retruns true if the product has an affiliate rate set.
	 * @param int $post_id product post id
	 * @return boolean true if product post has rate, otherwise false
	 */
	public static function has_rate( $post_id ) {
		$rate = get_post_meta( $post_id, '_affiliates_rate', true );
		return strlen( (string) $rate ) > 0;
	}

	/**
	 * Returns the product rate.
	 * @param int $post_id product post id
	 * @return string rate for product
	 */
	public static function get_rate( $post_id ) {
		$result = null;
		$rate = get_post_meta( $post_id, '_affiliates_rate', true );
		if ( strlen( (string) $rate ) > 0 ) {
			$result = $rate;
		}
		return $result;
	}

}
Affiliates_EDDDR_Download::init();
