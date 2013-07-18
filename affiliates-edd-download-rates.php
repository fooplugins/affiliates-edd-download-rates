<?php
/**
 * affiliates-edd-download-rates.php
 * 
 * Copyright (c) 2013 "kento" Karim Rahimpur www.itthinx.com
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
 *
 * Plugin Name: Affiliates EDD Downloads Rates
 * Description: Allows you to set different affiliate rates for each download in Easy Digital Downloads.
 * Version: 1.0.0
 * Author: itthinx, bradvin
 * Plugin URI: http://www.fooplugins.com
 * License: GPLv3
 */

define( 'AFFILIATES_EDD_DOWNLOAD_RATES_CPT', 'download' );	//EDD's download CPT
define( 'AFFILIATES_EDD_DOWNLOAD_RATES_DEFAULT_PRODUCT_RATE', '0.33' );
define( 'AFFILIATES_EDD_DOWNLOAD_RATES_REFERENCE', 'reference' );

if ( is_admin() ) {
	require_once('class-affiliates-edddr-download.php');
}

require_once 'class-affiliates-edddr-method.php';
