<?php
/**
 * The theme set up loader file.
 *
 * @package Deadpool
 */

// Useful global constants
define( 'DIE_VERSION',      '0.1.0' );
define( 'DIE_URL',          get_stylesheet_directory_uri() );
define( 'DIE_TEMPLATE_URL', get_template_directory_uri() );
define( 'DIE_PATH',         get_template_directory() . '/' );
define( 'DIE_INC',          DIE_PATH . 'includes/' );
define( 'DIE_PARTIALS',     DIE_PATH . 'partials/' );

// Include files
require_once DIE_INC . 'helpers.php';
require_once DIE_INC . 'core.php';
require_once DIE_INC . 'clean-up.php';
require_once DIE_INC . 'gif-support.php';
require_once DIE_INC . 'post-types/character.php';
require_once DIE_INC . 'post-types/player.php';
require_once DIE_INC . 'post-meta/status.php';
require_once DIE_INC . 'post-meta/wiki-link.php';
require_once DIE_INC . 'post-meta/characters.php';
require_once DIE_INC . 'options/episode.php';

// Run the load functions
Deadpool\Core\load();
Deadpool\CleanUp\load();
Deadpool\Gifs\load();
Deadpool\Post_Types\Character\load();
Deadpool\Post_Types\Player\load();
Deadpool\Post_Meta\Status\load();
Deadpool\Post_Meta\Wiki_Link\load();
Deadpool\Post_Meta\Characters\load();
Deadpool\Options\Episode\load();
