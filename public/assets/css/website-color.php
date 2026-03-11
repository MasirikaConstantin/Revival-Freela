<?php
header("Content-Type:text/css");

// get the colors from query parameter
$primaryColor = $_GET['primary_color'];
$secondaryColor = $_GET['secondary_color'];
$breadcrumbOverlayColor = $_GET['breadcrumb_overlay_color'];

// check, whether color has '#' or not, will return 0 or 1
function checkColorCode($color)
{
  return preg_match('/^#[a-f0-9]{6}/i', $color);
}

// if, primary color value does not contain '#', then add '#' before color value
if (isset($primaryColor) && (checkColorCode($primaryColor) == 0)) {
  $primaryColor = '#' . $primaryColor;
}

// if, secondary color value does not contain '#', then add '#' before color value
if (isset($secondaryColor) && (checkColorCode($secondaryColor) == 0)) {
  $secondaryColor = '#' . $secondaryColor;
}

// if, breadcrumb overlay color value does not contain '#', then add '#' before color value
if (isset($breadcrumbOverlayColor) && (checkColorCode($breadcrumbOverlayColor) == 0)) {
  $breadcrumbOverlayColor = '#' . $breadcrumbOverlayColor;
}

// then add color to style
?>

:root {
--primary-color: <?php echo $secondaryColor; ?>;
--secondary-color: <?php echo $primaryColor; ?>;
}

.lds-ellipsis span {
background: <?php echo $secondaryColor; ?>;
}

.header-area-one .header-top-bar {
background-color: <?php echo $primaryColor; ?>;
}

.header-area-two .header-navigation .header-right-nav .cart-button .cart-btn span#product-count,
.header-area-one .header-top-bar .top-right ul li .cart-btn span#product-count {
color: <?php echo $primaryColor; ?>;
}

.header-navigation .main-menu ul li>a {
color: <?php echo $primaryColor; ?>;
}

.header-navigation .main-menu ul li:hover>a {
color: <?php echo $secondaryColor; ?>;
}

.header-navigation .main-menu ul>li.menu-item-has-children>a:after {
color: <?php echo $primaryColor; ?>;
}

.header-navigation .main-menu ul li:hover.menu-item-has-children>a:after {
color: <?php echo $secondaryColor; ?>;
}

.header-navigation .main-menu ul li .sub-menu li a {
color: <?php echo $primaryColor; ?>;
}

.header-navigation .main-menu ul li .sub-menu li:hover>a {
background-color: <?php echo $primaryColor; ?>;
}

.header-area-one .header-navigation .header-right-nav ul.social-link li a {
background-color: <?php echo $primaryColor; ?>;
}

.hero-slider-one .slick-arrow {
color: <?php echo $primaryColor; ?>;
}

h1, h2, h3, h4, h5, h6 {
color: <?php echo $primaryColor; ?>;
}

.main-btn:hover {
color: <?php echo $primaryColor; ?>;
}

.process-item-one .count-box .icon i {
color: <?php echo $primaryColor; ?>;
}

.process-item-one .count-box {
background-color: <?php echo $primaryColor; ?>;
}

.features-item-one .icon i {
color: <?php echo $primaryColor; ?>;
}

.bg-with-overlay:after {
background-color: <?php echo $primaryColor . 'DE'; ?>;
}

.counter-item-one .icon {
background: <?php echo $secondaryColor; ?>;
}

.counter-item-one .icon i {
color: <?php echo $primaryColor; ?>;
}

.pricing-item .pricing-info .pricing-body .price-option span.span-btn {
color: <?php echo $primaryColor; ?>;
}

.pricing-item-one .pricing-info .pricing-body span.delivary {
color: <?php echo $primaryColor; ?>;
}

.pricing-item .pricing-info .pricing-body ul.info-list li:before {
background-color: <?php echo $primaryColor; ?>;
}

.testimonial-item-one .testimonial-content .quote i {
color: <?php echo $primaryColor; ?>;
}

.blog-post-item-one .post-thumbnail .cat-btn {
color: <?php echo $primaryColor; ?>;
}

.blog-post-item-one .entry-content .btn-link {
color: <?php echo $primaryColor; ?>;
}

.newsletter-wrapper-one:after {
background: <?php echo $secondaryColor; ?>;
}

.newsletter-wrapper-one .newsletter-form .newsletter-btn {
background: <?php echo $primaryColor; ?>;
}

.footer-area-one:after {
background-color: <?php echo $primaryColor . 'F2'; ?>;
}

.back-to-top {
background: <?php echo $secondaryColor; ?>;
}

.back-to-top:hover, .back-to-top:focus {
background: <?php echo $primaryColor; ?>;
}

.breadcrumbs-area:after {
background-color: <?php echo $breadcrumbOverlayColor . 'CF'; ?>;
}


.equipments-search-filter .search-filter-form {
background-color: <?php echo $primaryColor; ?>;
}

.pricing-item-three .pricing-img span.discount {
color: <?php echo $primaryColor; ?>;
}

.equipement-sidebar-info .booking-form .price-info {
background-color: <?php echo $primaryColor; ?>;
}

.equipement-sidebar-info .booking-form .price-info .price-tag h4 span {
color: <?php echo $primaryColor; ?>;
}

.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn {
color: <?php echo $primaryColor; ?>;
}

.checkout-area-section .coupon .btn, .equipment-details-section .pricing-body .extra-option .btn {
background-color: <?php echo $secondaryColor; ?>;
}

.equipment-gallery-slider .slick-arrow {
color: <?php echo $primaryColor; ?>;
}

.description-wrapper .voucher-btn {
color: <?php echo $secondaryColor; ?>;
}

.description-wrapper .description-tabs .nav-link {
color: <?php echo $primaryColor; ?>;
}

.product-item-two .product-img .product-overlay {
background-color: <?php echo $primaryColor . 'CC'; ?>;
}

.product-item-two .product-img .product-overlay .product-meta a {
color: <?php echo $primaryColor; ?>;
}

.products-gallery-wrap .products-thumb-slider .slick-arrow,
.products-gallery-wrap .products-big-slider .slick-arrow {
color: <?php echo $primaryColor; ?>;
}

.products-gallery-wrap .products-thumb-slider .slick-arrow:hover,
.products-gallery-wrap .products-big-slider .slick-arrow:hover {
background-color: <?php echo $primaryColor; ?>;
}

.products-details-wrapper .product-info .product-tags a {
color: <?php echo $primaryColor; ?>;
}

.cart-area-section .total-item-info li {
color: <?php echo $primaryColor; ?>;
}

.cart-area-section .cart-table thead tr th {
color: <?php echo $primaryColor; ?>;
}

.cart-area-section .cart-middle .cart-btn {
background-color: <?php echo $secondaryColor; ?>;
}

.cart-area-section .cart-middle .cart-btn:hover {
background-color: <?php echo $primaryColor; ?>;
}

.sidebar-widget-area .widget.search-widget .search-btn {
color: <?php echo $primaryColor; ?>;
}

.sidebar-widget-area .widget.categories-widget ul.widget-link li a {
color: <?php echo $primaryColor; ?>;
}

.faq-wrapper-one .card .card-header {
color: <?php echo $primaryColor; ?>;
}

.header-area-two .header-top-bar .top-left span {
color: <?php echo $primaryColor; ?>;
}

.header-area-two .header-top-bar:after {
background-color: <?php echo $primaryColor; ?>;
}

.header-area-two .header-navigation .header-right-nav .cart-button .cart-btn {
background-color: <?php echo $primaryColor; ?>;
}

.header-area-two .header-navigation .header-right-nav .user-info a {
color: <?php echo $primaryColor; ?>;
}

.hero-wrapper-two .hero-search-wrapper {
background-color: <?php echo $primaryColor; ?>;
}

.process-item-two .count-box .icon i {
color: <?php echo $primaryColor; ?>;
}

.dark-blue {
background-color: <?php echo $primaryColor; ?>;
}

.features-item-two.active-item .icon, .features-item-two:hover .icon {
color: <?php echo $primaryColor; ?>;
}

.counter-item-two .icon {
color: <?php echo $primaryColor; ?>;
}

.counter-item-two .icon:after {
background-color: <?php echo $primaryColor; ?>;
}

.pricing-item-two .pricing-info .price-info {
background-color: <?php echo $primaryColor; ?>;
}

.pricing-item-two .pricing-info .pricing-body .price-option span.span-btn.active-btn {
background-color: <?php echo $secondaryColor . 'AC'; ?>;
}

.pricing-item-two .pricing-info .pricing-body span.delivary {
color: <?php echo $primaryColor; ?>;
}

.pricing-item-two .pricing-info .pricing-bottom {
background-color: <?php echo $primaryColor; ?>;
}

.main-btn-primary {
color: <?php echo $primaryColor; ?>;
}

.blog-post-item-two .post-thumbnail .category {
color: <?php echo $primaryColor; ?>;
}

.blog-post-item-two .post-thumbnail .category::after {
background-color: <?php echo $primaryColor; ?>;
}

.newsletter-wrapper-two:after {
background-color: <?php echo $secondaryColor . 'E6'; ?>;
}
