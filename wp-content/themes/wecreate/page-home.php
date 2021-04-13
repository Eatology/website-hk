<?php
/**
 * Template Name: Home page
 */
global $post;
$home_page = $post->ID;

echo "Loulou";
include "template-parts/home/hero.php";
include "template-parts/home/why-order-from-us.php";
include "template-parts/home/latest-meal-plans.php";
include "template-parts/home/how-it-works.php";
include "template-parts/home/what-our-customers-think.php";
include "template-parts/home/newsletter.php";

