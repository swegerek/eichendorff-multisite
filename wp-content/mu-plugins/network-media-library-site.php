<?php
/**
 * Plugin Name: Network Media Library – Polish site
 * Description: The Polish site (1) is the shared media library of the network.
 */

add_filter( 'network-media-library/site_id', static fn(): int => 1 );
