<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class('has-navbar-fixed-top'); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site is-flex is-flex-direction-column" style="min-height: 100vh;">
        <div id="content" class="site-content px-5-desktop is-flex-grow-1">
            <header>
                <?php get_template_part('template-parts/navbar'); ?>
            </header>