<?php
/**
 * class-affiliates-edddr-method.php
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
 * Product method.
 */
class Affiliates_EDDDR_Method {

	/**
	 * Register referral method.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'add_new_referral_calculator') );
	}

	public static function add_new_referral_calculator() {
		if ( method_exists( 'Affiliates_Referral', 'register_referral_amount_method' ) ) {
			Affiliates_Referral::register_referral_amount_method( array( __CLASS__, 'calculate_referral' ) );
		}
	}

	/**
	 * calculate the custom referral
	 */
	public static function calculate_referral( $affiliate_id, $parameters ) {
		$result = '0';
		if ( isset( $parameters[AFFILIATES_EDD_DOWNLOAD_RATES_REFERENCE] ) ) {
			$result = self::calculate( intval( $parameters[AFFILIATES_EDD_DOWNLOAD_RATES_REFERENCE] ) );
		} else if ( isset( $parameters['base_amount'] ) ) {
			$default_rate = AFFILIATES_EDD_DOWNLOAD_RATES_DEFAULT_PRODUCT_RATE;
			$result = bcmul( $parameters['base_amount'], $default_rate, AFFILIATES_REFERRAL_AMOUNT_DECIMALS );
		} else  if ( isset( $parameters['amount'] ) ) {
			$result = $parameters['amount'];
		}
		return $result;
	}

	/**
	 * Calculates the total product commission for an order.
	 * @param int $payment_id
	 * @return string total commission amount
	 */
	public static function calculate( $payment_id ) {
		$total_commission = '0';
		$payment_meta 	= edd_get_payment_meta( $payment_id );
		$downloads 		= maybe_unserialize( $payment_meta['downloads'] );
		$cart_details 	= unserialize( $payment_meta['cart_details'] );
		$user_info      = maybe_unserialize( $payment_meta['user_info'] );
		$coupon			= false;
		if ( isset( $user_info['discount'] ) && $user_info['discount'] != 'none' ) {
			$coupon = $user_info['discount'];
		}

		if ( count( $downloads )  > 0 ) {

			$default_rate = AFFILIATES_EDD_DOWNLOAD_RATES_DEFAULT_PRODUCT_RATE;

			foreach ( $downloads as $download_key => $download ) {
				$download_id = $download['id'];

				$rate = get_post_meta( $download_id, '_affiliates_rate', true );

				if ( strlen( (string) $rate ) == 0 ) {
					$rate = $default_rate;
				}

				if ( strlen( (string) $rate ) > 0 ) {
					// get the net line item total and calculate the commission
					$product_subtotal = floatval( $cart_details[ $download_key ]['price'] ); //$cart_details self::get_product_subtotal( $item, $product_id, $payment_id );

					if ( $product_subtotal > 0 ) {

						//if discount then calc discount
						if ($coupon !== false) {
							$product_subtotal = edd_get_discounted_amount( $coupon, $product_subtotal );
						}

						$commission       = bcmul( $product_subtotal, $rate, AFFILIATES_REFERRAL_AMOUNT_DECIMALS );
						// add to total commission
						$total_commission = bcadd( $total_commission, $commission, AFFILIATES_REFERRAL_AMOUNT_DECIMALS );
					}
				}
			}
		}
		return $total_commission;
	}
}
Affiliates_EDDDR_Method::init();
