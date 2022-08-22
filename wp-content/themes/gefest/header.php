<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package gefest
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wrapper wrapper-main">
    <header class="header">
        <div class="header__container">
            <div class="header__body">
                <a href="/" class="header__logo">
                    <?php echo get_custom_logo();?>
                </a>
                <div class="header__menu menu">
                    <button type="button" class="menu__icon icon-menu"><span></span></button>

                    <nav class="menu__body">
                        <ul class="menu__list">
                            <?php
                            $args = array(
                                'menu'                 => 'menu-main',
                                'container'            => false,
                                'menu_class'           => 'menu__list',
                                'echo'                 => true,
                                'items_wrap'           => '%3$s',
                            );
                            wp_nav_menu($args); ?>
                            <li class="menu__item item-cart">
                                <a href="#" class="menu__link link">Корзина</a>
                                <span>0</span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="header__line line-horizontal">

        </div>
    </header>
