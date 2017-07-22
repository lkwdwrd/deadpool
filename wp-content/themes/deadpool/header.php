<?php
/**
 * The standard header template file
 *
 * @package Deadpool
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="https://fonts.googleapis.com/css?family=Trirong:600" rel="stylesheet">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<header class="primary-header">
			<h1 class="main-title">
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
			</h1>
			<h2 class="main-subtitle">
				<?php echo esc_html( get_bloginfo( 'description' ) ); ?>
			</h2>
		</header>
		<main class="main-content">
