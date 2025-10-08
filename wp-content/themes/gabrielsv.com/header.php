<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class('overflow-x-hidden'); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site d-flex flex-column min-vh-100">
        <div id="content" class="site-content px-lg-5 flex-fill">
            <header>
                <?php get_template_part('template-parts/navbar'); ?>
            </header>