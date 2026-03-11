-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 09, 2024 at 08:41 AM
-- Server version: 5.7.34
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `multigig_2.0_without_installer`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` longtext COLLATE utf8_unicode_ci,
  `button_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `language_id`, `title`, `text`, `button_name`, `button_url`, `created_at`, `updated_at`) VALUES
(3, 9, 'نحن نقدم أفضل الحلول لعملك', '<p><span style=\"color:rgb(66,66,66);font-family:tahoma, sans-serif;\">وعند موافقه العميل المبدئيه على التصميم يتم ازالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة للتصميم ويقول البعض ان وضع النصوص التجريبية بالتصميم قد تشغل المشاهد عن وضع الكثير من الملاحظات او الانتقادات للتصميم الاساسي.</span></p>\r\n<p><span style=\"color:rgb(66,66,66);font-family:tahoma, sans-serif;\">وخلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد. من كتاب \"حول أقاصي الخير والشر\"</span><span style=\"color:rgb(66,66,66);font-family:tahoma, sans-serif;\"><br /></span></p>', 'البدء', 'https://freelance-marketplace.test', '2022-04-16 23:29:40', '2023-12-18 05:25:10'),
(4, 8, 'Take Your Business Life To The Next Level', '<p>Welcome to our website, a cutting-edge multi-vendor freelance platform designed to connect skilled professionals with businesses and individuals seeking top-tier services. Just like the renowned platform Fiverr, we\'ve curated a dynamic ecosystem where talent meets opportunity, fostering a thriving community of freelancers and clients.</p>\r\n<p>At our site, we believe in empowering freelancers to showcase their expertise across diverse categories, from graphic design and digital marketing to programming, writing, and more. Our platform offers a seamless and secure environment for freelancers to exhibit their skills, set their own prices, and connect with a global clientele.</p>', 'Get Started', 'https://example.com', '2022-05-14 23:00:15', '2023-12-20 07:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `role_id`, `first_name`, `last_name`, `image`, `username`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Fahad', 'Shemul', '622845a1841fb.png', 'admin', 'fahadahmadshemul@gmail.com', '$2y$10$W/ymK1oV1m7R7KTdZb/D/.BWGuxn5yN/brblegtXFylo3XQn8oI8.', 1, NULL, '2023-11-30 08:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ad_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resolution_type` smallint(5) UNSIGNED NOT NULL COMMENT '1 => 300 x 250, 2 => 300 x 600, 3 => 728 x 90',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slot` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `ad_type`, `resolution_type`, `image`, `url`, `slot`, `views`, `created_at`, `updated_at`) VALUES
(7, 'banner', 3, '65992840bd471.png', 'http://example.com/', NULL, 3, '2021-08-15 22:44:47', '2024-01-06 10:15:28'),
(8, 'banner', 2, '659928637764f.png', 'http://example.com/', NULL, 1, '2021-08-15 22:45:21', '2024-01-06 10:16:03'),
(9, 'banner', 1, '6599286b36783.png', 'http://example.com/', NULL, 1, '2021-08-15 23:12:31', '2024-01-06 10:16:11'),
(10, 'banner', 1, '659928774435e.png', 'http://example.com/', NULL, 2, '2021-08-15 23:13:44', '2024-01-06 10:16:23'),
(11, 'banner', 2, '6599285c64bd6.png', 'http://example.com/', NULL, 3, '2021-08-15 23:15:14', '2024-01-06 10:15:56'),
(12, 'banner', 1, '6599287f4ac4f.png', 'http://example.com/', NULL, 0, '2021-08-15 23:16:41', '2024-01-06 10:16:31'),
(13, 'banner', 3, '659928483f39c.png', 'http://example.com/', NULL, 1, '2021-08-17 04:52:09', '2024-01-06 10:15:36'),
(16, 'banner', 4, '659928897b48c.png', 'https://www.twitter.com/', NULL, 1, '2023-07-18 05:07:01', '2024-01-06 10:16:41'),
(17, 'banner', 5, '65992854079cb.png', 'https://www.example.com', NULL, 0, '2023-07-18 06:03:01', '2024-01-06 10:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `basic_extends`
--

CREATE TABLE `basic_extends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `popular_tags` text COLLATE utf8_unicode_ci,
  `news_letter_section_text` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `basic_extends`
--

INSERT INTO `basic_extends` (`id`, `language_id`, `popular_tags`, `news_letter_section_text`, `created_at`, `updated_at`) VALUES
(1, 8, 'Graphic Design,Web Development,Digital Marketing', 'Subscribe to Our Newsletter Today! Get the latest updates, exclusive offers, and valuable insights delivered directly to your inbox.', '2023-05-07 07:36:15', '2024-01-04 05:42:53'),
(2, 9, 'التصميم الجرافيكي,تطوير الشبكة,التسويق الرقمي', 'اشترك في النشرة الإخبارية لدينا اليوم! احصل على آخر التحديثات والعروض الحصرية والرؤى القيمة التي يتم تسليمها مباشرة إلى صندوق الوارد الخاص بك', '2023-05-07 07:37:30', '2024-01-04 05:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `basic_settings`
--

CREATE TABLE `basic_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uniqid` int(10) UNSIGNED NOT NULL DEFAULT '12345',
  `favicon` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website_title` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `theme_version` smallint(5) UNSIGNED NOT NULL,
  `base_currency_symbol` varchar(255) DEFAULT NULL,
  `base_currency_symbol_position` varchar(20) DEFAULT NULL,
  `base_currency_text` varchar(20) DEFAULT NULL,
  `base_currency_text_position` varchar(20) DEFAULT NULL,
  `base_currency_rate` decimal(8,2) DEFAULT NULL,
  `primary_color` varchar(30) DEFAULT NULL,
  `secondary_color` varchar(30) DEFAULT NULL,
  `breadcrumb_overlay_color` varchar(30) DEFAULT NULL,
  `breadcrumb_overlay_opacity` decimal(4,2) DEFAULT NULL,
  `smtp_status` tinyint(4) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `encryption` varchar(50) DEFAULT NULL,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `from_mail` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `to_mail` varchar(255) DEFAULT NULL,
  `breadcrumb` varchar(255) DEFAULT NULL,
  `disqus_status` tinyint(3) UNSIGNED DEFAULT NULL,
  `disqus_short_name` varchar(255) DEFAULT NULL,
  `google_recaptcha_status` tinyint(4) DEFAULT NULL,
  `google_recaptcha_site_key` varchar(255) DEFAULT NULL,
  `google_recaptcha_secret_key` varchar(255) DEFAULT NULL,
  `whatsapp_status` tinyint(3) UNSIGNED DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `whatsapp_header_title` varchar(255) DEFAULT NULL,
  `whatsapp_popup_status` tinyint(3) UNSIGNED DEFAULT NULL,
  `whatsapp_popup_message` text,
  `maintenance_img` varchar(255) DEFAULT NULL,
  `maintenance_status` tinyint(4) DEFAULT NULL,
  `maintenance_msg` text,
  `bypass_token` varchar(255) DEFAULT NULL,
  `footer_logo` varchar(255) DEFAULT NULL,
  `admin_theme_version` varchar(10) NOT NULL DEFAULT 'light',
  `notification_image` varchar(255) DEFAULT NULL,
  `google_adsense_publisher_id` varchar(255) DEFAULT NULL,
  `hero_bg_img` varchar(255) DEFAULT NULL,
  `about_section_image` varchar(255) DEFAULT NULL,
  `about_section_video_link` varchar(255) DEFAULT NULL,
  `feature_bg_img` varchar(255) NOT NULL,
  `testimonial_bg_img` varchar(255) NOT NULL,
  `qr_url` varchar(255) DEFAULT NULL,
  `qr_image` varchar(255) DEFAULT NULL,
  `qr_color` varchar(255) NOT NULL DEFAULT '000000',
  `qr_size` int(10) UNSIGNED NOT NULL DEFAULT '250',
  `qr_style` varchar(255) NOT NULL DEFAULT 'square',
  `qr_eye_style` varchar(255) NOT NULL DEFAULT 'square',
  `qr_margin` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `qr_type` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'it can be 3 types of qr code. they are: ''default'', ''image'' and ''text''',
  `qr_inserted_image` varchar(255) DEFAULT NULL,
  `qr_inserted_image_size` int(10) UNSIGNED NOT NULL DEFAULT '20',
  `qr_inserted_image_x` int(10) UNSIGNED NOT NULL DEFAULT '50',
  `qr_inserted_image_y` int(10) UNSIGNED NOT NULL DEFAULT '50',
  `qr_text` varchar(255) DEFAULT NULL,
  `qr_text_color` varchar(255) NOT NULL DEFAULT '000000',
  `qr_text_size` int(10) UNSIGNED NOT NULL DEFAULT '15',
  `qr_text_x` int(10) UNSIGNED NOT NULL DEFAULT '50',
  `qr_text_y` int(10) UNSIGNED NOT NULL DEFAULT '50',
  `facebook_login_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `facebook_app_id` varchar(255) DEFAULT NULL,
  `facebook_app_secret` varchar(255) DEFAULT NULL,
  `google_login_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `google_client_id` varchar(255) DEFAULT NULL,
  `google_client_secret` varchar(255) DEFAULT NULL,
  `pusher_app_id` varchar(255) DEFAULT NULL,
  `pusher_key` varchar(255) DEFAULT NULL,
  `pusher_secret` varchar(255) DEFAULT NULL,
  `pusher_cluster` varchar(50) DEFAULT NULL,
  `support_ticket_status` tinyint(3) UNSIGNED NOT NULL COMMENT '1 -> enable, 0 -> disable',
  `hero_static_img` varchar(255) DEFAULT NULL,
  `hero_video_url` varchar(255) DEFAULT NULL,
  `newsletter_bg_img` varchar(255) DEFAULT NULL,
  `cta_bg_img` varchar(255) DEFAULT NULL,
  `is_service` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - deactive',
  `is_language` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1- active, 0 - deactive from menubar',
  `seller_email_verification` int(11) DEFAULT '0',
  `seller_admin_approval` int(11) DEFAULT '0',
  `admin_approval_notice` text,
  `expiration_reminder` int(11) DEFAULT '0',
  `tax` float(8,2) NOT NULL DEFAULT '0.00',
  `chat_max_file` varchar(255) DEFAULT '0',
  `life_time_earning` double(8,2) NOT NULL,
  `total_profit` double(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `basic_settings`
--

INSERT INTO `basic_settings` (`id`, `uniqid`, `favicon`, `logo`, `website_title`, `email_address`, `contact_number`, `address`, `latitude`, `longitude`, `theme_version`, `base_currency_symbol`, `base_currency_symbol_position`, `base_currency_text`, `base_currency_text_position`, `base_currency_rate`, `primary_color`, `secondary_color`, `breadcrumb_overlay_color`, `breadcrumb_overlay_opacity`, `smtp_status`, `smtp_host`, `smtp_port`, `encryption`, `smtp_username`, `smtp_password`, `from_mail`, `from_name`, `to_mail`, `breadcrumb`, `disqus_status`, `disqus_short_name`, `google_recaptcha_status`, `google_recaptcha_site_key`, `google_recaptcha_secret_key`, `whatsapp_status`, `whatsapp_number`, `whatsapp_header_title`, `whatsapp_popup_status`, `whatsapp_popup_message`, `maintenance_img`, `maintenance_status`, `maintenance_msg`, `bypass_token`, `footer_logo`, `admin_theme_version`, `notification_image`, `google_adsense_publisher_id`, `hero_bg_img`, `about_section_image`, `about_section_video_link`, `feature_bg_img`, `testimonial_bg_img`, `qr_url`, `qr_image`, `qr_color`, `qr_size`, `qr_style`, `qr_eye_style`, `qr_margin`, `qr_type`, `qr_inserted_image`, `qr_inserted_image_size`, `qr_inserted_image_x`, `qr_inserted_image_y`, `qr_text`, `qr_text_color`, `qr_text_size`, `qr_text_x`, `qr_text_y`, `facebook_login_status`, `facebook_app_id`, `facebook_app_secret`, `google_login_status`, `google_client_id`, `google_client_secret`, `pusher_app_id`, `pusher_key`, `pusher_secret`, `pusher_cluster`, `support_ticket_status`, `hero_static_img`, `hero_video_url`, `newsletter_bg_img`, `cta_bg_img`, `is_service`, `is_language`, `seller_email_verification`, `seller_admin_approval`, `admin_approval_notice`, `expiration_reminder`, `tax`, `chat_max_file`, `life_time_earning`, `total_profit`, `created_at`, `updated_at`) VALUES
(2, 12345, '659cd13da6f1a.png', '659cd12acfe34.png', 'Multigig', 'demo@example.com', '+1-202-555-0109', '450 Young Road, New York, USA', '34.05224', '-118.24368', 1, '$', 'left', 'USD', 'right', '1.00', 'F4813C', '160828', '000000', '0.60', 1, 'smtp.gmail.com', 587, 'TLS', 'geniustest11@gmail.com', 'jvpdiafcjhrznkbm', 'geniustest11@gmail.com', 'MultiGig', 'fahadahmadshemul@gmail.com', '65a78bd4efcc4.jpg', 1, 'Multigig', 1, '6LdvRBUpAAAAAOC2bYiBjclS5bv3Ia98tg38euo5', '6LdvRBUpAAAAALOcTjULHON-V6dSkvo4GG5n2era', 1, '01931341253', 'Hi, there!', 1, 'If you have any issues, let us know.', '1632725312.png', 0, 'We are upgrading our site. We will come back soon. \r\nPlease stay with us.\r\nThank you.', 'fahad', '659cd1605e079.png', 'dark', '619b7d5e5e9df.png', NULL, '659934a208c01.jpg', '6598e6d2e8a19.png', 'https://www.youtube.com/watch?v=ufda7QD-EcM', '625bae6fd72f0.jpg', '658d222b348ff.jpg', 'https://codecanyon8.kreativdev.com/multi-gig/demo', '659a60e3d3193.png', '000000', 250, 'square', 'square', 0, 'default', NULL, 20, 50, 50, NULL, '000000', 15, 50, 50, 1, '415655527803766', 'a0c446544eaaf35713de739be5dc22e8', 1, '1028456015138-2ig40jpn9gaj7bq6kefbmsjt149me75v.apps.googleusercontent.com', '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', '1632491', '76e6df7413baa2e10f9f', '929a8970e66fb26255b1', 'ap2', 1, '659934a20932a.png', 'https://www.youtube.com/watch?v=ufda7QD-EcM', '62f09aacaaa98.png', '659934867b5bd.jpg', 1, 1, 1, 1, 'Unfortunately, your account is deactive now. please get in touch with admin.', 3, 7.00, '2000', 0.00, 0.00, '2023-12-03 06:27:43', '2023-12-03 06:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cookie_alerts`
--

CREATE TABLE `cookie_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `cookie_alert_status` tinyint(3) UNSIGNED NOT NULL,
  `cookie_alert_btn_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cookie_alert_text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cookie_alerts`
--

INSERT INTO `cookie_alerts` (`id`, `language_id`, `cookie_alert_status`, `cookie_alert_btn_text`, `cookie_alert_text`, `created_at`, `updated_at`) VALUES
(2, 8, 1, 'I Agree', 'We use cookies to give you the best online experience.\r\nBy continuing to browse the site you are agreeing to our use of cookies. dfalkfa', '2021-08-29 04:20:43', '2024-01-06 09:39:37'),
(3, 9, 1, 'أنا موافق', 'نحن نستخدم ملفات تعريف الارتباط لنمنحك أفضل تجربة عبر الإنترنت.\r\nمن خلال الاستمرار في تصفح الموقع ، فإنك توافق على استخدامنا لملفات تعريف الارتباط.', '2022-03-10 01:00:26', '2022-05-14 22:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `cta_section_infos`
--

CREATE TABLE `cta_section_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cta_section_infos`
--

INSERT INTO `cta_section_infos` (`id`, `language_id`, `image`, `title`, `button_text`, `button_url`, `created_at`, `updated_at`) VALUES
(1, 8, '659a80063a446.png', 'Experience the Power of Premium Freelancers', 'Start Hiring', 'https://codecanyon8.kreativdev.com/multi-gig/demo/contact', '2023-12-30 06:04:55', '2024-01-07 15:42:14'),
(3, 9, '658fbc9e2eba1.png', 'استمتع بتجربة قوة المستقلين المميزين', 'البدء في التوظيف', 'https://codecanyon8.kreativdev.com/multi-gig/demo/contact', '2023-12-30 06:45:50', '2023-12-30 06:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `language_id`, `icon`, `color`, `title`, `created_at`, `updated_at`) VALUES
(11, 9, 'fas fa-bullhorn', 'FCAA23', 'التسويق الرقمي مع تحسين محركات البحث', '2022-04-18 02:11:04', '2022-07-21 09:38:44'),
(12, 9, 'fab fa-css3-alt', '0025DB', 'تطوير البرمجيات و Saas', '2022-04-18 02:13:22', '2022-07-21 09:39:21'),
(13, 9, 'fas fa-code', '28E1FF', 'تطوير المواقع والتطبيقات', '2022-04-18 02:14:12', '2022-07-21 09:39:52'),
(14, 8, 'fas fa-bullhorn', 'FCAA23', 'Customer Support', '2022-05-14 23:04:13', '2023-12-20 06:34:57'),
(15, 8, 'fab fa-css3-alt', '0025DB', 'Data Tracking', '2022-05-14 23:07:47', '2023-12-20 06:34:48'),
(16, 8, 'fas fa-code', '28E1FF', 'Project Reporting', '2022-05-14 23:08:31', '2023-12-20 06:34:38'),
(18, 8, 'fas fa-briefcase', 'F3615D', 'Reports Analysis', '2022-06-22 10:12:34', '2023-12-20 06:34:27'),
(19, 8, 'fas fa-paint-brush', 'FF89A5', 'Business Analysis', '2022-06-22 10:13:18', '2023-12-20 06:34:05'),
(20, 9, 'fas fa-briefcase', 'F3615D', 'ادارة اعمال', '2022-07-21 09:40:28', '2022-07-21 09:40:28'),
(21, 9, 'fas fa-paint-brush', 'FF89A5', 'تصميم الجرافيك المرئي', '2022-07-21 09:41:02', '2022-07-21 09:41:02'),
(22, 8, 'fas fa-layer-group', '44D1A5', 'Profit Planning', '2022-08-03 07:20:19', '2023-12-20 06:29:28'),
(24, 9, 'fas fa-layer-group', '44D1A5', 'تصميم واجهة المستخدم', '2022-08-06 10:03:05', '2022-08-06 10:03:05'),
(25, 8, 'fab fa-app-store', '2068AC', 'Financial Management', '2023-12-20 06:50:44', '2023-12-20 06:51:05'),
(26, 8, 'fas fa-donate', '9D2FB5', 'E-commerce Optimization', '2023-12-20 06:52:11', '2023-12-20 06:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `follower_id` bigint(20) DEFAULT NULL,
  `following_id` bigint(20) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_contents`
--

CREATE TABLE `footer_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `footer_background_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `about_company` text COLLATE utf8_unicode_ci,
  `copyright_text` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `footer_contents`
--

INSERT INTO `footer_contents` (`id`, `language_id`, `footer_background_color`, `about_company`, `copyright_text`, `created_at`, `updated_at`) VALUES
(3, 9, '0D1034', 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها.', 'حقوق الطبع والنشر © 2022 ، جميع الحقوق محفوظة.', '2022-01-02 23:26:08', '2022-05-13 22:21:58'),
(4, 8, '4D6878', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'Copyright © 2022,  All Rights Reserved.', '2022-03-06 01:00:32', '2024-01-23 17:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `language_id`, `seller_id`, `name`, `created_at`, `updated_at`) VALUES
(36, 9, NULL, 'نموذج الدفع', '2023-12-17 09:53:03', '2023-12-17 09:53:03'),
(37, 9, NULL, 'طلب نموذج الاقتباس', '2023-12-17 09:57:23', '2023-12-17 09:57:23'),
(42, 9, 24, 'نموذج الخروج', '2023-12-18 06:53:37', '2023-12-18 06:53:37'),
(43, 9, 24, 'نموذج طلب', '2023-12-18 06:56:57', '2023-12-18 06:56:57'),
(44, 9, 23, 'نموذج الخروج', '2023-12-18 06:58:11', '2023-12-18 06:58:11'),
(45, 9, 23, 'طلب نموذج الاقتباس', '2023-12-18 07:00:55', '2023-12-18 07:00:55'),
(47, 9, 22, 'نموذج الدفع', '2023-12-18 07:03:43', '2023-12-18 07:03:43'),
(49, 9, 21, 'نموذج الخروج', '2023-12-18 07:07:38', '2023-12-18 07:07:38'),
(51, 9, 20, 'نموذج الدفع', '2023-12-18 07:09:22', '2023-12-18 07:09:22'),
(53, 9, 19, 'نموذج طلب', '2023-12-18 07:12:28', '2023-12-18 07:12:28'),
(55, 9, 19, 'الدفع', '2023-12-18 07:14:58', '2023-12-18 07:14:58'),
(57, 9, 18, 'نموذج الدفع', '2023-12-18 07:17:03', '2023-12-18 07:17:03'),
(59, 9, 17, 'نموذج الدفع', '2023-12-18 07:20:00', '2023-12-18 07:20:00'),
(61, 9, 17, 'نموذج طلب', '2023-12-18 07:22:39', '2023-12-18 07:22:39'),
(63, 9, 16, 'نموذج الخروج', '2023-12-18 07:24:51', '2023-12-18 07:24:51'),
(66, 9, 15, 'نموذج الدفع', '2023-12-18 07:29:12', '2023-12-18 07:29:12'),
(67, 9, 15, 'نموذج طلب', '2023-12-18 07:31:16', '2023-12-18 07:31:16');

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE `form_inputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL COMMENT '1 - Text Field, 2 - Number Field, 3 - Select, 4 - Checkbox, 5 - Textarea Field, 6 - Datepicker, 7 - Timepicker, 8 - File',
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(3) UNSIGNED NOT NULL COMMENT '0 - not required, 1 - required',
  `options` text COLLATE utf8_unicode_ci,
  `file_size` decimal(11,2) UNSIGNED DEFAULT NULL,
  `order_no` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'default value 0 means, this input field has created just now and it has not sorted yet.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `form_inputs`
--

INSERT INTO `form_inputs` (`id`, `form_id`, `type`, `label`, `placeholder`, `name`, `is_required`, `options`, `file_size`, `order_no`, `created_at`, `updated_at`) VALUES
(90, 36, 1, 'رقم التليفون', 'أدخل رقم الهاتف', 'رقم_التليفون', 1, NULL, NULL, 1, '2023-12-17 09:55:03', '2023-12-17 09:55:03'),
(91, 36, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-17 09:55:26', '2023-12-17 09:55:26'),
(92, 36, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 3, '2023-12-17 09:55:46', '2023-12-17 09:55:46'),
(93, 36, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 1, NULL, NULL, 4, '2023-12-17 09:56:22', '2023-12-17 09:56:22'),
(94, 36, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 5, '2023-12-17 09:57:01', '2023-12-17 09:57:01'),
(95, 37, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-17 09:58:01', '2023-12-17 09:58:01'),
(96, 37, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-17 09:58:26', '2023-12-17 09:58:26'),
(97, 37, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 3, '2023-12-17 09:58:42', '2023-12-17 09:58:42'),
(98, 37, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 0, NULL, NULL, 4, '2023-12-17 09:59:01', '2023-12-17 09:59:01'),
(99, 37, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 5, '2023-12-17 09:59:21', '2023-12-17 09:59:21'),
(100, 37, 5, 'رسالة', 'أدخل رسالة', 'رسالة', 1, NULL, NULL, 6, '2023-12-17 09:59:39', '2023-12-17 09:59:39'),
(112, 42, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 1, '2023-12-18 06:54:10', '2023-12-18 06:54:10'),
(113, 42, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 2, '2023-12-18 06:54:28', '2023-12-18 06:54:28'),
(114, 42, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 0, NULL, NULL, 3, '2023-12-18 06:54:47', '2023-12-18 06:54:47'),
(115, 42, 1, 'الرمز البريدي', 'أدخل الرمز البريدي', 'الرمز_البريدي', 1, NULL, NULL, 4, '2023-12-18 06:55:08', '2023-12-18 06:55:08'),
(118, 42, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 5, '2023-12-18 06:56:36', '2023-12-18 06:56:36'),
(119, 43, 5, 'رسالة', 'أدخل رسالة', 'رسالة', 1, NULL, NULL, 1, '2023-12-18 06:57:22', '2023-12-18 06:57:22'),
(120, 44, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 1, '2023-12-18 06:58:38', '2023-12-18 06:58:38'),
(121, 44, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 2, '2023-12-18 06:59:41', '2023-12-18 06:59:41'),
(122, 44, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 1, NULL, NULL, 3, '2023-12-18 07:00:04', '2023-12-18 07:00:04'),
(123, 44, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 4, '2023-12-18 07:00:21', '2023-12-18 07:00:21'),
(124, 45, 5, 'Message', 'Enter Message', 'message', 1, NULL, NULL, 1, '2023-12-18 07:01:19', '2023-12-18 07:01:19'),
(129, 47, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:04:04', '2023-12-18 07:04:04'),
(130, 47, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-18 07:04:36', '2023-12-18 07:04:36'),
(131, 47, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 3, '2023-12-18 07:04:56', '2023-12-18 07:04:56'),
(132, 47, 1, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 4, '2023-12-18 07:05:14', '2023-12-18 07:05:14'),
(134, 49, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:07:56', '2023-12-18 07:07:56'),
(136, 51, 2, 'رقم واتس اب', 'أدخل رقم واتس اب', 'رقم_واتس_اب', 1, NULL, NULL, 1, '2023-12-18 07:09:53', '2023-12-18 07:09:53'),
(139, 53, 2, 'رقم التليفون', 'أدخل رقم الهاتف', 'رقم_التليفون', 1, NULL, NULL, 1, '2023-12-18 07:12:53', '2023-12-18 07:12:53'),
(140, 53, 5, 'رسالة', 'أدخل رسالة', 'رسالة', 1, NULL, NULL, 2, '2023-12-18 07:13:12', '2023-12-18 07:13:12'),
(144, 55, 1, 'اسم الشركة', 'أدخل اسم الشركة', 'اسم_الشركة', 1, NULL, NULL, 1, '2023-12-18 07:15:15', '2023-12-18 07:15:15'),
(145, 55, 5, 'وصف', 'أدخل الوصف', 'وصف', 1, NULL, NULL, 2, '2023-12-18 07:15:32', '2023-12-18 07:15:32'),
(149, 57, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-18 07:17:20', '2023-12-18 07:18:01'),
(150, 57, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 3, '2023-12-18 07:17:35', '2023-12-18 07:18:01'),
(151, 57, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:17:59', '2023-12-18 07:18:01'),
(157, 59, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:20:17', '2023-12-18 07:20:17'),
(158, 59, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-18 07:20:34', '2023-12-18 07:20:34'),
(159, 59, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 1, NULL, NULL, 3, '2023-12-18 07:20:52', '2023-12-18 07:20:52'),
(160, 59, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 4, '2023-12-18 07:21:08', '2023-12-18 07:21:08'),
(161, 59, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 5, '2023-12-18 07:21:26', '2023-12-18 07:21:26'),
(165, 61, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:23:03', '2023-12-18 07:23:03'),
(166, 61, 5, 'رسالة', 'أدخل رسالة', 'رسالة', 1, NULL, NULL, 2, '2023-12-18 07:23:22', '2023-12-18 07:23:22'),
(170, 63, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:25:14', '2023-12-18 07:25:14'),
(171, 63, 5, 'وصف', 'أدخل الوصف', 'وصف', 1, NULL, NULL, 2, '2023-12-18 07:25:33', '2023-12-18 07:25:33'),
(181, 66, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:29:29', '2023-12-18 07:29:29'),
(182, 66, 1, 'مدينة', 'أدخل المدينة', 'مدينة', 1, NULL, NULL, 2, '2023-12-18 07:29:42', '2023-12-18 07:29:42'),
(183, 66, 1, 'ولاية', 'إدخال الدولة', 'ولاية', 0, NULL, NULL, 3, '2023-12-18 07:29:55', '2023-12-18 07:29:55'),
(184, 66, 1, 'الرمز البريدي', 'أدخل الرمز البريدي', 'الرمز_البريدي', 1, NULL, NULL, 4, '2023-12-18 07:30:13', '2023-12-18 07:30:13'),
(185, 66, 1, 'دولة', 'أدخل البلد', 'دولة', 1, NULL, NULL, 5, '2023-12-18 07:30:29', '2023-12-18 07:30:29'),
(186, 66, 5, 'عنوان', 'أدخل العنوان', 'عنوان', 1, NULL, NULL, 6, '2023-12-18 07:30:59', '2023-12-18 07:30:59'),
(187, 67, 1, 'هاتف', 'أدخل الهاتف', 'هاتف', 1, NULL, NULL, 1, '2023-12-18 07:31:34', '2023-12-18 07:31:34'),
(188, 67, 5, 'رسالة', 'أدخل رسالة', 'رسالة', 1, NULL, NULL, 2, '2023-12-18 07:31:57', '2023-12-18 07:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `endpoint` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `endpoint`, `created_at`, `updated_at`) VALUES
(1, 'https://fcm.googleapis.com/fcm/send/cBv2YWjxIb0:APA91bEnO9dp_BY-wkQg3FuomdLV7stszxMmVHUWoNrXlGsrSJwBVT-vQ1sAKnHfAIl_kQEt86vFP-SY5sHDq4H-9Y0wDI-7bEptsk57weWnpEjU-tlnUnLOdznPoQ4C5ulqPBTOxeX8', '2024-01-06 07:02:24', '2024-01-06 07:02:24'),
(2, 'https://fcm.googleapis.com/fcm/send/cJYCIIvP0Wo:APA91bFXVa0d25_R1HfpOAe8U4GsqlyC_j7TSTqlK3jUAJtDiXra6r7vWGb06Pfluj00xhwcCmyjpcCJ7oMXfbGCt7DtSqe_EjdyKIl0QXwh5_0eduKHiYlyJlQ-jXEmK1dsA2CFQXwO', '2024-01-06 09:37:38', '2024-01-06 09:37:38'),
(3, 'https://fcm.googleapis.com/fcm/send/fw0Hqhfg7xg:APA91bGoNeRWYfXCOOaRM6BaR7nbtCVA-yGiy7QeXL40MClofLVs3Db_La0Jqz_mz0PzXteiTps609WT70siMW_XVDhjsc6vPIwixmIIJvY6JhY3ZxQ_d0ElERj3sDc1C7MmBKToejTD', '2024-01-06 09:44:12', '2024-01-06 09:44:12'),
(4, 'https://fcm.googleapis.com/fcm/send/e3b6YptzCsc:APA91bEDM56563r9KEdy_tdmXdhmGf1sUcxicgeAAWjnE0QkdIlq5hOor4Cds7wQXK1LVkGRPEhwA_DwDKFTgiJYrBPqhW0L-jH6H4cq7xXuIOnVxTCzPd-9Z31Rh4AoRhgMa_paYj2y', '2024-01-06 12:01:09', '2024-01-06 12:01:09'),
(5, 'https://fcm.googleapis.com/fcm/send/dDrn0VaU5QU:APA91bEK5hX2UxHSAGmf0ffcLluzzDlxhD5AcexC79ZnljEcqGCP0ZGuTPeFJeU_7VD01o2_PpFDL_hvszWBivgOQ3S535URPRjFrkZGZNEzPAmR3GursQP--JC17eRiqdm2-a3JP9mn', '2024-01-06 18:17:13', '2024-01-06 18:17:13'),
(6, 'https://fcm.googleapis.com/fcm/send/d_WkHjT_MgI:APA91bFVpzZ5MDgqSRS2nucv5SV6t3O_E-ipIYzDQExgg72ZCh26-r2vo8Qhml1HOKTWnrzfxq9o1pwCt7YPA_bh18nZzQ5pSx6QOOiQp2KwqQ9pC_TvR0UdfnL1tHEbl26MN2LJ44Y6', '2024-01-06 18:24:25', '2024-01-06 18:24:25'),
(7, 'https://fcm.googleapis.com/fcm/send/dY2sg8kpreg:APA91bGu5KTMbVqdm_FgStnxHmuiqkMYfGW2G3eGVd7sqMWxaxDTqu5zDBaL2vI6T7_oNgHqGGzED_eRiIOWtwaU6kcrE3jJFOJaEeRMrxutC0vEqiYk8KSaoxHV0ssL8hW312ljpmSw', '2024-01-07 20:17:12', '2024-01-07 20:17:12'),
(8, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABlmsJNy4uCipVXO1tqsrP7KpZ0xvzcP8PYQdHUk-f7wOq8GrE6TrKKvgKF0WbMtPwneurNvLDFqGa0aUUSjmC_8KwohjkAVHSwBS6_On4sWLYCJVNbFhquR0xwihGkk5IK-ujSRVmYTIN_-ks3pcSF2FOR_myXi4TAAA4cQCUfArFBcGw', '2024-01-07 20:25:02', '2024-01-07 20:25:02'),
(9, 'https://fcm.googleapis.com/fcm/send/fw-cGRrWWbI:APA91bF9zfcQen0By0xA_GkaJK79t19PZbGi6Hi51mgX1Xe1WA_lWAPkyOsHWzYAUgMCgHf3MPopVI7UIhKXVYA9h-8jUKukC40ghU87N3CTNh9SxwUHWN5hQahYVWcvwJmbHoQvbgj3', '2024-01-22 11:14:12', '2024-01-22 11:14:12'),
(10, 'https://fcm.googleapis.com/fcm/send/cf2Pbk0ekxY:APA91bFizcuL8aioJnhYNICnMYs02AYQe-2LGsREzvI25BVrZMH1-NlfA-27miuW4122lryoRT6AYfRqSy44pNptw2vLbtCidRZM6sQV-yUTHAX_28MGM1sSeE27H42HipNW3FSjUupZ', '2024-01-25 22:35:03', '2024-01-25 22:35:03'),
(11, 'https://fcm.googleapis.com/fcm/send/fY3XNA_v6Tk:APA91bG3-rK2BQyglYfiCsoq7JIN55AXZQ-4G1EtApw2mSGIIVOY3X4aB2lysIuZjZmO-tFD1NcWRqZxg8aRJsYsott9gfIPANN04Vy2LuIKSmDWzG7fn8OOX965h4X8Rve21gH6ELBB', '2024-01-25 22:57:39', '2024-01-25 22:57:39'),
(12, 'https://fcm.googleapis.com/fcm/send/eSoWoPJu330:APA91bE2L--KgmCBpsDA0G1zNv2m6Y0IXoFwSIPIsKwo5Zgri_9pVGZDNzlQ5f4zi2_jFtYRHLmzvQ6tXpqPDQ4n1vO10NkTj_xorSgQjeRSCF_xneTualDe4uOhODX0oFCl8kVqXE-V', '2024-01-25 23:34:14', '2024-01-25 23:34:14'),
(13, 'https://fcm.googleapis.com/fcm/send/fOHDeoHK3nQ:APA91bH5bkUe6TLvdHBcu0N3hdRtZM0S3l9FC75cqJzraP3cemfPYYTy7uNihLu5alagkd-UzITvuFuQ7J2lYrac7E54puI010ytw6GeaaZ1ASthavGcIVQ5lDcmWDIKLpfzIwDmNW5P', '2024-01-25 23:42:09', '2024-01-25 23:42:09'),
(14, 'https://fcm.googleapis.com/fcm/send/dPgnfKB1AiU:APA91bHclqfRaMvPo17psR_SszFfluCIw__YHaTLtGlfdILgYd09ZIOzeXbsQlOwQ97qvXbcGQi-gs7Va-ChwT-u3ZEGXL-FvBzbchB-8Eqn-mMGV9DfpqybaVByT0o6a5iBCytkjKj1', '2024-01-25 23:44:14', '2024-01-25 23:44:14'),
(15, 'https://fcm.googleapis.com/fcm/send/elXfq9uSmKc:APA91bFP0ECX2EVWibMVWapjHKez4vc76vHRWIcWwe7CCtaX2SFpw0I2N1OhyacA5DjWeZeGmqHrDxiMTEpzuodypwXTFqLRerw-J8VaJXXak4txbtlR9zCOVMe62kI0wAkmTTUf_0aw', '2024-01-25 23:44:15', '2024-01-25 23:44:15'),
(16, 'https://fcm.googleapis.com/fcm/send/fxfrqc0mDks:APA91bHkp_DHGeXza6sHkQq7FktpUEW7tl69ZJytEdQ5GDKuxzu8e55v_JBJdchHnbOuRAa9Mq6zKoQVjeVBHYhrSgcqf4_SOeMmS2iThJ3sfyy1riZAgZAbDDOw-I0QZj3E1foi5J9c', '2024-01-25 23:50:07', '2024-01-25 23:50:07'),
(17, 'https://fcm.googleapis.com/fcm/send/e_5vHIEyM6o:APA91bFMdzcp1iHTyw2EpQfJ94t5rHyx020Fui9hFQyMmp8TL6KAlsLV9MBoYNQJxk7JbOJe800ODWlQINvc2T9xT2mS3gIkyDsXPtDVGmop65WlOa7JlzhN1XufK2jfK2cbWo78jjDw', '2024-01-26 00:10:27', '2024-01-26 00:10:27'),
(18, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABlsrsylEcHEcMPTsuxOSaADPpBF69iEbYk5Ev7MTPSaHfgCHcTS9XPjpda7jZFDaUR4paPQ9U0b-orXAot-Vu4A8O3AaKmnzicQzh3VsRuLLtnUulUV0dm2PLO2KIy3XSPMd2czmPDVlBOgcYfnip9VymiO9AVY6qk7dCC8syWm9bIXpk', '2024-01-26 00:49:07', '2024-01-26 00:49:07'),
(19, 'https://fcm.googleapis.com/fcm/send/evn3OHN65t4:APA91bHx5llOsStdanXu_kM3C-ohV0WO_fskgnyKtcO726w7OkyB_eTddPKLvrtvwEw_bWqwYLwpPkOv1eV6v8F-wTKjR1XIy2oLvMwCHvrWt-WcuW6-tYc8k9-5tKI7Wy5fVA-reifj', '2024-01-26 02:03:31', '2024-01-26 02:03:31'),
(20, 'https://fcm.googleapis.com/fcm/send/fTzgkqYF4t8:APA91bGvaqMmBI1o89JBIPB60C2-Ik1PdEdoX4ZQn2Dp7m09d_7wQ1tL2ZeugrZQbPY9sBd-naUzYKlAMcPD2zpUDmdFCnoaePAenVyLjHxm9Dcf6GkATvDqZjlYxPA_0DzRW9n-8iX-', '2024-01-26 02:37:18', '2024-01-26 02:37:18'),
(21, 'https://fcm.googleapis.com/fcm/send/ey4Fp9UkvjE:APA91bHeutWYJvR343pF9nTdf9hzoUqB2lEnAmzw6TvOVfEyDUIe-i_u9Q9T9LaYLAWbnuIIdt3b-ACIHydK5nB77LV_9U6JAUHXbOwsJeF8FPAo6r6Ya7LS0glUwi_7LOB7DPmz5qsV', '2024-01-26 04:31:11', '2024-01-26 04:31:11'),
(22, 'https://fcm.googleapis.com/fcm/send/dLrXMBDNT1Q:APA91bEZLW8q2IyjIfK5Y7QMlMIRCxc-cRzvVZupGLIXlquBDpSU_AuyOnWS11xvZZBbIJBFZH6P7-XjYhXVQPhvgzQmq_nAjsfZzNKipS-tuvQq74ZZIRDHI42g2cQzPHgTim4upe5F', '2024-01-26 04:34:27', '2024-01-26 04:34:27'),
(23, 'https://fcm.googleapis.com/fcm/send/dhI-Bsb9zZ4:APA91bGltEjWBeOppoqG_0IQgaR_uEwUksJ2z2_yAguTuOuXUlKn6jjKOpjTCP7IwacjqbTlYbetUSgXy2uny4KPYZKR30hTnk-9jnk7nviQsMsYgt33DVI4PZ0FSEsVitClkpCPajR-', '2024-01-26 06:43:56', '2024-01-26 06:43:56'),
(24, 'https://fcm.googleapis.com/fcm/send/cADi8-Ed12M:APA91bHGo6_Aj8pfEgVWLO-BvXmwmNnUfa25zeJ89gjaXZjyFEi1eyVmnXFYOs59lG9VlpdPCSXwCYMMNa3wnhpM1mmJ3_XlIkGoEnwmxYGVJa1PaFtbe7_JWNTT7qR1-ocNsDdGcXYM', '2024-01-26 08:40:08', '2024-01-26 08:40:08'),
(25, 'https://fcm.googleapis.com/fcm/send/fG8r5_xT3z4:APA91bFEp9-7xy-Lr0_VbSdy_VFtRp69JwmCDTbm02TBpzc4ShpPPwieuJHQ5Lfm-Dsnqv25mvcmjOnfqsagPBAmvO6mA_8dDzBMA77jQHCm47du94C7N8GmUtx1_ULoARhl8zBhsQ1m', '2024-01-26 08:48:50', '2024-01-26 08:48:50'),
(26, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABls0fan6wubz8VmZ2sA5_2OYrULseMi3fzJv23f10eYF0v1QUykOnmaAdMsbOwEj8nhprUzV2lmKEdnc6yCPeCOcr2VL1s8W_nhmZHYO3u9j5ZLZv3EIXgaN_kG18d_K55hZknJt7tDtrgWgxGQDWAUHwZJuQNYB-RQvj8pJBTQrURIxA', '2024-01-26 10:49:15', '2024-01-26 10:49:15'),
(27, 'https://fcm.googleapis.com/fcm/send/dxBz5I1_PpA:APA91bFFU_bH7O40Gv9sh9yslXVGuU6uWJxXr7sWGfERV9lVpqd6uVDmeQWQF0t33tgR6Lob5iEHXIFqt7W2C6ekQkzo9CGBuxwOh1In_vD69ZNTDiBD_kNtsENyWXk5Zx0k6piL7pxS', '2024-01-26 10:56:36', '2024-01-26 10:56:36'),
(28, 'https://fcm.googleapis.com/fcm/send/fcjc5m3N4Qc:APA91bFF3EGf7nXPIm0gOVPK73ZUnOUcJFH6Mpe9rSAVe94HW7VlWC-YWrGEGg9K54f-PiJERK6AKFgpQM-Jwktm13Jw3n0a8RW90LDnGm_V507-BqIHomZRcQWzJOj9_-NkVvI33fk8', '2024-01-26 11:01:48', '2024-01-26 11:01:48'),
(29, 'https://fcm.googleapis.com/fcm/send/fhXyFntgfkc:APA91bGFZOdcnjT1IFOjX3gSrRfgO4Qt4VajFgWIhJ9bvzAzTIxrG_9gQVWbceXJiFesAImy-o_kbxVoqnusp-ArsxDea-JhOOhX-Y0yzZgdste4y-0mHsHODPdOLwhcbWSswYETXnoa', '2024-01-26 12:00:26', '2024-01-26 12:00:26'),
(30, 'https://fcm.googleapis.com/fcm/send/fet8GSmnYto:APA91bHHw8K75hjSdRaZqiJzMPY6l_SHzKh3WUPHISsW-57RR8UfSNyjU1Ah_p0yIdzU9kfUnN8keYqZ7vRTFFbRm4gdajUBws6yFStyCcSOAVKjkgwirk_KK91pl-QmU7r0pbWGxmqB', '2024-01-26 14:24:40', '2024-01-26 14:24:40'),
(31, 'https://fcm.googleapis.com/fcm/send/fv0waEXMWTw:APA91bHwYAjYMVNZvwTM9mzvNgutb-GXHGdFv1M2Yc3t80XgBjFddj0G-JWPL5XanI_l_A_eDWBjdfZtiVts45rhpdOnpm2LKYE3kQjS2jEBzgpftL2oPYWErCPveOvMnmi3RE7J5zsy', '2024-01-26 14:34:43', '2024-01-26 14:34:43'),
(32, 'https://fcm.googleapis.com/fcm/send/feQ0flyOEcY:APA91bFZx2iZ6ElHuPE7GUke9MjvLsq_aX7QP2oKchzLU5U7C-U5KtDuYT_o2FDI2rCbbPLZIZ3M6DsD4xISrfrvLbI7EcHgplCtOxk1Gp8iIpsYsrGmr4dYUHgJ7NBOCr8KdSrwqImf', '2024-01-26 15:43:34', '2024-01-26 15:43:34'),
(33, 'https://fcm.googleapis.com/fcm/send/cpILVB3wpsY:APA91bGQ-sTncefT086lDiTDneWoZ_WQdlr1VcBc8ag-xP8UL-K_2WfbFWsh8i5KQI_YwqXALLhojbUD2jUtJTJJU4RfxMcc1Lo-KW0dlyI63ZZ6gcHvI03PQsn1f-vG6AmLXRwgs0w8', '2024-01-26 16:37:34', '2024-01-26 16:37:34'),
(34, 'https://fcm.googleapis.com/fcm/send/fUmte5rnlIk:APA91bHT-VisnJkV-DqrcfoDf1vK1Tn6Tejf4Cgj8j4aIRjU_wT6NVVhVtlwuSFmXqEBDaptixPcdetyKG97CGvCZfiqPHVnQ3_3alUCK161RusEK-IhsWI2KszWwjdW6aqfn06GDlIs', '2024-01-26 17:03:33', '2024-01-26 17:03:33'),
(35, 'https://fcm.googleapis.com/fcm/send/eQWjUnKndCc:APA91bFBR93TXu153LHUx7mIk0dUtoSCOBgXVwmZ56hFHmeUrjTgpWIlxSizoBo18cNDjoEz7YReq90YbtilXFQjCdASTrGK_pW4nOjENFY7IlDbllaKbLA1Px31IW7Sw-GQETdqnwNm', '2024-01-26 17:22:46', '2024-01-26 17:22:46'),
(36, 'https://fcm.googleapis.com/fcm/send/dsirQJucsB0:APA91bHycmGN_r-0o5XcaCaG61Y7c3BtPjVhczvwpyYuxq5k7aLpfWpHxXox2t1R-nKuQ683IHHjU8-0j6P8a-xQ-ZboFa0ejqcfLOncwLLSgvPRNpDRYNwNunaRwurphJjuQ08tYbNa', '2024-01-26 18:01:31', '2024-01-26 18:01:31'),
(37, 'https://fcm.googleapis.com/fcm/send/cGvGSeyW3l8:APA91bEtXvUwwgrXLH1OZ6NRBIpK2oZ3Qcmlg7DR5SK2CPS_Pl0IVmqdIk514DpRWFmWNFZjst3WQFmZ6Cq4txXljfLV71mX7RTDnr0TnKxpWPj7-H7wTYCFURmMkQBDJtYCgG8T3Kkl', '2024-01-26 21:17:58', '2024-01-26 21:17:58'),
(38, 'https://fcm.googleapis.com/fcm/send/dY13_Dk0haY:APA91bFKoNoi6EaxP6k2vEih_KSzlDiDGL2oyuQMiU0YG8p1_Hnqp2hlRXkWmO1-Sx2rJJxP0XnNpgLlt5_DKPkbzmR24AZKMb-D_PNEFYRtMlCbqUFK9as7mJLRxedp9GAbLyLCFjtC', '2024-01-26 22:58:38', '2024-01-26 22:58:38'),
(39, 'https://fcm.googleapis.com/fcm/send/es63u_cHbXs:APA91bFZgMM0ARlpSdKAjmqpxDI8sVT5z2lCumPjTibA4olo46ijIYHz9yKrIBQD_45DXD0Y0p-GRWmsg6tuMhOKxWpzLbeiEl29VtRNLk8sjX16VMP3wOK0YttB34vRoBFc4F05fAEq', '2024-01-26 23:04:44', '2024-01-26 23:04:44'),
(40, 'https://fcm.googleapis.com/fcm/send/cDi0HYd_6wU:APA91bGpfwGzMCaDgxs4CDZRwMaF3Cc-xAnrNHQMORl3fyLAyditYPO56m5kwQcoXo7ZoWXn2QsBe2Ce3xpCezeWhM4LL3PBLW62WQefBLLf-m8KGrNUkjVjSlRAXqxkBWXgp50XKakb', '2024-01-26 23:05:46', '2024-01-26 23:05:46'),
(41, 'https://fcm.googleapis.com/fcm/send/f24-34ycVFo:APA91bE_TKow3vnTmHK-vEN8NRFfzMRICZeJRvoG1zy9d1sSHSKCYoVeu_uWK_vKE7i0riqUAUlBOruoYN52G7bKqDfH_QlymLZvB7COPukGxyndXdIAzxauSODIM3po5r0evDTZDmIG', '2024-01-27 00:18:28', '2024-01-27 00:18:28'),
(42, 'https://fcm.googleapis.com/fcm/send/fNQyUC3c-zg:APA91bFMAnr2hm-Li6lnRtp6SB3p9FRt6VeXSu-mxxFoTpJyPBnALmbJWX03ZHoL16nzvsqgOKzTrwsfd_AaKMh8xXGBL6UF2zR1NGRHD7NXKFE5DXqmn5aq-7feVnozNbtb1Lc48GQV', '2024-01-27 00:18:39', '2024-01-27 00:18:39'),
(43, 'https://fcm.googleapis.com/fcm/send/cm90FBHTsLA:APA91bF7j7AmiB7S5xWtD8eCnoyOF10OolReiImcDZrcMVvX81VqU1TeDddVvdotQ-y0yYrD8TgovpQcDL3-Qlc1caRGOdZfS90sJmtH13pEVO94-468sjasUlgQE4cTjSJ-nD0oPLmT', '2024-01-27 00:27:08', '2024-01-27 00:27:08'),
(44, 'https://fcm.googleapis.com/fcm/send/cyHV7exVGLE:APA91bFe0RgADILCfxCYzEYeVk7rGgr75KE-aYFIMXCabLROzn3OMq-RGDKqTz3c9i0dqvD0PGZBLEueRqUPejmWTU9GamBa8Wmzn7bzormeN3G6-faTTprAjK-AIt5Pu4eUSts2hvkP', '2024-01-27 00:43:01', '2024-01-27 00:43:01'),
(45, 'https://fcm.googleapis.com/fcm/send/cH0YgWw_HQY:APA91bGp4iaLBEiI7Uodh3gAhFzH_MeBk2o8uGx04zss2m5_PvfzXHDysuQsZpmOcTFpX8gnTo87FXMAhV-mNO26EQjpJlT5vfUArEHr28ar8atNlsrjSG4ilL45HszFnUCR4BaxUW6g', '2024-01-27 00:54:54', '2024-01-27 00:54:54'),
(46, 'https://fcm.googleapis.com/fcm/send/diF8IBRmiGY:APA91bHGBdRuvvq2XVtVWgYIEyf-i_QBCOvdQFWldwXuWyTrc0uCE4WiFTY8IFMjpJqxqExIALe40hmMM58VPkhZfmCOcZ5y1M4XCM41Rv8nXWh1BlBoDf1In5xunVdss0dTBXkpi9zC', '2024-01-27 00:55:05', '2024-01-27 00:55:05'),
(47, 'https://fcm.googleapis.com/fcm/send/d9a03o7EtxE:APA91bGeTuYhguSJCK-y7TMWvvrCUInNT6waxGHy-SkH8ot7dZvQZ8ZHu7RUIRdIkbCqfP9zK1VgTwZ3H3fwDsXMGPnGaAoSBCeB_zhT8iqvOIW6RI7t58MkhcmbDgM45RucchMI7VSI', '2024-01-27 02:23:25', '2024-01-27 02:23:25'),
(48, 'https://fcm.googleapis.com/fcm/send/eEche4lhVpc:APA91bGg07Tyzma2nBJSvrIBRai8G0ppx9rzdzH5NDqfqnrTOiJZijs0Y3yxsJ00pSrMbKsQC93r2oUGjqZiqjqXLGIdj75feBIZJ0Y9L5xvGQWGZ45FSAmY9iLe-FsY5seS7fCP2Qma', '2024-01-27 02:28:05', '2024-01-27 02:28:05'),
(49, 'https://fcm.googleapis.com/fcm/send/fC8vkUbWm4k:APA91bEVVjADAOmGY5YpHdmOTz9NVLAsTTziiNgEKHpILjWVbkB0pYLxu-FN4MpoOp9909Q2GjuMurcnjgNZ_zUKcTAvni2zHZGpLi0YfNEfiQA66OVZ-sxeibZZQ0T939OBPTSsHEQT', '2024-01-27 03:54:59', '2024-01-27 03:54:59'),
(50, 'https://fcm.googleapis.com/fcm/send/ckP5aO1bjgQ:APA91bFNGQaTbsKHnmN06wE2yi-mpPWh77JJQ8XHOPMOHMrGxyibFsc6_ZEs8rLdhsqKVpMEL2OTJSjJdHx8kNgl6uMuH72KP1nu1Lo0KokQeqdZlCp2mX-mSWb5ze8_g4syuABghq1K', '2024-01-27 04:08:56', '2024-01-27 04:08:56'),
(51, 'https://fcm.googleapis.com/fcm/send/e0xXserOg84:APA91bFnfPQa-ou4kUdA1k3sokEFzVFIdOFux1IakwU-jMhnHr08MHkoMv5KwNNfyV7dveCBcIkoVF3IAO4QpkAMvTvmq1Rj85KkzsfXobASMx9WOA2_u43F-5-DDftdjeB7sI3wxa0t', '2024-01-27 04:50:48', '2024-01-27 04:50:48'),
(52, 'https://fcm.googleapis.com/fcm/send/fqmjnez3j3o:APA91bEAUvXu9tN9JO5w-hoxK9bCFHlx2u2ITZujzrhHqp9N45XSgRNgJOROUAn7zVWjavafaCHRC_17IUXlG1gh8nSCYsP_9KyvfZ6t6vFA7vQdi3TyXSHHaExU2DRPra0uvpBgOy6U', '2024-01-27 09:38:43', '2024-01-27 09:38:43'),
(53, 'https://fcm.googleapis.com/fcm/send/cxLTSsNbGNI:APA91bEcW1THEiz6svG0mYIpN0DzkwsoVkZPDzwrbp8Hc6zFkZwmsoTu4TnrkrSFrNbB4fPfgJ9vOqNEohWp22gZ770dsvgKALT1exNrI2RP_ob1uS0SQ5HSx3EZ5CaBUKXhGujw2OjW', '2024-01-27 05:18:06', '2024-01-27 05:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `hero_sliders`
--

CREATE TABLE `hero_sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hero_sliders`
--

INSERT INTO `hero_sliders` (`id`, `language_id`, `image`, `title`, `text`, `created_at`, `updated_at`) VALUES
(16, 9, '64bf898657477.png', 'نحن نعتني بنمو عملك', 'ولكن لكي تفهم من أين وُلد كل هذا الخطأ ، واتهم لذة الأول وألم مدحهم ، سأفتح كل شيء ، وتلك الأشياء ذاتها التي جاءت من مخترع الحقيقة هذا ، وهي أمور أخرى.', '2022-07-19 06:55:33', '2023-07-25 08:36:22'),
(18, 8, '658c1277db755.png', 'Explore the Perfect Freelancer for Your Next Project', 'Explore a Diverse World of Skills and services Offered by Expert Freelancers, Connecting You to the Perfect Match', '2023-07-25 08:35:36', '2023-12-27 12:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `hero_statics`
--

CREATE TABLE `hero_statics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hero_statics`
--

INSERT INTO `hero_statics` (`id`, `language_id`, `title`, `text`, `created_at`, `updated_at`) VALUES
(14, 8, 'The Easiest Way to Find & Hire Skills Talent for Projects', 'Explore a Diverse World of Skills and services Offered by Expert Freelancers, Connecting You to the Perfect Match', '2022-06-22 08:42:24', '2024-01-02 06:50:08'),
(15, 9, 'نحن نقدم لك خدمات رقمية عالية', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-07-21 09:29:00', '2022-07-21 09:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` tinyint(4) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `direction`, `is_default`, `created_at`, `updated_at`) VALUES
(8, 'English', 'en', 0, 1, '2021-05-31 05:58:22', '2023-12-13 11:14:27'),
(9, 'عربي', 'ar', 1, 0, '2021-05-31 05:59:16', '2023-11-08 04:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` int(11) NOT NULL,
  `mail_type` varchar(255) NOT NULL,
  `mail_subject` varchar(255) NOT NULL,
  `mail_body` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `mail_type`, `mail_subject`, `mail_body`) VALUES
(4, 'verify_email', 'Verify Your Email Address', '<table class=\"m_2450577039782362685body\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td align=\"center\" valign=\"top\" bgcolor=\"#F6F6F6\"><center>\r\n<table class=\"m_2450577039782362685container\" style=\"width: 78.3088%;\" align=\"center\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<table class=\"m_2450577039782362685row\">\r\n<tbody>\r\n<tr>\r\n<th class=\"m_2450577039782362685small-12 m_2450577039782362685columns\">\r\n<table style=\"width: 100.096%; height: 191.953px;\">\r\n<tbody>\r\n<tr style=\"height:22.3906px;\">\r\n<th style=\"width:97.6447%;height:22.3906px;\"> </th>\r\n</tr>\r\n<tr style=\"height:169.562px;\">\r\n<td style=\"width: 97.6447%; height: 169.562px;\">\r\n<p style=\"text-align:left;\">Hi {username},</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">We need to verify your email address before you can access your dashboard.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Please verify your email address by visiting the link below:</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">{verification_link}.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Thank you.<br />{website_title}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</center></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><img class=\"CToWUd\" src=\"https://ci5.googleusercontent.com/proxy/_L2S_yn8V9jLvAeR1rLPF3qmrQLBqWlB2DJfAQ4SBEhv-VqAJHg0FK6cmT99y8m9R1G1BC_i2FWCFmHGlcjnIExwE3rNqaUN1-ayp0bawEaxVCbLEGpJ7JQDR4jbczNq_1DXjqcVXXnTza_LEegpL2x792ZGjaA8Y594GJqeVxtjqM2LA5kDTgdYFWW8sGb8UQzAetE2hKnCmyIkYvcqSFceBQcSFT_B7jgjI_qLUCiOPLf8IAudBTPMNjeesYBhKmRLScTVpcAyb1ASUfoBwueWDC3I8AHTpsbotgLJks5ipgbiZSINWL1bG_qw0pI_JbMPhCaSek6I-f4QsLYRd6oAUcdol5y2dXTkzr3WmL1K1lZ8lr1i6eJ8FDsTtGwlLTwxv9-kUCCT2UfqHxbUGnGTPYOHH74ytkpK=s0-d-e1-ft#http://email-link.overleaf.com/wf/open?upn=CB7nsy4cUUrMEy00dVC7xtkixf1jGRQiRmv9ytghPG-2F9iMBvteO1eyfwjvE7n-2FPrXViQOvivqNnn9vNEH7KuOUPk6gWzhzmBjtlf6gat86vo9nJtlVPWo-2BQ6DCAkJV4JpOTwpu0-2FMAzexK9bw6PGBTnX5GD5nNe2ed6hROW6IDmeUd0gh2F5IV42PVhMQ-2B0gYOp39DeLXW7PovcBulw-2BrA8qlCawgAjpBtNzRd-2Bl3Hk-3D\" alt=\"\" width=\"1\" height=\"1\" border=\"0\" /></p>'),
(5, 'reset_password', 'Recover Password of Your Account', '<table class=\"m_2450577039782362685body\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td align=\"center\" valign=\"top\" bgcolor=\"#F6F6F6\"><center>\r\n<table class=\"m_2450577039782362685container\" style=\"width: 78.3088%;\" align=\"center\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<table class=\"m_2450577039782362685row\">\r\n<tbody>\r\n<tr>\r\n<th class=\"m_2450577039782362685small-12 m_2450577039782362685columns\">\r\n<table style=\"width: 100.096%; height: 191.953px;\">\r\n<tbody>\r\n<tr style=\"height:22.3906px;\">\r\n<th style=\"width:97.6447%;height:22.3906px;\"> </th>\r\n</tr>\r\n<tr style=\"height:169.562px;\">\r\n<td style=\"width: 97.6447%; height: 169.562px;\">\r\n<p style=\"text-align:left;\">Hi {customer_name},</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">We have received a request to reset your password. If you did not make the request, just ignore this email. Otherwise, you can reset your password using the below link.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">{password_reset_link}</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Thanks,<br />{website_title}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</center></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><img class=\"CToWUd\" src=\"https://ci5.googleusercontent.com/proxy/_L2S_yn8V9jLvAeR1rLPF3qmrQLBqWlB2DJfAQ4SBEhv-VqAJHg0FK6cmT99y8m9R1G1BC_i2FWCFmHGlcjnIExwE3rNqaUN1-ayp0bawEaxVCbLEGpJ7JQDR4jbczNq_1DXjqcVXXnTza_LEegpL2x792ZGjaA8Y594GJqeVxtjqM2LA5kDTgdYFWW8sGb8UQzAetE2hKnCmyIkYvcqSFceBQcSFT_B7jgjI_qLUCiOPLf8IAudBTPMNjeesYBhKmRLScTVpcAyb1ASUfoBwueWDC3I8AHTpsbotgLJks5ipgbiZSINWL1bG_qw0pI_JbMPhCaSek6I-f4QsLYRd6oAUcdol5y2dXTkzr3WmL1K1lZ8lr1i6eJ8FDsTtGwlLTwxv9-kUCCT2UfqHxbUGnGTPYOHH74ytkpK=s0-d-e1-ft#http://email-link.overleaf.com/wf/open?upn=CB7nsy4cUUrMEy00dVC7xtkixf1jGRQiRmv9ytghPG-2F9iMBvteO1eyfwjvE7n-2FPrXViQOvivqNnn9vNEH7KuOUPk6gWzhzmBjtlf6gat86vo9nJtlVPWo-2BQ6DCAkJV4JpOTwpu0-2FMAzexK9bw6PGBTnX5GD5nNe2ed6hROW6IDmeUd0gh2F5IV42PVhMQ-2B0gYOp39DeLXW7PovcBulw-2BrA8qlCawgAjpBtNzRd-2Bl3Hk-3D\" alt=\"\" width=\"1\" height=\"1\" border=\"0\" /></p>'),
(11, 'service_order', 'Service Order Has Been Placed', '<p>Hi {customer_name},</p><p>Your order has been placed successfully. We have attached an invoice in this mail.<br />Order No: #{order_number}</p><p>{order_link}<br /></p><p>Best regards.<br />{website_title}</p>'),
(12, 'payment_success', 'Payment Success', '<p>Hi {customer_name},</p><p>Your payment is completed. We have attached an invoice in this mail.<br />Invoice No: #{invoice_number}</p><p>Best regards.<br />{website_title}</p>'),
(15, 'user_register_success', 'Successfully Register Your Account', '<p>Hi {username},</p>\r\n<p>You have successfully crate an account. Now you can access your dashboard.</p>\r\n<p>Thank you.<br />{website_title}</p>'),
(18, 'membership_extend', 'Your membership is extended', '<p>Hi {username},<br><br>This is a confirmation mail from us.<br>You have extended your membership.<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br><strong>Activation Date:</strong> {activation_date}<br><strong>Expire Date:</strong> {expire_date}</p>\r\n<p> </p>\r\n<p>We have attached an invoice with this mail.<br>Thank you for your purchase.</p>\r\n<p><br>Best Regards,<br>{website_title}.</p>'),
(19, 'registration_with_premium_package', 'You have registered successfully', '<p>Hi {username},<br /><br />This is a confirmation mail from us</p>\r\n<p><strong><span style=\"font-size:18px;\">Membership Information:</span></strong><br /><strong>Package Title:</strong> {package_title}<br /><strong>Package Price:</strong> {package_price}</p>\r\n<p><span style=\"font-weight:600;\">Total:</span> {total}<br /><strong>Activation Date:</strong> {activation_date}<br /><strong>Expire Date:</strong> {expire_date}</p>\r\n<p> </p>\r\n<p>We have attached an invoice with this mail.<br />Thank you for your purchase.</p>\r\n<p><br />Best Regards,<br />{website_title}.</p>'),
(20, 'registration_with_trial_package', 'You have registered successfully', 'Hi {username},<br /><br />\r\n\r\nThis is a confirmation mail from us.<br />\r\nYou have purchased a trial package<br /><br />\r\n\r\n<h4>Membership Information:</h4>\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}<br /><br />\r\n\r\nWe have attached an invoice in this mail<br />\r\nThank you for your purchase.<br /><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br />'),
(21, 'registration_with_free_package', 'You have registered successfully', 'Hi {username},<br /><br />\r\n\r\nThis is a confirmation mail from us.<br />\r\nYou have purchased a free package<br /><br />\r\n\r\n<h4>Membership Information:</h4>\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}<br /><br />\r\n\r\nWe have attached an invoice in this mail<br />\r\nThank you for your purchase.<br /><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br />'),
(22, 'membership_expiry_reminder', 'Your membership will be expired soon', 'Hi {username},<br /><br />\n\nYour membership will be expired soon.<br />\nYour membership is valid till <strong>{last_day_of_membership}</strong><br />\nPlease click here - {login_link} to log into the dashboard to purchase a new package / extend the current package to extend your membership.<br /><br />\n\nBest Regards,<br />\n{website_title}.'),
(23, 'membership_expired', 'Your membership is expired', 'Hi {username},<br><br>\n\nYour membership is expired.<br>\nPlease click here - {login_link} to log into the dashboard to purchase a new package / extend the current package to continue the membership.<br><br>\n\nBest Regards,<br>\n{website_title}.'),
(24, 'payment_accepted_for_membership_extension_offline_gateway', 'Your payment for membership extension is accepted', '<p>Hi {username},<br><br>This is a confirmation mail from us.<br>Your payment has been accepted &amp; your membership is extended.<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br><strong>Activation Date:</strong> {activation_date}<br><strong>Expire Date:</strong> {expire_date}</p>\r\n<p>Best Regards,<br>{website_title}.</p>'),
(25, 'payment_accepted_for_registration_offline_gateway', 'Your payment for registration is approved', '<p>Hi {username},<br /><br />\r\n\r\nThis is a confirmation mail from us.<br />\r\nYour payment has been accepted & now you can login to your user dashboard to build your portfolio website.<br />\r\n\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(26, 'payment_rejected_for_membership_extension_offline_gateway', 'Your payment for membership extension is rejected', '<p>Hi {username},<br /><br />\r\n\r\nWe are sorry to inform you that your payment has been rejected<br />\r\n\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(27, 'payment_rejected_for_registration_offline_gateway', 'Your payment for registration is rejected', '<p>Hi {username},<br><br>We are sorry to inform you that your payment has been rejected<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br>Best Regards,<br>{website_title}.</p>'),
(28, 'admin_changed_current_package', 'Admin has changed your current package', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has changed your current package <b>({replaced_package})</b></p>\r\n<p><b>New Package Information:</b></p>\r\n<p>\r\n<strong>Package:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(29, 'admin_added_current_package', 'Admin has added current package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has added current package for you</p><p><b><span style=\"font-size:18px;\">Current Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(30, 'admin_changed_next_package', 'Admin has changed your next package', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has changed your next package <b>({replaced_package})</b></p><p><b><span style=\"font-size:18px;\">Next Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(31, 'admin_added_next_package', 'Admin has added next package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has added next package for you</p><p><b><span style=\"font-size:18px;\">Next Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(32, 'admin_removed_current_package', 'Admin has removed current package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has removed current package - <strong>{removed_package_title}</strong><br>\r\n\r\nBest Regards,<br />\r\n{website_title}.<br />'),
(33, 'admin_removed_next_package', 'Admin has removed next package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has removed next package - <strong>{removed_package_title}</strong><br>\r\n\r\nBest Regards,<br />\r\n{website_title}.<br />'),
(34, 'withdraw_approve', 'Confirmation of Withdraw Approve', '<p>Hi {seller_username},</p>\r\n<p>This email is confirm that your withdraw request  {withdraw_id} is approved. </p>\r\n<p>Your current balance is {current_balance}, withdraw amount {withdraw_amount}, charge : {charge},payable amount {payable_amount}</p>\r\n<p>withdraw method : {withdraw_method},</p>\r\n<p> </p>\r\n<p>Best Regards.<br />{website_title}</p>'),
(35, 'withdraw_rejected', 'Withdraw Request Rejected', '<p>Hi {seller_username},</p>\r\n<p>This email is to confirm that your withdrawal request  {withdraw_id} is rejected and the balance added to your account. </p>\r\n<p>Your current balance is {current_balance}</p>\r\n<p> </p>\r\n<p>Best Regards.<br />{website_title}</p>'),
(36, 'balance_add', 'Balance Add', '<p>Hi {username}</p><p>{amount} added to your account.</p><p>Your current balance is {current_balance}. </p></p><p><br></p><p>Best Regards.<br>{website_title}<br></p><br>'),
(37, 'balance_subtract', 'Balance Subtract', '<p>Hi {username}</p>\r\n<p>{amount} subtract from your account.</p>\r\n<p>Your current balance is {current_balance}.</p>\r\n<p>Best Regards.<br />{website_title}</p>\r\n<p> </p>\r\n<p> </p>'),
(38, 'add_user_by_admin', 'Admin has been added your account.', '<p>Hi {username},</p>\r\n<p>Admin has been added to your account as a \'{user_type}\'.</p>\r\n<p>Your username: {username} and password: #{password}</p>\r\n<p>Best regards.<br />{website_title}</p>');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price` double DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_trial` tinyint(4) NOT NULL DEFAULT '0',
  `trial_days` int(11) NOT NULL DEFAULT '0',
  `receipt` longtext COLLATE utf8_unicode_ci,
  `transaction_details` longtext COLLATE utf8_unicode_ci,
  `settings` longtext COLLATE utf8_unicode_ci,
  `package_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `modified` tinyint(4) DEFAULT NULL COMMENT '1 - modified by Admin, 0 - not modified by Admin',
  `conversation_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_builders`
--

CREATE TABLE `menu_builders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `menus` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu_builders`
--

INSERT INTO `menu_builders` (`id`, `language_id`, `menus`, `created_at`, `updated_at`) VALUES
(1, 9, '[{\"text\":\"مسكن\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"خدمات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"services\"},{\"text\":\"التسعير\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"البائعين\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"sellers\"},{\"text\":\"الصفحات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"مقالات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"سياسة الخصوصية\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"سياسة-الخصوصية\"},{\"text\":\"البنود و الظروف\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"البنود-و-الظروف\"},{\"text\":\"التعليمات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"}]},{\"text\":\"عن\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about\"},{\"text\":\"اتصال\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2021-11-18 04:50:31', '2024-01-17 14:09:38'),
(3, 8, '[{\"text\":\"Home\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Services\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"services\"},{\"text\":\"Pricing\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"Sellers\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"sellers\"},{\"text\":\"Pages\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"Privacy Policy\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"privacy-policy\"},{\"type\":\"terms--conditions\",\"text\":\"Terms & Conditions\",\"target\":\"_self\"},{\"text\":\"Blog\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"FAQ\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"}]},{\"text\":\"About\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about\"},{\"text\":\"Contact\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2022-05-11 03:26:11', '2024-01-20 15:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2023_12_30_110825_create_cta_section_infos_table', 1),
(3, '2024_03_31_015344_add_new_9_payment_gateway_into_online_gateways_table', 2),
(4, '2024_03_31_021950_add_conversation_id_to_service_orders_and_memberships_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `offline_gateways`
--

CREATE TABLE `offline_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8_unicode_ci,
  `instructions` longtext COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> gateway is deactive, 1 -> gateway is active.',
  `has_attachment` tinyint(1) NOT NULL COMMENT '0 -> do not need attachment, 1 -> need attachment.',
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `offline_gateways`
--

INSERT INTO `offline_gateways` (`id`, `name`, `short_description`, `instructions`, `status`, `has_attachment`, `serial_number`, `created_at`, `updated_at`) VALUES
(2, 'Citibank', 'A pioneer of both the credit card industry and automated teller machines, Citibank – formerly the City Bank of New York.', '<p><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\">A pioneer of both the credit card industry and automated teller machines, </span><a href=\"https://smartasset.com/checking-account/Citibank-banking-review\">Citibank</a><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\"> –\r\n formerly the City Bank of New York – was regarded as an East Coast \r\nequivalent to Wells Fargo during the 19th century.</span><br></p>', 1, 0, 1, '2021-07-16 22:41:59', '2022-01-23 00:11:01'),
(3, 'Bank of America', 'Bank of America has 4,265 branches in the country, only about 700 fewer than Chase. It started as a small institution serving immigrants in San Francisco.', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock,</p>', 1, 1, 2, '2021-07-16 22:43:19', '2023-07-19 09:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `online_gateways`
--

CREATE TABLE `online_gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `information` mediumtext,
  `status` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `online_gateways`
--

INSERT INTO `online_gateways` (`id`, `name`, `keyword`, `information`, `status`) VALUES
(1, 'PayPal', 'paypal', '{\"sandbox_status\":\"1\",\"client_id\":\"AVYKFEw63FtDt9aeYOe9biyifNI56s2Hc2F1Us11hWoY5GMuegipJRQBfWLiIKNbwQ5tmqKSrQTU3zB3\",\"client_secret\":\"EJY0qOKliVg7wKsR3uPN7lngr9rL1N7q4WV0FulT1h4Fw3_e5Itv1mxSdbtSUwAaQoXQFgq-RLlk_sQu\"}', 1),
(2, 'Instamojo', 'instamojo', '{\"sandbox_status\":\"1\",\"key\":\"test_172371aa837ae5cad6047dc3052\",\"token\":\"test_4ac5a785e25fc596b67dbc5c267\"}', 1),
(3, 'Paystack', 'paystack', '{\"key\":\"sk_test_4ac9f2c43514e3cc08ab68f922201549ebda1bfd\"}', 1),
(4, 'Flutterwave', 'flutterwave', '{\"public_key\":\"FLWPUBK_TEST-93972d50b7b24582a2050de2803799c0-X\",\"secret_key\":\"FLWSECK_TEST-3c9d39d4b16e9011bc4b9893f882f71e-X\"}', 1),
(5, 'Razorpay', 'razorpay', '{\"key\":\"rzp_test_fV9dM9URYbqjm7\",\"secret\":\"nickxZ1du2ojPYVVRTDif2Xr\"}', 1),
(6, 'MercadoPago', 'mercadopago', '{\"sandbox_status\":\"1\",\"token\":\"TEST-705032440135962-041006-ad2e021853f22338fe1a4db9f64d1491-421886156\"}', 1),
(7, 'Mollie', 'mollie', '{\"key\":\"test_kKT2J9nRMHH9cN6acf2CTruN3t5CC6\"}', 1),
(8, 'Stripe', 'stripe', '{\"key\":\"pk_test_UnU1Coi1p5qFGwtpjZMRMgJM\",\"secret\":\"sk_test_QQcg3vGsKRPlW6T3dXcNJsor\"}', 1),
(9, 'Paytm', 'paytm', '{\"environment\":\"local\",\"merchant_key\":\"LhNGUUKE9xCQ9xY8\",\"merchant_mid\":\"tkogux49985047638244\",\"merchant_website\":\"WEBSTAGING\",\"industry_type\":\"Retail\"}', 1),
(10, 'Authorize.Net', 'authorize.net', '{\"sandbox_status\":\"1\",\"api_login_id\":\"3Ca5hYQ6h\",\"transaction_key\":\"8bt8Kr5gPZ3ZE23C\",\"public_client_key\":\"7m38JBnNjStNFq58BA6Wrr852ahtT533cGKavWwu6Fge28RDc5wC7wTL8Vsb35B3\"}', 1),
(11, 'Phonepe', 'phonepe', '{\"sandbox_status\":null,\"merchant_id\":null,\"salt_key\":null,\"salt_index\":null}', 0),
(12, 'Xendit', 'xendit', '{\"secret_key\":null}', 0),
(13, 'Perfect Money', 'perfect_money', '{\"perfect_money_wallet_id\":null}', 0),
(14, 'Myfatoorah', 'myfatoorah', '{\"sandbox_status\":null,\"token\":null}', 0),
(15, 'Yoco', 'yoco', '{\"secret_key\":null}', 0),
(16, 'Toyyibpay', 'toyyibpay', '{\"sandbox_status\":null,\"secret_key\":null,\"category_code\":null}', 0),
(17, 'Paytabs', 'paytabs', '{\"profile_id\":null,\"server_key\":null,\"api_endpoint\":null,\"country\":null}', 0),
(18, 'Iyzico', 'iyzico', '{\"api_key\":null,\"secret_key\":null,\"sandbox_status\":null}', 0),
(19, 'Midtrans', 'midtrans', '{\"server_key\":null,\"is_production\":null}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number_of_service_add` int(11) DEFAULT '0',
  `number_of_service_featured` int(11) DEFAULT '0',
  `number_of_form_add` int(11) DEFAULT '0',
  `number_of_service_order` int(11) NOT NULL DEFAULT '0',
  `live_chat_status` int(11) DEFAULT '0',
  `qr_builder_status` int(11) DEFAULT '0',
  `qr_code_save_limit` int(11) DEFAULT '0',
  `custom_features` longtext COLLATE utf8_unicode_ci,
  `is_trial` int(11) DEFAULT NULL,
  `recommended` int(11) DEFAULT '0',
  `trial_days` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `status`, `created_at`, `updated_at`) VALUES
(14, 1, '2021-10-18 02:33:45', '2021-10-18 02:33:45'),
(16, 1, '2023-08-09 08:54:15', '2023-11-30 05:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `page_contents`
--

CREATE TABLE `page_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page_contents`
--

INSERT INTO `page_contents` (`id`, `language_id`, `page_id`, `title`, `slug`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(30, 8, 14, 'Terms & Conditions', 'terms--conditions', '<style>\n.summernote-content *:not(h1,h2,h3,h4,h5,h6){\n  color: var(--text-medium);\n  font-size: var(--font-base);\n  line-height: var(--bs-body-line-height);\n}\n.summernote-content :is(h1,h2,h3,h4,h5,h6) {\n  font-family: var(--font-heading);\n  color: var(--text-dark);\n  font-weight: var(--font-bold);\n  line-height: 1.3;\n}\n.summernote-content li:not(:last-child) {\n  margin-bottom: 10px;\n}\n\n</style>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">Welcome to MultiGig. These terms and conditions outline the rules and regulations for the use of our website.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">1. Acceptance of Terms</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">By accessing and using our website, you agree to be bound by these terms and conditions. If you do not agree to these terms and conditions, you should not use our website.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">2. Intellectual Property</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">All intellectual property rights in the website and the content published on it, including but not limited to copyright and trademarks, are owned by us or our licensors. You may not use any of our intellectual property without our prior written consent.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">3. User Content</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">By submitting any content to our website, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, distribute, and display such content in any media format and through any media channels.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">4. Disclaimer of Warranties</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">Our website and the content published on it are provided on an \"as is\" and \"as available\" basis. We do not make any warranties, express or implied, regarding the website, including but not limited to the accuracy, reliability, or suitability of the content for any particular purpose.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">5. Limitation of Liability</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">We shall not be liable for any damages, including but not limited to direct, indirect, incidental, punitive, and consequential damages, arising from the use or inability to use our website or the content published on it.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">6. Modifications to Terms and Conditions</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">We reserve the right to modify these terms and conditions at any time without prior notice. Your continued use of our website after any such modifications indicates your acceptance of the modified terms and conditions.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">7. Governing Law and Jurisdiction</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">These terms and conditions shall be governed by and construed by the laws of the jurisdiction in which we operate, without giving effect to any principles of conflicts of law. Any legal proceedings arising out of or in connection with these terms and conditions shall be brought solely in the courts located in the jurisdiction in which we operate.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">8. Termination</span></h5>\n<div><span style=\"font-size:12pt;font-family:helvetica, arial, sans-serif;\">We shall not be liable for any damages, including but not limited to direct, indirect, incidental, punitive, and consequential damages, arising from the use or inability to use our website or the content published on it.</span></div>\n<div> </div>\n<h5><span style=\"font-family:helvetica, arial, sans-serif;\">9. Contact Information</span></h5>\n<div><span style=\"font-family:helvetica, arial, sans-serif;\">If you have any questions or comments about these terms and conditions, please contact us at info@multigig.com.</span></div>', 'terms', 'Unless otherwise stated, MultiGig and/or its licensors own the intellectual property rights for all material on MultiGig. All intellectual property rights are reserved. You may access this from MultiGig for your own personal use subjected to restrictions set in these terms and conditions.', '2021-10-18 02:33:45', '2024-01-20 16:48:36'),
(31, 9, 14, 'البنود و الظروف', 'البنود-و-الظروف', '<style>\n.summernote-content *:not(h1,h2,h3,h4,h5,h6){\n  color: var(--text-medium);\n  font-size: var(--font-base);\n  line-height: var(--bs-body-line-height);\n}\n.summernote-content :is(h1,h2,h3,h4,h5,h6) {\n  font-family: var(--font-heading);\n  color: var(--text-dark);\n  font-weight: var(--font-bold);\n  line-height: 1.3;\n}\n.summernote-content li:not(:last-child) {\n  margin-bottom: 10px;\n}\n\n</style>\n<p>مرحبا بكم في جيجو. تحدد هذه الشروط والأحكام القواعد واللوائح الخاصة باستخدام موقعنا.</p>\n<p><br /><strong>1. قبول الشروط</strong></p>\n<p>من خلال الوصول إلى موقعنا واستخدامه ، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على هذه الشروط والأحكام ، فلا يجب عليك استخدام موقعنا.</p>\n<p><br /><strong>2. الملكية الفكرية</strong></p>\n<p>جميع حقوق الملكية الفكرية في الموقع والمحتوى المنشور عليه ، بما في ذلك على سبيل المثال لا الحصر حقوق النشر والعلامات التجارية ، مملوكة لنا أو للمرخصين لدينا. لا يجوز لك استخدام أي من ملكيتنا الفكرية دون موافقة خطية مسبقة منا.</p>\n<p><br /><strong>3. محتوى المستخدم</strong></p>\n<p>من خلال تقديم أي محتوى إلى موقعنا ، فإنك تمنحنا ترخيصًا عالميًا غير حصري وخالي من حقوق الملكية لاستخدام هذا المحتوى وإعادة إنتاجه وتوزيعه وعرضه في أي تنسيقات وسائط وعبر أي قنوات وسائط.</p>\n<p><br /><strong>4. إخلاء المسؤولية عن الضمانات</strong></p>\n<p>يتم توفير موقعنا الإلكتروني والمحتوى المنشور عليه \"كما هو\" و \"كما هو متاح\". نحن لا نقدم أي ضمانات ، صريحة أو ضمنية ، فيما يتعلق بالموقع الإلكتروني ، بما في ذلك على سبيل المثال لا الحصر دقة أو موثوقية أو ملاءمة المحتوى لأي غرض معين.</p>\n<p><br /><strong>5. تحديد المسؤولية</strong></p>\n<p>لن نكون مسؤولين عن أي أضرار ، بما في ذلك على سبيل المثال لا الحصر ، الأضرار المباشرة وغير المباشرة والعرضية والعقابية والتبعية ، الناشئة عن استخدام أو عدم القدرة على استخدام موقعنا أو المحتوى المنشور عليه.</p>\n<p><br /><strong>6. تعديلات على الشروط والأحكام</strong></p>\n<p>نحتفظ بالحق في تعديل هذه الشروط والأحكام في أي وقت دون إشعار مسبق. يشير استمرار استخدامك لموقعنا على الويب بعد أي تعديلات من هذا القبيل إلى موافقتك على الشروط والأحكام المعدلة.</p>\n<p><br /><strong>7. القانون الحاكم والاختصاص القضائي</strong></p>\n<p>تخضع هذه الشروط والأحكام وتفسر وفقًا لقوانين الولاية القضائية التي نعمل فيها ، دون إعمال أي مبادئ لتعارض القوانين. أي إجراءات قانونية ناشئة عن أو فيما يتعلق بهذه الشروط والأحكام يجب أن يتم رفعها فقط في المحاكم الواقعة في الولاية القضائية التي نعمل فيها.</p>\n<p><br /><strong>8. الإنهاء</strong></p>\n<p>يجوز لنا إنهاء أو تعليق وصولك إلى موقعنا على الفور ، دون إشعار مسبق أو مسؤولية ، لأي سبب من الأسباب ، بما في ذلك على سبيل المثال لا الحصر إذا قمت بخرق هذه الشروط والأحكام.</p>\n<p><br /><strong>9. معلومات الاتصال</strong></p>\n<p>إذا كان لديك أي أسئلة أو تعليقات حول هذه الشروط والأحكام ، يرجى الاتصال بنا على info@multigig.com.</p>', 'terms', NULL, '2021-10-18 02:33:45', '2024-01-20 16:37:09'),
(34, 8, 16, 'Privacy Policy', 'privacy-policy', '<style>\n.summernote-content *:not(h1,h2,h3,h4,h5,h6){\n  color: var(--text-medium);\n  font-size: var(--font-base);\n  line-height: var(--bs-body-line-height);\n}\n.summernote-content :is(h1,h2,h3,h4,h5,h6) {\n  font-family: var(--font-heading);\n  color: var(--text-dark);\n  font-weight: var(--font-bold);\n  line-height: 1.3;\n}\n.summernote-content li:not(:last-child) {\n  margin-bottom: 10px;\n}\n\n</style>\n<h3>1. Information Collection</h3>\n<p>This Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your information when You use the Service and tells You about Your privacy rights and how the law protects You.</p>\n<p>We use Your Personal data to provide and improve the Service. By using the Service, You agree to the collection and use of information in accordance with this Privacy Policy. </p>\n<p> </p>\n<h3>2. Personal Data</h3>\n<p>While using Our Service, We may ask You to provide Us with certain personally identifiable information that can be used to contact or identify You. Personally identifiable information may include, but is not limited to:</p>\n<ul>\n<li>\n<p>Email address</p>\n</li>\n<li>\n<p>First name and last name</p>\n</li>\n<li>\n<p>Phone number</p>\n</li>\n<li>\n<p>Address, State, Province, ZIP/Postal code, City</p>\n</li>\n<li>\n<p>Usage Data</p>\n</li>\n</ul>\n<p> </p>\n<h3>3. Usage Data</h3>\n<p>Usage Data is collected automatically when using the Service.</p>\n<p>Usage Data may include information such as Your Device\'s Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that You visit, the time and date of Your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>\n<p>When You access the Service by or through a mobile device, We may collect certain information automatically, including, but not limited to, the type of mobile device You use, Your mobile device unique ID, the IP address of Your mobile device, Your mobile operating system, the type of mobile Internet browser You use, unique device identifiers and other diagnostic data.</p>\n<p>We may also collect information that Your browser sends whenever You visit our Service or when You access the Service by or through a mobile device.</p>\n<p> </p>\n<h3>4. Retention of Your Data</h3>\n<p>The Company will retain Your Personal Data only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use Your Personal Data to the extent necessary to comply with our legal obligations (for example, if we are required to retain your data to comply with applicable laws), resolve disputes, and enforce our legal agreements and policies.</p>\n<p>The Company will also retain Usage Data for internal analysis purposes. Usage Data is generally retained for a shorter period of time, except when this data is used to strengthen the security or to improve the functionality of Our Service, or We are legally obligated to retain this data for longer time periods.</p>\n<p> </p>\n<h3>6. Transfer of Your Data</h3>\n<p>Your information, including Personal Data, is processed at the Company\'s operating offices and in any other places where the parties involved in the processing are located. It means that this information may be transferred to — and maintained on — computers located outside of Your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from Your jurisdiction.</p>\n<p>Your consent to this Privacy Policy followed by Your submission of such information represents Your agreement to that transfer.</p>\n<p>The Company will take all steps reasonably necessary to ensure that Your data is treated securely and in accordance with this Privacy Policy and no transfer of Your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of Your data and other personal information.</p>\n<p> </p>\n<h3>7. Delete Your Personal Data</h3>\n<p>You have the right to delete or request that We assist in deleting the Personal Data that We have collected about You.</p>\n<p>Our Service may give You the ability to delete certain information about You from within the Service.</p>\n<p>You may update, amend, or delete Your information at any time by signing in to Your Account, if you have one, and visiting the account settings section that allows you to manage Your personal information. You may also contact Us to request access to, correct, or delete any personal information that You have provided to Us.</p>\n<p>Please note, however, that We may need to retain certain information when we have a legal obligation or lawful basis to do so.</p>\n<p> </p>\n<h3>8. Business Transactions</h3>\n<p>If the Company is involved in a merger, acquisition or asset sale, Your Personal Data may be transferred. We will provide notice before Your Personal Data is transferred and becomes subject to a different Privacy Policy.</p>\n<p> </p>\n<h3>9. Security of Your Personal Data</h3>\n<p>The security of Your Personal Data is important to Us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While We strive to use commercially acceptable means to protect Your Personal Data, We cannot guarantee its absolute security.</p>\n<p>Children\'s Privacy</p>\n<p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If You are a parent or guardian and You are aware that Your child has provided Us with Personal Data, please contact Us. If We become aware that We have collected Personal Data from anyone under the age of 13 without verification of parental consent, We take steps to remove that information from Our servers.</p>', NULL, NULL, '2023-08-09 08:54:15', '2024-01-17 15:16:20'),
(35, 9, 16, 'سياسة الخصوصية', 'سياسة-الخصوصية', '<style>\n.summernote-content *:not(h1,h2,h3,h4,h5,h6){\n  color: var(--text-medium);\n  font-size: var(--font-base);\n  line-height: var(--bs-body-line-height);\n}\n.summernote-content :is(h1,h2,h3,h4,h5,h6) {\n  font-family: var(--font-heading);\n  color: var(--text-dark);\n  font-weight: var(--font-bold);\n  line-height: 1.3;\n}\n.summernote-content li:not(:last-child) {\n  margin-bottom: 10px;\n}\n\n</style>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">مرحبا بكم في إيفينتو. تحدد هذه الشروط والأحكام القواعد واللوائح الخاصة باستخدام موقعنا.</span></p>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"><strong><span style=\"font-size:12pt;\">قبول الشروط</span></strong></h5>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">من خلال الوصول إلى موقعنا واستخدامه ، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على هذه الشروط والأحكام ، يجب عليك عدم استخدام موقعنا.</span></p>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\"><strong>الملكية الفكرية</strong></span></h5>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">جميع حقوق الملكية الفكرية في الموقع والمحتوى المنشور عليه ، بما في ذلك على سبيل المثال لا الحصر حقوق الطبع والنشر والعلامات التجارية ، مملوكة لنا أو للمرخصين لدينا. لا يجوز لك استخدام أي من int لدينا</span></p>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">محتوى المستخدم</span></h5>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">من خلال تقديم أي محتوى إلى موقعنا ، فإنك تمنحنا ترخيصا عالميا وغير حصري وخالي من حقوق الملكية لاستخدام هذا المحتوى وإعادة إنتاجه وتوزيعه وعرضه بأي تنسيق وسائط ومن خلال أي وسيلة إعلامية.</span></p>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">إخلاء المسؤولية عن الضمانات</span></h5>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">يتم توفير موقعنا الإلكتروني والمحتوى المنشور عليه على أساس \"كما هو\" و \"كما هو متاح\". نحن لا نقدم أي ضمانات ، صريحة أو ضمنية ، فيما يتعلق بالموقع ، بما في ذلك على سبيل المثال لا الحصر</span></p>\n<h5 style=\"text-align:right;line-height:1.5;\"> </h5>\n<h5 style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">تحديد المسؤولية</span></h5>\n<p> </p>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">لن نكون مسؤولين عن أي أضرار ، بما في ذلك على سبيل المثال لا الحصر الأضرار المباشرة وغير المباشرة والعرضية والعقابية والتبعية ، الناشئة عن استخدام أو عدم القدرة على استخدام موقعنا أو المقاولات.</span></p>\n<p style=\"text-align:right;line-height:1.5;\"> </p>\n<h5 style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\"><strong>التعديلات على الشروط والأحكام</strong></span></h5>\n<p> </p>\n<p> </p>\n<p style=\"text-align:right;line-height:1.5;\"><span style=\"font-size:12pt;\">نحتفظ بالحق في تعديل هذه الشروط والأحكام في أي وقت دون إشعار مسبق. إن استمرارك في استخدام موقعنا الإلكتروني بعد أي تعديلات من هذا القبيل يشير إلى موافقتك على التعديل الثالث.</span></p>', NULL, NULL, '2023-08-09 08:54:15', '2024-01-17 14:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `page_headings`
--

CREATE TABLE `page_headings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `blog_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `post_details_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `error_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `faq_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `forget_password_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `login_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `signup_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `services_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `service_details_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `about_us_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_login_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_signup_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_forget_password_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pricing_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page_headings`
--

INSERT INTO `page_headings` (`id`, `language_id`, `blog_page_title`, `post_details_page_title`, `contact_page_title`, `error_page_title`, `faq_page_title`, `forget_password_page_title`, `login_page_title`, `signup_page_title`, `services_page_title`, `service_details_page_title`, `created_at`, `updated_at`, `about_us_page_title`, `seller_page_title`, `seller_login_page_title`, `seller_signup_page_title`, `seller_forget_password_page_title`, `pricing_page_title`) VALUES
(4, 9, 'مقالات', 'تفاصيل المنشور', 'اتصال', '404', 'التعليمات', 'نسيت كلمة المرور', 'تسجيل الدخول', 'اشتراك', 'خدمات', 'تفاصيل الخدمة', '2021-10-14 02:42:42', '2023-07-12 06:36:36', 'معلومات عنا', NULL, NULL, NULL, NULL, NULL),
(8, 8, 'Blog', 'Post Details', 'Contact', '404', 'FAQ', 'Forget Password', 'Login', 'Signup', 'Services', 'Service Details', '2022-01-10 05:21:48', '2023-12-19 09:40:29', 'About', 'Sellers', 'Login', 'Signup', 'Forget Password', 'Pricing');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` smallint(5) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `image`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(21, '64b37476320ef.png', 'https://www.example.com/', 1, '2023-07-16 04:39:18', '2023-07-16 04:39:18'),
(22, '64b374c63640b.png', 'https://www.example.com', 2, '2023-07-16 04:40:38', '2023-07-16 04:40:38'),
(23, '64b374dba7bd4.png', 'https://example.com', 3, '2023-07-16 04:40:59', '2023-07-16 04:40:59'),
(24, '64b374edc9386.png', 'https://example.com', 4, '2023-07-16 04:41:17', '2023-07-16 04:41:17'),
(25, '64b376a0292d4.png', 'https://www.example.com', 5, '2023-07-16 04:48:32', '2023-07-16 04:48:32'),
(26, '64b376be932e4.png', 'https://www.example.com', 5, '2023-07-16 04:49:02', '2023-07-16 04:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'fahadahmadshemul@gmail.com', '653e3d22a6dd3', NULL),
(6, 'lujisejudy@mailinator.com', '65598b48c1a56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `popups`
--

CREATE TABLE `popups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `type` smallint(5) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `background_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_color_opacity` decimal(3,2) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `button_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `delay` int(10) UNSIGNED NOT NULL COMMENT 'value will be in milliseconds',
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 => deactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `popups`
--

INSERT INTO `popups` (`id`, `language_id`, `type`, `image`, `name`, `background_color`, `background_color_opacity`, `title`, `text`, `button_text`, `button_color`, `button_url`, `end_date`, `end_time`, `delay`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(7, 8, 1, '1628593512.jpg', 'Black Friday', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500, 1, 0, '2021-08-10 05:05:12', '2024-01-06 09:50:59'),
(8, 8, 2, '1628593631.jpg', 'Month End Sale', '451D53', '0.80', 'ENJOY 10% OFF', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Shop Now', '451D53', 'http://example.com/', NULL, NULL, 2000, 2, 0, '2021-08-10 05:07:11', '2024-01-06 09:50:57'),
(10, 8, 3, '1628682131.jpg', 'Summer Sale', 'DC143C', '0.70', 'Newsletter', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Subscribe', 'DC143C', NULL, NULL, NULL, 2000, 3, 0, '2021-08-11 05:42:11', '2024-01-06 19:05:23'),
(11, 8, 4, '1628685488.jpg', 'Winter Offer', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Shop Now', 'FF2865', 'http://example.com/', NULL, NULL, 1500, 4, 0, '2021-08-11 06:38:08', '2024-01-06 09:50:52'),
(12, 8, 5, '1628685866.jpg', 'Winter Sale', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Subscribe', 'F8960D', NULL, NULL, NULL, 2000, 5, 0, '2021-08-11 06:44:26', '2024-01-06 19:05:18'),
(13, 8, 6, '1628686132.jpg', 'New Arrivals Sale', NULL, NULL, 'Hurry, Sales Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', '29A19C', 'http://example.com/', '2026-07-22', '10:00:00', 2000, 6, 0, '2021-08-11 06:48:52', '2024-01-06 09:50:48'),
(14, 8, 7, '1628687716.jpg', 'Flash Sale', '930077', NULL, 'Hurry, Sale Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', 'FA00CA', 'http://example.com/', '2025-11-27', '12:00:00', 1500, 7, 0, '2021-08-11 07:15:16', '2024-01-06 19:13:05'),
(19, 9, 1, '61a6f917913f2.jpg', 'الجمعة السوداء', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500, 1, 0, '2021-11-30 22:24:55', '2022-05-24 23:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_informations`
--

CREATE TABLE `post_informations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `blog_category_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscribable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscribable_id` bigint(20) UNSIGNED NOT NULL,
  `endpoint` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_encoding` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `push_subscriptions`
--

INSERT INTO `push_subscriptions` (`id`, `subscribable_type`, `subscribable_id`, `endpoint`, `public_key`, `auth_token`, `content_encoding`, `created_at`, `updated_at`) VALUES
(172, 'App\\Models\\Guest', 174, 'https://wns2-sg2p.notify.windows.com/w/?token=BQYAAAA8R0nGgJCGXd7DAA%2fhqxPOqu2oZ2moWTmOEMeGmfFi1qaDXFGMbSdOhlJCDDHYV3e421pyy7ekto339AJC%2bmi05fj1SeSkE6WVj8l7sHnveqgatZWcx%2fQSpu5sJn%2feloVJ1Abvdb0OXoiWn6aLerArZ70yF4%2bwbAOkX1af5cOLDI6hoj5Cutw9qTx0tDEFIBjpX8YxadNhkTbMl%2bLBV%2fLbDuk06X6p7C8mXAif0Tw8A4ecVj8mSWIbyLr5NGr765xlB9i%2fEX8Sxr96%2bHtB4gHQ0geVdAX%2flTVH%2fu83vwUFeWgqu1mSbvA8wdr4PtAlz0Q%3d', 'BHdq97l2kzuGbHU2BKTNEHkc9XcB8n76_WNhmkxeplcElUQxS8YPJBzqJxUCOOxHMCtD_nPgDmB87703UsG8ldE', 'I0l8OzNL-r01rLMVG1A4Ug', NULL, '2022-07-05 06:31:47', '2022-07-05 06:31:47'),
(173, 'App\\Models\\Guest', 175, 'https://wns2-sg2p.notify.windows.com/w/?token=BQYAAACzIpanzXEw2hAJSGLiM%2fmAZajRmgC6MI7EXYAGpC3mac80GILac8RfVV6xs1%2bo%2fKG6Thlx8k%2f442aEM49R%2f%2bONH2txn%2fqtmrZ1fyK7OjxNxPaUW43jZPiOkcOnCxzuuPvnjsSwfMfmz8BbFjyyz9EdkmltuYwfJKQag4kgGKAR0lzlGreisBfKrHv30c0gfUV5ZexnCr8PCa8urNQw%2fPg1jusAewA6xuR9ojTCFbKJOXM7umjHjxDOBFDAq4rL8X2U36E4fsDSp1JcrCCcT7vRXLY9b4ych1mS1DKkjh2exhOLoND%2fCCcRp9GlOLpobJ8%3d', 'BIQzBks3IPQFXbnwZ38cnaAh0uG5eiQcWR8LU_7G-22cAbkbKvN9tsVMCvhc0Jy4vP3_ZGhXAJhW5oJkP7iavAA', 'knPXtng2xnfP9d1-rOowag', NULL, '2022-07-07 07:35:06', '2022-07-07 07:35:06'),
(174, 'App\\Models\\Guest', 176, 'https://wns2-pn1p.notify.windows.com/w/?token=BQYAAAAcGGckHn7hZCGNRIiT0fMSZrtAzuhKF5tQlTtBU6K2WOoMiQB27zYlOJ6y%2bqq8fiUD0us72l75e2wIntUiMWm%2fPwqogz9IHuh6269Vy8NYFoMkGA4aatkverogPd34Cg1qOvbHDUh%2fvVTWfRVihLY5HMNEYueieOaWaPF4jDs6Zq79kW5f8M0y0H62MwhtmxZdtPPQPwe3iSTUPvu%2flTCj3Smrc%2brmPeeCWXB3Ld9F1Y9kYcpVN7KuiaPXcTOPlRwBovL9xvaVF%2bOuPESYxvRUotAoYBLBhULtCtU%2bkAd09abzxgXOGaQZhmUwSR6pG7U%3d', 'BDr0_rbibpY2kgw69dmIQXsMcMsKWYHwXbwxAnOGUeA3JOY2ZRbyS-xRQs93f22mUWCyKVRYgp0W0vNYHOGGWSQ', 'VRYxVXvhBAQ-qCeImw6V_g', NULL, '2022-07-18 04:15:17', '2022-07-18 04:15:17'),
(177, 'App\\Models\\Guest', 179, 'https://wns2-pn1p.notify.windows.com/w/?token=BQYAAAD8nzoMfxtm%2fkcGTuDwXDyhYkQbUEmX8uk%2f%2bhubBXHfutHEMWkndbPPwEvW47C2qC6bZc7pjjEltZKFlh05Itu06NukXjahc9%2fUrLmWXXkizCXv55u7pQhReeARKuB19lMLwN2Bmh5JAQsich2N9QNolRt72EsGh3ocVrpntyrpMQNBhj0pQ59NYwfftssk9Hndle5ezLBCG7v9NSEwkyhtk9VNk3ibKlFgiWhHOiW8xU2f%2fEg%2baTSp6gmMLsJg8kYBJI6ZfoaVsNeYWC2eiK%2boRu7l4hgEhWpiKJ%2fEbu%2bqBOEy1rmvXshg1Qq6TfL1EjPqYG2ryGVHu3iW8hK3SCk0', 'BBmCsvohcXVVzReIoLt3fbEC4JVQmwXwyHRu1kX5HpB9PXbrSPcDJ8FYYsFVbFMEox0Pj0gadcvLiAAygwvUAXg', 'kAkDBG0hJHlyrdionW1MOQ', NULL, '2023-11-19 10:26:54', '2023-11-19 10:26:54'),
(178, 'App\\Models\\Guest', 180, 'https://fcm.googleapis.com/fcm/send/cJDDMRm5qjs:APA91bFI2UfaZjSeNOpOs0LjX1MskcuG5-IS2dnv2iANPHuLvhjA8jkut2Ffebfy-MYldcXEQdCdsV6JgFgbaN7hmQjkfr9zT6oY5GubQ6SnW3eW1vrAS-x5axZP89CVNi73A7bsXsDR', 'BHrhxmLomN2d2tJEA_7dy_qJuI_nUf_vIPiStbmK1Sl2m6wxTwK-wQ_WAZy8NdujkUIDIoKjR_AbuftADuOMj5c', 'J2QBhmxt2mUjMy-uqB3MNw', NULL, '2023-11-20 04:47:22', '2023-11-20 04:47:22'),
(179, 'App\\Models\\Guest', 181, 'https://fcm.googleapis.com/fcm/send/eYKUGIt-Tfg:APA91bG3NG-69is0cr1yDLjtOlSg-uUsY9npolEF7ZOlCTByi13PfnxWG04_X9idy5ji77lr7O06vZfnCWH_FkCoVFCfhKMNB2If6F-fDAt4IST2ZBfDhc4tyBuH5rJP-8m7QWtO8Ksv', 'BI_I3D4JAyEOaZ98nQkOwAzdi7z-dUhB2KNpR7klPd3ZcLphExA9yO_XSwFxX1PD6LHwC6CVif60ITE73l0vXJA', '4lUzu3M8ZAIEf5R5AyBSKg', NULL, '2024-01-03 06:58:56', '2024-01-03 06:58:56'),
(180, 'App\\Models\\Guest', 182, 'https://fcm.googleapis.com/fcm/send/ero0ALpIueY:APA91bGzz6MMarAinnSKFU0tS4Hydw31vht-NR1WAuENCuwjohiFkc_tSiAqf_g6kOwxZXlx2ds0IVAByr2z7rRAryJBC4onHBQ7lttFs7wFIkSSibvVemdZW8XxqRNeH8PNd2bmIG4c', 'BH7ssWS_mHUUASYJ3trtMTCcjTqB4HeluTwYjghlGQLBbYYmjhEurwLZTyYQ61GXkDkRZ4WlyZlxDBQI44NzxL4', 'ATqPNqI6GgCA1zTT279gsA', NULL, '2024-01-04 06:52:21', '2024-01-04 06:52:21'),
(181, 'App\\Models\\Guest', 183, 'https://fcm.googleapis.com/fcm/send/d1P6rXjCqXY:APA91bEnKlRHtPHebLsJNkX2eJV4omEI3CwyeFqfcVC-2UZtK9Ej1zs7GTXIzAojV6ds5N9W5G8dOAnqGgZllKBOYDgHqNw9zRSvi1_03NRveTd_6x-FcG8qGVqmVqX6uyS1L_Mlo--M', 'BLxZq5py8AnWJbklI4mJ1SY8UdVCNl25bxGejXHWn1ZwQh_5Cz4I4NSEYdciVCgPPpauMQZEXEPbyiJ75G4AgEI', 'IwRVOxSK_jHYnSBsnAsSew', NULL, '2024-01-04 06:57:44', '2024-01-04 06:57:44'),
(183, 'App\\Models\\Guest', 1, 'https://fcm.googleapis.com/fcm/send/cBv2YWjxIb0:APA91bEnO9dp_BY-wkQg3FuomdLV7stszxMmVHUWoNrXlGsrSJwBVT-vQ1sAKnHfAIl_kQEt86vFP-SY5sHDq4H-9Y0wDI-7bEptsk57weWnpEjU-tlnUnLOdznPoQ4C5ulqPBTOxeX8', 'BFlcPx382zn9-d9p-DItFWViOjLNrs9h7e1g2y3bLDwWk3MUYOR6XVcsyG3CICjbifs2WjPK9ZO9EUJ0EBAP9fg', 'BD0g3NrynucI-vFQ6LDiVQ', NULL, '2024-01-06 07:02:24', '2024-01-06 07:02:24'),
(184, 'App\\Models\\Guest', 2, 'https://fcm.googleapis.com/fcm/send/cJYCIIvP0Wo:APA91bFXVa0d25_R1HfpOAe8U4GsqlyC_j7TSTqlK3jUAJtDiXra6r7vWGb06Pfluj00xhwcCmyjpcCJ7oMXfbGCt7DtSqe_EjdyKIl0QXwh5_0eduKHiYlyJlQ-jXEmK1dsA2CFQXwO', 'BGXa2suQ3faIaHUtWA0dWp5b7GBPz4ZM0GuCc8Y_pUULvAx9P7f5KWcD2VJc4tKmEdAihkcqv-JBIwjJAv_Xo_A', 'JkgK9fb-FSBuqpI_yg8VjQ', NULL, '2024-01-06 09:37:38', '2024-01-06 09:37:38'),
(185, 'App\\Models\\Guest', 3, 'https://fcm.googleapis.com/fcm/send/fw0Hqhfg7xg:APA91bGoNeRWYfXCOOaRM6BaR7nbtCVA-yGiy7QeXL40MClofLVs3Db_La0Jqz_mz0PzXteiTps609WT70siMW_XVDhjsc6vPIwixmIIJvY6JhY3ZxQ_d0ElERj3sDc1C7MmBKToejTD', 'BJmKhYMEhDBC6F6z_mUcB08ehFI6u21C9Vh3qv66fc6SB0y4BNOYseQr4JaANrno8cxvs_Pj0btk6I01bb-RrfM', 'gUju4yNP2YSiDfMoZnBhBA', NULL, '2024-01-06 09:44:12', '2024-01-06 09:44:12'),
(186, 'App\\Models\\Guest', 4, 'https://fcm.googleapis.com/fcm/send/e3b6YptzCsc:APA91bEDM56563r9KEdy_tdmXdhmGf1sUcxicgeAAWjnE0QkdIlq5hOor4Cds7wQXK1LVkGRPEhwA_DwDKFTgiJYrBPqhW0L-jH6H4cq7xXuIOnVxTCzPd-9Z31Rh4AoRhgMa_paYj2y', 'BP1R_UpEM5GOcvMJgfliKndpMYyQgwELD1C9vf8l4gbDqDBVF6CSoopM7yaQtmy1WmwngSU1yZjT9dgM27p335g', '6EMXBGsdNaFxB7cJlgfeKg', NULL, '2024-01-06 12:01:09', '2024-01-06 12:01:09'),
(187, 'App\\Models\\Guest', 5, 'https://fcm.googleapis.com/fcm/send/dDrn0VaU5QU:APA91bEK5hX2UxHSAGmf0ffcLluzzDlxhD5AcexC79ZnljEcqGCP0ZGuTPeFJeU_7VD01o2_PpFDL_hvszWBivgOQ3S535URPRjFrkZGZNEzPAmR3GursQP--JC17eRiqdm2-a3JP9mn', 'BLKeXswHRhQxD6pWHetw0NDJG3wFuDUeQr7pxlXh-Nn6Xnd9C2BR2z4bRmCMNMzcTTnz07Z2mswYW96WwMRgijg', 'H-TeOU3DQfjxHfpOr0W3Mg', NULL, '2024-01-06 18:17:13', '2024-01-06 18:17:13'),
(188, 'App\\Models\\Guest', 6, 'https://fcm.googleapis.com/fcm/send/d_WkHjT_MgI:APA91bFVpzZ5MDgqSRS2nucv5SV6t3O_E-ipIYzDQExgg72ZCh26-r2vo8Qhml1HOKTWnrzfxq9o1pwCt7YPA_bh18nZzQ5pSx6QOOiQp2KwqQ9pC_TvR0UdfnL1tHEbl26MN2LJ44Y6', 'BHnraUj3JXuKsD9gh9HvvxQ9W5ZxtdnGtCGadIbL4ubxNtjFYWm6Qd2RfT4u7TXgcdm9XW_LtW5yXuKZ-PUqfv0', 'oVshRe6E9oxSwqlk3SHQqQ', NULL, '2024-01-06 18:24:25', '2024-01-06 18:24:25'),
(189, 'App\\Models\\Guest', 7, 'https://fcm.googleapis.com/fcm/send/dY2sg8kpreg:APA91bGu5KTMbVqdm_FgStnxHmuiqkMYfGW2G3eGVd7sqMWxaxDTqu5zDBaL2vI6T7_oNgHqGGzED_eRiIOWtwaU6kcrE3jJFOJaEeRMrxutC0vEqiYk8KSaoxHV0ssL8hW312ljpmSw', 'BLd7g5S99P-xQW0REFggeJTdgLge9oOUShs6UDbmUR-uX8_QOogUcAvRIxaaGUPZFuZG3GO0c7SCJT_x7g_qHVI', 'u2TstBYMgtto1r2XsBXYRA', NULL, '2024-01-07 20:17:12', '2024-01-07 20:17:12'),
(190, 'App\\Models\\Guest', 8, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABlmsJNy4uCipVXO1tqsrP7KpZ0xvzcP8PYQdHUk-f7wOq8GrE6TrKKvgKF0WbMtPwneurNvLDFqGa0aUUSjmC_8KwohjkAVHSwBS6_On4sWLYCJVNbFhquR0xwihGkk5IK-ujSRVmYTIN_-ks3pcSF2FOR_myXi4TAAA4cQCUfArFBcGw', 'BHaYGSEpHCn5UdVXZTWfOY7RqZmsIuLCjmSzXK6VXd3y0g5xwGmbjZp4aT9SjW6EDrbeWO7ShqAKkZ0yHQdK6yc', 'KtlGyNgOVU93U1MDtK6utw', NULL, '2024-01-07 20:25:02', '2024-01-07 20:25:02'),
(191, 'App\\Models\\Guest', 9, 'https://fcm.googleapis.com/fcm/send/fw-cGRrWWbI:APA91bF9zfcQen0By0xA_GkaJK79t19PZbGi6Hi51mgX1Xe1WA_lWAPkyOsHWzYAUgMCgHf3MPopVI7UIhKXVYA9h-8jUKukC40ghU87N3CTNh9SxwUHWN5hQahYVWcvwJmbHoQvbgj3', 'BIFW9OPGH3VNJUXHrnaEmPtXdr19HqXk42at40xma42BvQXlanSHtoM2t_sehxC1IFj2w57frGpRiYwzfp5Sl74', 'xj-CxFvKsMwuoX75g-QQXA', NULL, '2024-01-22 11:14:12', '2024-01-22 11:14:12'),
(192, 'App\\Models\\Guest', 10, 'https://fcm.googleapis.com/fcm/send/cf2Pbk0ekxY:APA91bFizcuL8aioJnhYNICnMYs02AYQe-2LGsREzvI25BVrZMH1-NlfA-27miuW4122lryoRT6AYfRqSy44pNptw2vLbtCidRZM6sQV-yUTHAX_28MGM1sSeE27H42HipNW3FSjUupZ', 'BNKUmrUH-vM_Wim9KLNf3kSllXUC4AcfHJqiNj7x3u_QZkGqyuiUzG0msUXimIGq7w0w9xQP2-ylXpaUvgI-P9U', '7i3YszzFekT5AWuqR3-NDA', NULL, '2024-01-25 22:35:03', '2024-01-25 22:35:03'),
(193, 'App\\Models\\Guest', 11, 'https://fcm.googleapis.com/fcm/send/fY3XNA_v6Tk:APA91bG3-rK2BQyglYfiCsoq7JIN55AXZQ-4G1EtApw2mSGIIVOY3X4aB2lysIuZjZmO-tFD1NcWRqZxg8aRJsYsott9gfIPANN04Vy2LuIKSmDWzG7fn8OOX965h4X8Rve21gH6ELBB', 'BNy9ViE-ttW53y06O0XrTKPKcKe1_BQzyBuGcHzdo0LsnFYEimEAvzK6P-SrKAfKgcfEleEnC9xl2uuuxyDWImQ', 'RUldiGFUTmXb-bh9GeZGbw', NULL, '2024-01-25 22:57:39', '2024-01-25 22:57:39'),
(194, 'App\\Models\\Guest', 12, 'https://fcm.googleapis.com/fcm/send/eSoWoPJu330:APA91bE2L--KgmCBpsDA0G1zNv2m6Y0IXoFwSIPIsKwo5Zgri_9pVGZDNzlQ5f4zi2_jFtYRHLmzvQ6tXpqPDQ4n1vO10NkTj_xorSgQjeRSCF_xneTualDe4uOhODX0oFCl8kVqXE-V', 'BPB6ww8LToqN9iek7a4aq9GX3UUQtmjAu76jLQH_GeC4OTJ2fuvBzvmhomfnhM1Sv9jSvdzRwPvejZTxKWTurck', 'pXb8LgmNSdcK5-gajDrebg', NULL, '2024-01-25 23:34:14', '2024-01-25 23:34:14'),
(195, 'App\\Models\\Guest', 13, 'https://fcm.googleapis.com/fcm/send/fOHDeoHK3nQ:APA91bH5bkUe6TLvdHBcu0N3hdRtZM0S3l9FC75cqJzraP3cemfPYYTy7uNihLu5alagkd-UzITvuFuQ7J2lYrac7E54puI010ytw6GeaaZ1ASthavGcIVQ5lDcmWDIKLpfzIwDmNW5P', 'BMT48vq5rxBgWS531Zwpq5E2R6JcNUynUt-REIu5UM1zNZHojJoIamE0o0IsBNbPRk3G-VrC43r_fbiHNetw5UU', 'YaMle-NE464cnL_RmkXWHg', NULL, '2024-01-25 23:42:09', '2024-01-25 23:42:09'),
(196, 'App\\Models\\Guest', 14, 'https://fcm.googleapis.com/fcm/send/dPgnfKB1AiU:APA91bHclqfRaMvPo17psR_SszFfluCIw__YHaTLtGlfdILgYd09ZIOzeXbsQlOwQ97qvXbcGQi-gs7Va-ChwT-u3ZEGXL-FvBzbchB-8Eqn-mMGV9DfpqybaVByT0o6a5iBCytkjKj1', 'BPhV-iqpm7Snj3BY9ViBvMboRb17hrIXzsLcm0k5ADndWq-ETGI3BG1c9pmmaUYgRgojP3KUtsxDPYf4lbQoWUw', 'q34A__xCcdfz9WlvjZaQ2w', NULL, '2024-01-25 23:44:14', '2024-01-25 23:44:14'),
(197, 'App\\Models\\Guest', 15, 'https://fcm.googleapis.com/fcm/send/elXfq9uSmKc:APA91bFP0ECX2EVWibMVWapjHKez4vc76vHRWIcWwe7CCtaX2SFpw0I2N1OhyacA5DjWeZeGmqHrDxiMTEpzuodypwXTFqLRerw-J8VaJXXak4txbtlR9zCOVMe62kI0wAkmTTUf_0aw', 'BBDd_hEOJOK9fW7zC84tgiH7LADwFI-zl5Is0iJdz0acJxZX501hVh_9HYAM70HLST8IUSa4aWk5mYcwLN8br3w', 'BAaEGppY0BUWXAfKJM5a3w', NULL, '2024-01-25 23:44:15', '2024-01-25 23:44:15'),
(198, 'App\\Models\\Guest', 16, 'https://fcm.googleapis.com/fcm/send/fxfrqc0mDks:APA91bHkp_DHGeXza6sHkQq7FktpUEW7tl69ZJytEdQ5GDKuxzu8e55v_JBJdchHnbOuRAa9Mq6zKoQVjeVBHYhrSgcqf4_SOeMmS2iThJ3sfyy1riZAgZAbDDOw-I0QZj3E1foi5J9c', 'BEC_pmTFV0hYvPZpkeG8d6BNz71UP4RUnrZ-enAbCsBvAa0ER7fPI9ty1oTRCutRi67z4bnDplDjJlOO9hxgg74', 'TjijgconNaFoQ4iuo1t5FQ', NULL, '2024-01-25 23:50:07', '2024-01-25 23:50:07'),
(199, 'App\\Models\\Guest', 17, 'https://fcm.googleapis.com/fcm/send/e_5vHIEyM6o:APA91bFMdzcp1iHTyw2EpQfJ94t5rHyx020Fui9hFQyMmp8TL6KAlsLV9MBoYNQJxk7JbOJe800ODWlQINvc2T9xT2mS3gIkyDsXPtDVGmop65WlOa7JlzhN1XufK2jfK2cbWo78jjDw', 'BObg1pBDJSyWiOF5utwEV1grjt6xs5Ku4IgQtry_YoJtAdBkBuqjgQkF70UdHI2dJM5LCQZ7AUH4Bmjxm7tdJN8', 'Reb8mqPswU-rxe0lWU-0bQ', NULL, '2024-01-26 00:10:27', '2024-01-26 00:10:27'),
(200, 'App\\Models\\Guest', 18, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABlsrsylEcHEcMPTsuxOSaADPpBF69iEbYk5Ev7MTPSaHfgCHcTS9XPjpda7jZFDaUR4paPQ9U0b-orXAot-Vu4A8O3AaKmnzicQzh3VsRuLLtnUulUV0dm2PLO2KIy3XSPMd2czmPDVlBOgcYfnip9VymiO9AVY6qk7dCC8syWm9bIXpk', 'BFCXIsyK2-ycspynYG4TDw1WwAVCQqm4Q5N1fm-dLeUCFKfF-nKF7kMdOKkjuFHbmbwOBn-rLdPKm2sCDp9c7z0', 'vnAKjFf3NunbdemmUpIIjg', NULL, '2024-01-26 00:49:07', '2024-01-26 00:49:07'),
(201, 'App\\Models\\Guest', 19, 'https://fcm.googleapis.com/fcm/send/evn3OHN65t4:APA91bHx5llOsStdanXu_kM3C-ohV0WO_fskgnyKtcO726w7OkyB_eTddPKLvrtvwEw_bWqwYLwpPkOv1eV6v8F-wTKjR1XIy2oLvMwCHvrWt-WcuW6-tYc8k9-5tKI7Wy5fVA-reifj', 'BHdNxEUw_r2Ooug3vvb8a_wIjD9EoItQ-uvXjCD2lPONl35QgIkIxD7LTG68ogqwr9rGU8wNULdaTnuy9aWWHxA', 'ReTL7Xu3kzf3G0dWMxwnNQ', NULL, '2024-01-26 02:03:31', '2024-01-26 02:03:31'),
(202, 'App\\Models\\Guest', 20, 'https://fcm.googleapis.com/fcm/send/fTzgkqYF4t8:APA91bGvaqMmBI1o89JBIPB60C2-Ik1PdEdoX4ZQn2Dp7m09d_7wQ1tL2ZeugrZQbPY9sBd-naUzYKlAMcPD2zpUDmdFCnoaePAenVyLjHxm9Dcf6GkATvDqZjlYxPA_0DzRW9n-8iX-', 'BKQrMU0D4zFYv3k7_V_Oh5iuvPh7v33QX4SRL_7vNIxoHBDJWW_rnmr3abghtV0GjtGmfKCRb-AvAca8366q7rE', '8-b3Urmk-KUaqFVYpbNj1w', NULL, '2024-01-26 02:37:18', '2024-01-26 02:37:18'),
(203, 'App\\Models\\Guest', 21, 'https://fcm.googleapis.com/fcm/send/ey4Fp9UkvjE:APA91bHeutWYJvR343pF9nTdf9hzoUqB2lEnAmzw6TvOVfEyDUIe-i_u9Q9T9LaYLAWbnuIIdt3b-ACIHydK5nB77LV_9U6JAUHXbOwsJeF8FPAo6r6Ya7LS0glUwi_7LOB7DPmz5qsV', 'BF1ww1TMO1oP2UMUSaold1ci-4lnHKp2kfsL1MUhomK5ZWmEcmffgwrvK-qHmA9qAbezoz9vMpEDbDONefY5iII', 'Kp_ZSn_SSBVVkZfbCd3Ctw', NULL, '2024-01-26 04:31:11', '2024-01-26 04:31:11'),
(204, 'App\\Models\\Guest', 22, 'https://fcm.googleapis.com/fcm/send/dLrXMBDNT1Q:APA91bEZLW8q2IyjIfK5Y7QMlMIRCxc-cRzvVZupGLIXlquBDpSU_AuyOnWS11xvZZBbIJBFZH6P7-XjYhXVQPhvgzQmq_nAjsfZzNKipS-tuvQq74ZZIRDHI42g2cQzPHgTim4upe5F', 'BMAtuaXpfGRUBIYKtbCbaPBS0qQVSEmQVXAfpQ1iZdHCWzgrrHh9RyTJWPakX6sXdkFzy6zZGovYaha4kYRTotw', 'KNcpS4n0ZuXwFrOjsZzfag', NULL, '2024-01-26 04:34:27', '2024-01-26 04:34:27'),
(205, 'App\\Models\\Guest', 23, 'https://fcm.googleapis.com/fcm/send/dhI-Bsb9zZ4:APA91bGltEjWBeOppoqG_0IQgaR_uEwUksJ2z2_yAguTuOuXUlKn6jjKOpjTCP7IwacjqbTlYbetUSgXy2uny4KPYZKR30hTnk-9jnk7nviQsMsYgt33DVI4PZ0FSEsVitClkpCPajR-', 'BBJa5WBglm1QCML3y83RQmPToMEQ1xzllhn3UwyzUarTJnJblMSw3nf_v4MPXt5svdUZKz_HQcD9xc-2WjXpmPI', 'raOKYh00zsP-0pd0NhVsbg', NULL, '2024-01-26 06:43:56', '2024-01-26 06:43:56'),
(206, 'App\\Models\\Guest', 24, 'https://fcm.googleapis.com/fcm/send/cADi8-Ed12M:APA91bHGo6_Aj8pfEgVWLO-BvXmwmNnUfa25zeJ89gjaXZjyFEi1eyVmnXFYOs59lG9VlpdPCSXwCYMMNa3wnhpM1mmJ3_XlIkGoEnwmxYGVJa1PaFtbe7_JWNTT7qR1-ocNsDdGcXYM', 'BDxhZ5aWAdB-cWq4I-1ClIg3cnTLLYyXUrkgQ2giuEtqOM96sdjmPafEeMtevO865B80nQuujqzYofl-J8AyGbQ', 'tQFi1po0uoTmlPQhx5pESQ', NULL, '2024-01-26 08:40:08', '2024-01-26 08:40:08'),
(207, 'App\\Models\\Guest', 25, 'https://fcm.googleapis.com/fcm/send/fG8r5_xT3z4:APA91bFEp9-7xy-Lr0_VbSdy_VFtRp69JwmCDTbm02TBpzc4ShpPPwieuJHQ5Lfm-Dsnqv25mvcmjOnfqsagPBAmvO6mA_8dDzBMA77jQHCm47du94C7N8GmUtx1_ULoARhl8zBhsQ1m', 'BAMFPwxPN-Nl0_0Stl39uqipROm-XlfuSYisoxO11WpYgQVJobSsOTTnruVsNXI-vAXWZPEkT2LWFv_IHngAaK0', 'Aocf40tujYETFfZ3Tjdvlg', NULL, '2024-01-26 08:48:50', '2024-01-26 08:48:50'),
(208, 'App\\Models\\Guest', 26, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABls0fan6wubz8VmZ2sA5_2OYrULseMi3fzJv23f10eYF0v1QUykOnmaAdMsbOwEj8nhprUzV2lmKEdnc6yCPeCOcr2VL1s8W_nhmZHYO3u9j5ZLZv3EIXgaN_kG18d_K55hZknJt7tDtrgWgxGQDWAUHwZJuQNYB-RQvj8pJBTQrURIxA', 'BH0wjCqBk5fMQEz-Lp8f4F-5ublnDsa9_or6O0E-YSCu7F_lxROtiPRwjQ96Erxu0H0SXjdLP-teyxSTUuBed5s', '5OBlDj4jCjAw79WrusIphA', NULL, '2024-01-26 10:49:15', '2024-01-26 10:49:15'),
(209, 'App\\Models\\Guest', 27, 'https://fcm.googleapis.com/fcm/send/dxBz5I1_PpA:APA91bFFU_bH7O40Gv9sh9yslXVGuU6uWJxXr7sWGfERV9lVpqd6uVDmeQWQF0t33tgR6Lob5iEHXIFqt7W2C6ekQkzo9CGBuxwOh1In_vD69ZNTDiBD_kNtsENyWXk5Zx0k6piL7pxS', 'BDWsJxS4BgIABV91lFbrlJm0id0TjDVWGJTfrUy5uxt8t0Fa9eaY9tWrQl8mnJ3AlmMNh--y1xyXsy1LrkxruDQ', 'sJJuUc1ETxf5aWrGAHZTJg', NULL, '2024-01-26 10:56:36', '2024-01-26 10:56:36'),
(210, 'App\\Models\\Guest', 28, 'https://fcm.googleapis.com/fcm/send/fcjc5m3N4Qc:APA91bFF3EGf7nXPIm0gOVPK73ZUnOUcJFH6Mpe9rSAVe94HW7VlWC-YWrGEGg9K54f-PiJERK6AKFgpQM-Jwktm13Jw3n0a8RW90LDnGm_V507-BqIHomZRcQWzJOj9_-NkVvI33fk8', 'BMlGI_GKDwla5idkB7KF5vVIfQiqKVvGMFm-7iAKQu-u-792-2_kLFUo-npjNxRZ47Y2KwnkSivZLtIzuYfUrik', 'VEEJVDSh2UX_J1DI21QeYg', NULL, '2024-01-26 11:01:48', '2024-01-26 11:01:48'),
(211, 'App\\Models\\Guest', 29, 'https://fcm.googleapis.com/fcm/send/fhXyFntgfkc:APA91bGFZOdcnjT1IFOjX3gSrRfgO4Qt4VajFgWIhJ9bvzAzTIxrG_9gQVWbceXJiFesAImy-o_kbxVoqnusp-ArsxDea-JhOOhX-Y0yzZgdste4y-0mHsHODPdOLwhcbWSswYETXnoa', 'BESv_mAQ_3P0wUOf8gvgAv0ta2ZT8NmT7sRReMlEHeptjBME9JR7aXe-KTmsxkAQNNU5GiLtU6yMWTVNTDRQQFo', 'Duu2hWGEeLQY-YFBoFBfSQ', NULL, '2024-01-26 12:00:26', '2024-01-26 12:00:26'),
(212, 'App\\Models\\Guest', 30, 'https://fcm.googleapis.com/fcm/send/fet8GSmnYto:APA91bHHw8K75hjSdRaZqiJzMPY6l_SHzKh3WUPHISsW-57RR8UfSNyjU1Ah_p0yIdzU9kfUnN8keYqZ7vRTFFbRm4gdajUBws6yFStyCcSOAVKjkgwirk_KK91pl-QmU7r0pbWGxmqB', 'BG6-IGJUoVxfZ1TieTTzsbPENXRZt6N0MlbnW2JoIlhU0hY3tMh_hlKkAvmRDbsU5alM8h4pstbKgsuHVoOXqXA', 'V-oRkRtHKjo4dbGbFVhuwQ', NULL, '2024-01-26 14:24:40', '2024-01-26 14:24:40'),
(213, 'App\\Models\\Guest', 31, 'https://fcm.googleapis.com/fcm/send/fv0waEXMWTw:APA91bHwYAjYMVNZvwTM9mzvNgutb-GXHGdFv1M2Yc3t80XgBjFddj0G-JWPL5XanI_l_A_eDWBjdfZtiVts45rhpdOnpm2LKYE3kQjS2jEBzgpftL2oPYWErCPveOvMnmi3RE7J5zsy', 'BMSJBb19Q9AjJs358nyhjwIwhfi2qJdwmZ8tAdbTbmfVBx4hmzuV6xI5E1IK_5wTkp5dgD_Thfeb3za2kKLQbxg', 'bcV3mMoP9Y-BUI_2GnCTfQ', NULL, '2024-01-26 14:34:43', '2024-01-26 14:34:43'),
(214, 'App\\Models\\Guest', 32, 'https://fcm.googleapis.com/fcm/send/feQ0flyOEcY:APA91bFZx2iZ6ElHuPE7GUke9MjvLsq_aX7QP2oKchzLU5U7C-U5KtDuYT_o2FDI2rCbbPLZIZ3M6DsD4xISrfrvLbI7EcHgplCtOxk1Gp8iIpsYsrGmr4dYUHgJ7NBOCr8KdSrwqImf', 'BBP0VwaM0NqpMo10oPsA83CwIYb0T_AloQQ6FkBPlBF8h0fvugcsYonREbmg0PuCKg9l3gWFjUEQoQAK6Y0ZDtA', 'nXJ4w9Utiqy3Tgk7OqLWiQ', NULL, '2024-01-26 15:43:34', '2024-01-26 15:43:34'),
(215, 'App\\Models\\Guest', 33, 'https://fcm.googleapis.com/fcm/send/cpILVB3wpsY:APA91bGQ-sTncefT086lDiTDneWoZ_WQdlr1VcBc8ag-xP8UL-K_2WfbFWsh8i5KQI_YwqXALLhojbUD2jUtJTJJU4RfxMcc1Lo-KW0dlyI63ZZ6gcHvI03PQsn1f-vG6AmLXRwgs0w8', 'BEID2RBAlYQLs1b6vGuQA4AtMAX0uZ092GG7tPoYRc3o5cCPIYdmdrSQ1E7Sv4PCX7djdbualKRtywyQuKz-yTA', 'WGu6TCxYOBMIvtaal4JeNw', NULL, '2024-01-26 16:37:34', '2024-01-26 16:37:34'),
(216, 'App\\Models\\Guest', 34, 'https://fcm.googleapis.com/fcm/send/fUmte5rnlIk:APA91bHT-VisnJkV-DqrcfoDf1vK1Tn6Tejf4Cgj8j4aIRjU_wT6NVVhVtlwuSFmXqEBDaptixPcdetyKG97CGvCZfiqPHVnQ3_3alUCK161RusEK-IhsWI2KszWwjdW6aqfn06GDlIs', 'BGHb-FAg22CUaIvCCalwfjGp26cgc3oMc2cXZtfvX0YNPWX8PSul2rCR4DPHaOoyXWSsicwhzjYRj7vneBHUn5Q', 'EzTWfBhX5_MlJ0n68fSO9w', NULL, '2024-01-26 17:03:33', '2024-01-26 17:03:33'),
(217, 'App\\Models\\Guest', 35, 'https://fcm.googleapis.com/fcm/send/eQWjUnKndCc:APA91bFBR93TXu153LHUx7mIk0dUtoSCOBgXVwmZ56hFHmeUrjTgpWIlxSizoBo18cNDjoEz7YReq90YbtilXFQjCdASTrGK_pW4nOjENFY7IlDbllaKbLA1Px31IW7Sw-GQETdqnwNm', 'BNwAHgEUPsBGoJjNewsKeWQF7trP-3aIoMinAMcs4Qewp9IpMCDdKUKqU4QhzvsxHt93RcHRVbfkG4fkzTUUKJk', 'sgOzzMmpioUoqcTL0-Z1Wg', NULL, '2024-01-26 17:22:46', '2024-01-26 17:22:46'),
(218, 'App\\Models\\Guest', 36, 'https://fcm.googleapis.com/fcm/send/dsirQJucsB0:APA91bHycmGN_r-0o5XcaCaG61Y7c3BtPjVhczvwpyYuxq5k7aLpfWpHxXox2t1R-nKuQ683IHHjU8-0j6P8a-xQ-ZboFa0ejqcfLOncwLLSgvPRNpDRYNwNunaRwurphJjuQ08tYbNa', 'BPxxQaI6JDV7ZnLKTBK3MarLsozz5niYXqx2uWT-yjPdie_IXsB_b5zfx8yIoB9aFalGK9z3ZkUISkfTYRTGVXo', 'ks1f3Lw21AKR1lNBr0J7rw', NULL, '2024-01-26 18:01:31', '2024-01-26 18:01:31'),
(219, 'App\\Models\\Guest', 37, 'https://fcm.googleapis.com/fcm/send/cGvGSeyW3l8:APA91bEtXvUwwgrXLH1OZ6NRBIpK2oZ3Qcmlg7DR5SK2CPS_Pl0IVmqdIk514DpRWFmWNFZjst3WQFmZ6Cq4txXljfLV71mX7RTDnr0TnKxpWPj7-H7wTYCFURmMkQBDJtYCgG8T3Kkl', 'BHvukhWLR2zETGFCiNsB6lvLafNOlRrOKgkt1QcGYq66BRiEx57Qf1zJdAD-1VAbq9IMWhY2xf6fyaWGbqrr4gA', 'UEF72Vbhv2EL3a8PPVs7kw', NULL, '2024-01-26 21:17:58', '2024-01-26 21:17:58'),
(220, 'App\\Models\\Guest', 38, 'https://fcm.googleapis.com/fcm/send/dY13_Dk0haY:APA91bFKoNoi6EaxP6k2vEih_KSzlDiDGL2oyuQMiU0YG8p1_Hnqp2hlRXkWmO1-Sx2rJJxP0XnNpgLlt5_DKPkbzmR24AZKMb-D_PNEFYRtMlCbqUFK9as7mJLRxedp9GAbLyLCFjtC', 'BMe7sY9DfnZOH6i5su1af9oOpcqFBwlKkgmlKr2wadTAhOOLh8TFVPblLpxfGqS8tt5RLiT-vgCN4-_ssPOYKmg', 'vyvKIdY-6Da4j9-jbF7nuw', NULL, '2024-01-26 22:58:38', '2024-01-26 22:58:38'),
(221, 'App\\Models\\Guest', 39, 'https://fcm.googleapis.com/fcm/send/es63u_cHbXs:APA91bFZgMM0ARlpSdKAjmqpxDI8sVT5z2lCumPjTibA4olo46ijIYHz9yKrIBQD_45DXD0Y0p-GRWmsg6tuMhOKxWpzLbeiEl29VtRNLk8sjX16VMP3wOK0YttB34vRoBFc4F05fAEq', 'BP3QeSMkNFc0y1v4J_kJKS2ExsP3a6XPSWlxIT2MOi4Bnc81LumJOzzWX-9iiYaFeLNEtUnXxEfuFhSleS-EmVE', 'zFCZZCkl1xcBJkAV0TN6eg', NULL, '2024-01-26 23:04:44', '2024-01-26 23:04:44'),
(222, 'App\\Models\\Guest', 40, 'https://fcm.googleapis.com/fcm/send/cDi0HYd_6wU:APA91bGpfwGzMCaDgxs4CDZRwMaF3Cc-xAnrNHQMORl3fyLAyditYPO56m5kwQcoXo7ZoWXn2QsBe2Ce3xpCezeWhM4LL3PBLW62WQefBLLf-m8KGrNUkjVjSlRAXqxkBWXgp50XKakb', 'BMj9snaUNbqCqiv2tz3IWNplqylHvZviE6Q316jSF19PAVhsTBAM01lr_J7brtQK5u0a5cjMuRmacePMxb8DoJc', 'i-vOMoEMOpX3VucEHJV-uQ', NULL, '2024-01-26 23:05:46', '2024-01-26 23:05:46'),
(223, 'App\\Models\\Guest', 41, 'https://fcm.googleapis.com/fcm/send/f24-34ycVFo:APA91bE_TKow3vnTmHK-vEN8NRFfzMRICZeJRvoG1zy9d1sSHSKCYoVeu_uWK_vKE7i0riqUAUlBOruoYN52G7bKqDfH_QlymLZvB7COPukGxyndXdIAzxauSODIM3po5r0evDTZDmIG', 'BClwLiid6M4Znd338ObMUucbuDyWDS4KPB9Y9UHZT1DUXZ5c4RAITeBTQH4IXyBff198hYkteHnq7Yo6joPAMRc', 'OUYcfTqZL0XBaXXW8HzngQ', NULL, '2024-01-27 00:18:28', '2024-01-27 00:18:28'),
(224, 'App\\Models\\Guest', 42, 'https://fcm.googleapis.com/fcm/send/fNQyUC3c-zg:APA91bFMAnr2hm-Li6lnRtp6SB3p9FRt6VeXSu-mxxFoTpJyPBnALmbJWX03ZHoL16nzvsqgOKzTrwsfd_AaKMh8xXGBL6UF2zR1NGRHD7NXKFE5DXqmn5aq-7feVnozNbtb1Lc48GQV', 'BOmchZl46W6XGc6VlUCOH1OOTwSdwUc4KXCC5HM3LWERGAPS_fxlt2-KB2yOg3WQ1NTYvYSL4q2wW8IcPK12pvg', '5xcriz-4tu2JrQjKvlVKeg', NULL, '2024-01-27 00:18:39', '2024-01-27 00:18:39'),
(225, 'App\\Models\\Guest', 43, 'https://fcm.googleapis.com/fcm/send/cm90FBHTsLA:APA91bF7j7AmiB7S5xWtD8eCnoyOF10OolReiImcDZrcMVvX81VqU1TeDddVvdotQ-y0yYrD8TgovpQcDL3-Qlc1caRGOdZfS90sJmtH13pEVO94-468sjasUlgQE4cTjSJ-nD0oPLmT', 'BPZiHhlHoaBQz1-4wxSsj3YGPnizoznBee7_wVQXubMey4G3mTZO_OdVo8kxVJbDqnI27JY-JXgMlIR0qGMDss8', 't9bM9zHYDj5rsYAc5vpo7g', NULL, '2024-01-27 00:27:08', '2024-01-27 00:27:08'),
(226, 'App\\Models\\Guest', 44, 'https://fcm.googleapis.com/fcm/send/cyHV7exVGLE:APA91bFe0RgADILCfxCYzEYeVk7rGgr75KE-aYFIMXCabLROzn3OMq-RGDKqTz3c9i0dqvD0PGZBLEueRqUPejmWTU9GamBa8Wmzn7bzormeN3G6-faTTprAjK-AIt5Pu4eUSts2hvkP', 'BEH0jHBGCX1L9pELPD4CUyXSaUIZrP_1dc7Kj978rBBSNuRGLwr536tcuK5ehyAAPON8GOgyJBeRKi8PzwofTEk', 'eV5aO4Lv2_4Ucr94mVFnCA', NULL, '2024-01-27 00:43:01', '2024-01-27 00:43:01'),
(227, 'App\\Models\\Guest', 45, 'https://fcm.googleapis.com/fcm/send/cH0YgWw_HQY:APA91bGp4iaLBEiI7Uodh3gAhFzH_MeBk2o8uGx04zss2m5_PvfzXHDysuQsZpmOcTFpX8gnTo87FXMAhV-mNO26EQjpJlT5vfUArEHr28ar8atNlsrjSG4ilL45HszFnUCR4BaxUW6g', 'BHvIw4ux3nItVwb7tzKwK71jFnYMY1z_Wh2zv3x3PN1msEs5l3s-Otq9ChZ8CVkjtyIBENCsWFxWhHDxH88NOqU', '9iGdlMNoGaZk-gn8cmA-jQ', NULL, '2024-01-27 00:54:54', '2024-01-27 00:54:54'),
(228, 'App\\Models\\Guest', 46, 'https://fcm.googleapis.com/fcm/send/diF8IBRmiGY:APA91bHGBdRuvvq2XVtVWgYIEyf-i_QBCOvdQFWldwXuWyTrc0uCE4WiFTY8IFMjpJqxqExIALe40hmMM58VPkhZfmCOcZ5y1M4XCM41Rv8nXWh1BlBoDf1In5xunVdss0dTBXkpi9zC', 'BJ_do9H08hnzOK-SM2PGGmnG5IhIfY7Ltxl68ZmEoCbZFI6Ou4y-6FcyqHn7FjpnnEqlTRvORNox_9cTdMUaenQ', 'LrLsRFthiv2pYnvf0QWXAQ', NULL, '2024-01-27 00:55:05', '2024-01-27 00:55:05'),
(229, 'App\\Models\\Guest', 47, 'https://fcm.googleapis.com/fcm/send/d9a03o7EtxE:APA91bGeTuYhguSJCK-y7TMWvvrCUInNT6waxGHy-SkH8ot7dZvQZ8ZHu7RUIRdIkbCqfP9zK1VgTwZ3H3fwDsXMGPnGaAoSBCeB_zhT8iqvOIW6RI7t58MkhcmbDgM45RucchMI7VSI', 'BJsG3NMH6ccXeVYbPn15gAMVdgqFh3-ZHBLFs1p3Aoe-CB2MbY87Y-VR8QwV85jHiNWPVc6NlxYsIBv8gxbKp-c', 'ZM4BmRXQXskvt32N3YnlGg', NULL, '2024-01-27 02:23:25', '2024-01-27 02:23:25'),
(230, 'App\\Models\\Guest', 48, 'https://fcm.googleapis.com/fcm/send/eEche4lhVpc:APA91bGg07Tyzma2nBJSvrIBRai8G0ppx9rzdzH5NDqfqnrTOiJZijs0Y3yxsJ00pSrMbKsQC93r2oUGjqZiqjqXLGIdj75feBIZJ0Y9L5xvGQWGZ45FSAmY9iLe-FsY5seS7fCP2Qma', 'BHjn7o57nYO6pmg3wDDDi6AsecP3E5_MnnODllz6qlxNsNy56AkbWrcbDYGbvIfKEz7Xob0fCXYcpimR-ndc6nk', 'wf8Yc1dhGPmWRFZqKnfDCA', NULL, '2024-01-27 02:28:05', '2024-01-27 02:28:05'),
(231, 'App\\Models\\Guest', 49, 'https://fcm.googleapis.com/fcm/send/fC8vkUbWm4k:APA91bEVVjADAOmGY5YpHdmOTz9NVLAsTTziiNgEKHpILjWVbkB0pYLxu-FN4MpoOp9909Q2GjuMurcnjgNZ_zUKcTAvni2zHZGpLi0YfNEfiQA66OVZ-sxeibZZQ0T939OBPTSsHEQT', 'BHm6QBEsa-i52y4Fn60rVlP3oPL8fuHlb8Wh1Uaya6JmOcg-WFKWqO6xkaVf5fv-e0kiZah1mqZ8wnehnAaCZRs', 'dWLCcLwGsbh6PbAm3vUHmw', NULL, '2024-01-27 03:54:59', '2024-01-27 03:54:59'),
(232, 'App\\Models\\Guest', 50, 'https://fcm.googleapis.com/fcm/send/ckP5aO1bjgQ:APA91bFNGQaTbsKHnmN06wE2yi-mpPWh77JJQ8XHOPMOHMrGxyibFsc6_ZEs8rLdhsqKVpMEL2OTJSjJdHx8kNgl6uMuH72KP1nu1Lo0KokQeqdZlCp2mX-mSWb5ze8_g4syuABghq1K', 'BN2f8eZff8KVW5Syzoa4lpTOT9YGhNzXtbZbWGHyOhXUB8DIC-NOv_KBRM_-XRBl2XMo0o_ADbsdCqKIQAKchjI', 'KsXS-N43GYn4p4FQokqtPw', NULL, '2024-01-27 04:08:56', '2024-01-27 04:08:56'),
(233, 'App\\Models\\Guest', 51, 'https://fcm.googleapis.com/fcm/send/e0xXserOg84:APA91bFnfPQa-ou4kUdA1k3sokEFzVFIdOFux1IakwU-jMhnHr08MHkoMv5KwNNfyV7dveCBcIkoVF3IAO4QpkAMvTvmq1Rj85KkzsfXobASMx9WOA2_u43F-5-DDftdjeB7sI3wxa0t', 'BMecyy1ntb3QxZI4rwk29z3LdVa4Z3gTjcrGNrdHTZvYGS8ZBcYK4rG6B47NwWTVPv0aYBG6OZtJ5F-bU9lSDao', 'uMABwdd2N4sS481fWrJbEg', NULL, '2024-01-27 04:50:48', '2024-01-27 04:50:48'),
(234, 'App\\Models\\Guest', 52, 'https://fcm.googleapis.com/fcm/send/fqmjnez3j3o:APA91bEAUvXu9tN9JO5w-hoxK9bCFHlx2u2ITZujzrhHqp9N45XSgRNgJOROUAn7zVWjavafaCHRC_17IUXlG1gh8nSCYsP_9KyvfZ6t6vFA7vQdi3TyXSHHaExU2DRPra0uvpBgOy6U', 'BB4iUhxBcDFbQ3gsQrJhO1n3-iTlNa2u-dM6TbyVAcfQlta_H4K-ACFh6YdCkeLr2LwHIAIzz3psQsLvZW1rYYE', 'NZDEorpsD-mOo-Xvk5NPzw', NULL, '2024-01-27 09:38:43', '2024-01-27 09:38:43'),
(235, 'App\\Models\\Guest', 53, 'https://fcm.googleapis.com/fcm/send/cxLTSsNbGNI:APA91bEcW1THEiz6svG0mYIpN0DzkwsoVkZPDzwrbp8Hc6zFkZwmsoTu4TnrkrSFrNbB4fPfgJ9vOqNEohWp22gZ770dsvgKALT1exNrI2RP_ob1uS0SQ5HSx3EZ5CaBUKXhGujw2OjW', 'BOdavsP1ljnNqwpwa8JGhJcdgvx6Bg_91iDKyAvLny6to-uF_ZYh5HnihoZNLDmULi_mlPL5_gLVDpJGAsQSuYs', 'PUGew9RGNIaWGVuaxF9YNg', NULL, '2024-01-27 05:18:06', '2024-01-27 05:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

CREATE TABLE `quick_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` smallint(5) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quick_links`
--

INSERT INTO `quick_links` (`id`, `language_id`, `title`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(3, 9, 'سياسة الخصوصية', 'https://codecanyon8.kreativdev.com/multi-gig/demo/%D8%B3%D9%8A%D8%A7%D8%B3%D8%A9-%D8%A7%D9%84%D8%AE%D8%B5%D9%88%D8%B5%D9%8A%D8%A9', 1, '2021-06-22 22:52:38', '2024-01-11 15:41:46'),
(4, 9, 'معلومات عنا', 'https://codecanyon8.kreativdev.com/multi-gig/demo/about', 2, '2021-06-22 22:53:09', '2024-01-11 15:42:47'),
(5, 9, 'اتصال', 'https://codecanyon8.kreativdev.com/multi-gig/demo/contact', 3, '2021-06-22 22:53:27', '2024-01-11 15:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(4, 'Admin', '[\"Language Management\",\"Payment Gateways\",\"Basic Settings\",\"FAQ Management\",\"Blog Management\",\"Footer\",\"Home Page\",\"Support Tickets\",\"Service Management\",\"Service Orders\"]', '2021-08-06 22:42:38', '2023-11-30 06:34:14'),
(6, 'Moderator', '[\"Basic Settings\",\"Home Page\",\"Invoice Management\",\"Blog Management\",\"FAQ Management\",\"Footer\"]', '2021-08-07 22:14:34', '2022-07-26 11:05:28'),
(14, 'Supervisor', 'null', '2021-11-24 22:48:53', '2022-02-26 05:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_category_section_status` tinyint(4) NOT NULL DEFAULT '1',
  `about_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `features_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `featured_services_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `testimonials_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `blog_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `partners_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `featured_products_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `newsletter_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `footer_section_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `cta_section_status` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `service_category_section_status`, `about_section_status`, `features_section_status`, `featured_services_section_status`, `testimonials_section_status`, `blog_section_status`, `partners_section_status`, `featured_products_section_status`, `newsletter_section_status`, `footer_section_status`, `cta_section_status`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, '2023-12-30 06:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `section_titles`
--

CREATE TABLE `section_titles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `category_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featured_services_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `testimonials_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blog_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featured_products_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `newsletter_section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `section_titles`
--

INSERT INTO `section_titles` (`id`, `language_id`, `category_section_title`, `featured_services_section_title`, `testimonials_section_title`, `blog_section_title`, `featured_products_section_title`, `newsletter_section_title`, `created_at`, `updated_at`) VALUES
(3, 9, 'استكشف السوق', 'أهم الخدمات المميزة', 'ماذا يقول عملاؤنا', 'رؤيتنا ومقالاتنا', 'أعلى المنتجات المميزة', 'احصل الآن على استشارات أعمال مجانية', '2022-04-17 23:38:57', '2022-07-21 09:30:51'),
(5, 8, 'Most Popular Categories', 'Top Featured Services', 'What Our Client Say About Multigig Services', 'Read Our Blog', NULL, NULL, '2022-05-14 22:56:47', '2023-12-28 07:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `amount` double(8,2) DEFAULT '0.00',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `avg_rating` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_email_addresss` tinyint(4) DEFAULT '1',
  `show_phone_number` tinyint(4) DEFAULT '1',
  `show_contact_form` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `photo`, `email`, `recipient_mail`, `phone`, `username`, `password`, `status`, `amount`, `email_verified_at`, `avg_rating`, `show_email_addresss`, `show_phone_number`, `show_contact_form`, `created_at`, `updated_at`) VALUES
(0, NULL, 'admin@gmail.com', 'admin@gmail.com', NULL, 'admin', NULL, 1, 0.00, NULL, NULL, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seller_infos`
--

CREATE TABLE `seller_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

CREATE TABLE `seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `meta_keyword_home` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_home` text COLLATE utf8_unicode_ci,
  `meta_keyword_services` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_services` text COLLATE utf8_unicode_ci,
  `meta_keyword_products` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_products` text COLLATE utf8_unicode_ci,
  `meta_keyword_cart` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_cart` text COLLATE utf8_unicode_ci,
  `meta_keyword_blog` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_blog` text COLLATE utf8_unicode_ci,
  `meta_keyword_faq` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_faq` text COLLATE utf8_unicode_ci,
  `meta_keyword_contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_contact` text COLLATE utf8_unicode_ci,
  `meta_keyword_customer_login` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_customer_login` text COLLATE utf8_unicode_ci,
  `meta_keyword_customer_signup` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_customer_signup` text COLLATE utf8_unicode_ci,
  `meta_keyword_customer_forget_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_customer_forget_password` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta_keyword_checkout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_checkout` text COLLATE utf8_unicode_ci,
  `meta_keyword_aboutus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_aboutus` text COLLATE utf8_unicode_ci,
  `meta_keyword_service_order` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_service_order` text COLLATE utf8_unicode_ci,
  `meta_keyword_invoice_payment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description_invoice_payment` text COLLATE utf8_unicode_ci,
  `seller_page_meta_keywords` text COLLATE utf8_unicode_ci,
  `seller_page_meta_description` text COLLATE utf8_unicode_ci,
  `meta_keyword_seller_login` text COLLATE utf8_unicode_ci,
  `meta_description_seller_login` text COLLATE utf8_unicode_ci,
  `meta_keyword_seller_signup` text COLLATE utf8_unicode_ci,
  `meta_description_seller_signup` text COLLATE utf8_unicode_ci,
  `meta_keyword_seller_forget_password` text COLLATE utf8_unicode_ci,
  `meta_description_seller_forget_password` text COLLATE utf8_unicode_ci,
  `pricing_page_meta_keywords` text COLLATE utf8_unicode_ci,
  `pricing_page_meta_description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `language_id`, `meta_keyword_home`, `meta_description_home`, `meta_keyword_services`, `meta_description_services`, `meta_keyword_products`, `meta_description_products`, `meta_keyword_cart`, `meta_description_cart`, `meta_keyword_blog`, `meta_description_blog`, `meta_keyword_faq`, `meta_description_faq`, `meta_keyword_contact`, `meta_description_contact`, `meta_keyword_customer_login`, `meta_description_customer_login`, `meta_keyword_customer_signup`, `meta_description_customer_signup`, `meta_keyword_customer_forget_password`, `meta_description_customer_forget_password`, `created_at`, `updated_at`, `meta_keyword_checkout`, `meta_description_checkout`, `meta_keyword_aboutus`, `meta_description_aboutus`, `meta_keyword_service_order`, `meta_description_service_order`, `meta_keyword_invoice_payment`, `meta_description_invoice_payment`, `seller_page_meta_keywords`, `seller_page_meta_description`, `meta_keyword_seller_login`, `meta_description_seller_login`, `meta_keyword_seller_signup`, `meta_description_seller_signup`, `meta_keyword_seller_forget_password`, `meta_description_seller_forget_password`, `pricing_page_meta_keywords`, `pricing_page_meta_description`) VALUES
(2, 9, 'مسكن', 'وصف المنزل', 'خدمات', 'وصف الخدمات', 'منتجات', 'وصف المنتجات', 'عربة التسوق', 'وصف عربة التسوق', 'مدونة او مذكرة', 'وصف المدونة', 'التعليمات', 'التعليمات الوصف', 'اتصال', 'وصف جهة الاتصال', 'تسجيل الدخول', 'وصف تسجيل الدخول', 'اشتراك', 'وصف الاشتراك', 'نسيت كلمة المرور', 'نسيت كلمة المرور الوصف', '2021-07-30 05:57:39', '2023-07-15 08:47:12', NULL, NULL, NULL, NULL, 'dddddddddddddd', 'ssssssssss', 'dddddddddddd', 'sssssssssss', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 8, 'Home', 'Home Description', 'Services', 'Services Description999', 'Products', 'Products Description', 'Cart', 'Cart Description', 'Blog', 'Blog Description', 'FAQ', 'FAQ Description', 'Contact', 'Contact Description', 'Login', 'Login Description', 'Signup', 'Signup Description', 'Forget Password', 'Forget Password Description', '2022-03-05 23:49:35', '2023-12-19 09:43:10', 'dd,der,ser,see', 'ssssererwer', 'sss,ereaw', 'sss999', 'service orders', 'Service Orders', 'Invoice Payments', 'Invoice Payments Descriptions', 'seller,sellers', 'meta description seller', 'seller_login', 'seller login description', 'seller signup', 'seller signup description', 'seller forget password', 'seller sigdescription', 'Pricing', 'Pricing Description');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT '0',
  `thumbnail_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slider_images` text COLLATE utf8_unicode_ci NOT NULL,
  `video_preview_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `live_demo_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quote_btn_status` tinyint(3) UNSIGNED DEFAULT NULL,
  `service_status` tinyint(3) UNSIGNED NOT NULL,
  `is_featured` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `average_rating` decimal(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `package_lowest_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `skills` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_addons`
--

CREATE TABLE `service_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(8,2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `is_featured` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `add_to_menu` int(11) DEFAULT '0' COMMENT '1=added, 0 = none',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `language_id`, `image`, `name`, `slug`, `status`, `serial_number`, `is_featured`, `add_to_menu`, `created_at`, `updated_at`) VALUES
(1, 8, '65a790948205d.png', 'Graphics & Design', 'graphics--design', 1, 1, 'yes', 1, '2023-12-17 07:26:15', '2024-01-17 13:32:20'),
(2, 8, '65a791a2613b9.png', 'Digital Marketing', 'digital-marketing', 1, 2, 'yes', 1, '2023-12-17 07:29:33', '2024-01-17 13:36:50'),
(3, 8, '65a791fc70e91.png', 'Writing & Translation', 'writing--translation', 1, 5, 'yes', 1, '2023-12-17 07:33:06', '2024-01-17 13:38:20'),
(4, 8, '65a791eaa6c9d.png', 'Video & Animation', 'video--animation', 1, 4, 'yes', 1, '2023-12-17 07:34:19', '2024-01-17 13:38:02'),
(5, 8, '65a791d65b295.png', 'Programming &Tech', 'programming-tech', 1, 3, 'yes', 1, '2023-12-17 07:35:46', '2024-01-17 13:37:42'),
(6, 8, '65a79214065fd.png', 'Music & Audio', 'music--audio', 1, 6, 'yes', 1, '2023-12-17 07:37:09', '2024-01-17 13:38:44'),
(7, 8, '65a7922b395e0.png', 'Business', 'business', 1, 7, 'no', 1, '2023-12-17 07:38:02', '2024-01-17 13:39:07'),
(8, 8, '65a7923dcb116.png', 'Lifestyle', 'lifestyle', 1, 8, 'no', 1, '2023-12-17 07:38:58', '2024-01-17 13:39:25'),
(9, 8, '65969dac67b21.png', 'Data & Analytics', 'data--analytics', 1, 9, 'no', 1, '2023-12-17 07:40:01', '2024-01-04 11:59:40'),
(10, 8, '65969dd2e80e1.png', 'Engineering & Architecture', 'engineering--architecture', 1, 10, 'no', 1, '2023-12-17 07:41:05', '2024-01-04 12:00:18'),
(21, 8, '65a798abde147.png', 'AI Services', 'ai-services', 1, 11, 'no', 1, '2024-01-17 14:06:51', '2024-01-17 14:07:16'),
(22, 8, '65a798bdd8633.png', 'Photography', 'photography', 1, 12, 'no', 1, '2024-01-17 14:07:09', '2024-01-17 14:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `service_contents`
--

CREATE TABLE `service_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  `service_subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skills` text COLLATE utf8_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_faqs`
--

CREATE TABLE `service_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_orders`
--

CREATE TABLE `service_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `informations` text COLLATE utf8_unicode_ci,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seller_membership_id` bigint(20) DEFAULT NULL,
  `package_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `addons` text COLLATE utf8_unicode_ci,
  `addon_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `grand_total` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tax_percentage` float DEFAULT '0',
  `tax` float DEFAULT '0',
  `currency_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `raise_status` int(11) DEFAULT '0' COMMENT '0=none, 1 = raised, 2=completed, 3=rejected',
  `conversation_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_order_messages`
--

CREATE TABLE `service_order_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `person_id` bigint(20) UNSIGNED NOT NULL,
  `person_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_original_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

CREATE TABLE `service_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `current_price` decimal(8,2) UNSIGNED NOT NULL,
  `previous_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `delivery_time` int(10) UNSIGNED DEFAULT NULL,
  `number_of_revision` int(10) UNSIGNED DEFAULT NULL,
  `features` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `rating` smallint(5) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_subcategories`
--

CREATE TABLE `service_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '1=active, 0=deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_medias`
--

CREATE TABLE `social_medias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` mediumint(8) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `social_medias`
--

INSERT INTO `social_medias` (`id`, `icon`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(36, 'fab fa-facebook-f', 'https://www.facebook.com/', 1, '2021-11-20 03:01:42', '2021-11-20 03:01:42'),
(37, 'fab fa-twitter', 'https://twitter.com/', 3, '2021-11-20 03:03:22', '2021-11-20 03:03:22'),
(38, 'fab fa-linkedin-in', 'https://www.linkedin.com/', 2, '2021-11-20 03:04:29', '2021-11-20 03:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci,
  `attachment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `language_id`, `image`, `name`, `occupation`, `comment`, `created_at`, `updated_at`) VALUES
(17, 9, '625e46240a476.jpg', 'فلان الفلاني', 'مدرس', 'لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه ... بروشور او فلاير على سبيل المثال ... او نماذج مواقع انترنت', '2022-04-18 23:18:28', '2022-08-03 09:51:28'),
(19, 9, '625e4670bc59c.jpg', 'جين دو', 'المدير التنفيذي', 'لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه ... بروشور او فلاير على سبيل المثال ... او نماذج مواقع انترنت', '2022-04-18 23:19:44', '2022-08-03 09:51:37'),
(20, 9, '625e46d882ff0.png', 'مارك وينز', 'مدونة الغذاء', 'لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه ... بروشور او فلاير على سبيل المثال ... او نماذج مواقع انترنت', '2022-04-18 23:21:28', '2022-08-03 09:51:47'),
(25, 9, '62ea4544d23ec.jpg', 'هاري بوتر', 'الممثل', 'لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه ... بروشور او فلاير على سبيل المثال ... او نماذج مواقع انترنت', '2022-07-21 09:46:10', '2022-08-03 09:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_conversations`
--

CREATE TABLE `ticket_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `person_id` bigint(20) UNSIGNED NOT NULL,
  `person_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reply` longtext COLLATE utf8_unicode_ci,
  `attachment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `gmt_offset` decimal(10,2) NOT NULL,
  `dst_offset` decimal(10,2) NOT NULL,
  `raw_offset` decimal(10,2) NOT NULL,
  `is_set` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `country_code`, `timezone`, `gmt_offset`, `dst_offset`, `raw_offset`, `is_set`) VALUES
(1, 'AD', 'Europe/Andorra', '1.00', '2.00', '1.00', 'no'),
(2, 'AE', 'Asia/Dubai', '4.00', '4.00', '4.00', 'no'),
(3, 'AF', 'Asia/Kabul', '4.50', '4.50', '4.50', 'no'),
(4, 'AG', 'America/Antigua', '-4.00', '-4.00', '-4.00', 'no'),
(5, 'AI', 'America/Anguilla', '-4.00', '-4.00', '-4.00', 'no'),
(6, 'AL', 'Europe/Tirane', '1.00', '2.00', '1.00', 'no'),
(7, 'AM', 'Asia/Yerevan', '4.00', '4.00', '4.00', 'no'),
(8, 'AO', 'Africa/Luanda', '1.00', '1.00', '1.00', 'no'),
(9, 'AQ', 'Antarctica/Casey', '8.00', '8.00', '8.00', 'no'),
(10, 'AQ', 'Antarctica/Davis', '7.00', '7.00', '7.00', 'no'),
(11, 'AQ', 'Antarctica/DumontDUrville', '10.00', '10.00', '10.00', 'no'),
(12, 'AQ', 'Antarctica/Mawson', '5.00', '5.00', '5.00', 'no'),
(13, 'AQ', 'Antarctica/McMurdo', '13.00', '12.00', '12.00', 'no'),
(14, 'AQ', 'Antarctica/Palmer', '-3.00', '-4.00', '-4.00', 'no'),
(15, 'AQ', 'Antarctica/Rothera', '-3.00', '-3.00', '-3.00', 'no'),
(16, 'AQ', 'Antarctica/South_Pole', '13.00', '12.00', '12.00', 'no'),
(17, 'AQ', 'Antarctica/Syowa', '3.00', '3.00', '3.00', 'no'),
(18, 'AQ', 'Antarctica/Vostok', '6.00', '6.00', '6.00', 'no'),
(19, 'AR', 'America/Argentina/Buenos_Aires', '-3.00', '-3.00', '-3.00', 'no'),
(20, 'AR', 'America/Argentina/Catamarca', '-3.00', '-3.00', '-3.00', 'no'),
(21, 'AR', 'America/Argentina/Cordoba', '-3.00', '-3.00', '-3.00', 'no'),
(22, 'AR', 'America/Argentina/Jujuy', '-3.00', '-3.00', '-3.00', 'no'),
(23, 'AR', 'America/Argentina/La_Rioja', '-3.00', '-3.00', '-3.00', 'no'),
(24, 'AR', 'America/Argentina/Mendoza', '-3.00', '-3.00', '-3.00', 'no'),
(25, 'AR', 'America/Argentina/Rio_Gallegos', '-3.00', '-3.00', '-3.00', 'no'),
(26, 'AR', 'America/Argentina/Salta', '-3.00', '-3.00', '-3.00', 'no'),
(27, 'AR', 'America/Argentina/San_Juan', '-3.00', '-3.00', '-3.00', 'no'),
(28, 'AR', 'America/Argentina/San_Luis', '-3.00', '-3.00', '-3.00', 'no'),
(29, 'AR', 'America/Argentina/Tucuman', '-3.00', '-3.00', '-3.00', 'no'),
(30, 'AR', 'America/Argentina/Ushuaia', '-3.00', '-3.00', '-3.00', 'no'),
(31, 'AS', 'Pacific/Pago_Pago', '-11.00', '-11.00', '-11.00', 'no'),
(32, 'AT', 'Europe/Vienna', '1.00', '2.00', '1.00', 'no'),
(33, 'AU', 'Antarctica/Macquarie', '11.00', '11.00', '11.00', 'no'),
(34, 'AU', 'Australia/Adelaide', '10.50', '9.50', '9.50', 'no'),
(35, 'AU', 'Australia/Brisbane', '10.00', '10.00', '10.00', 'no'),
(36, 'AU', 'Australia/Broken_Hill', '10.50', '9.50', '9.50', 'no'),
(37, 'AU', 'Australia/Currie', '11.00', '10.00', '10.00', 'no'),
(38, 'AU', 'Australia/Darwin', '9.50', '9.50', '9.50', 'no'),
(39, 'AU', 'Australia/Eucla', '8.75', '8.75', '8.75', 'no'),
(40, 'AU', 'Australia/Hobart', '11.00', '10.00', '10.00', 'no'),
(41, 'AU', 'Australia/Lindeman', '10.00', '10.00', '10.00', 'no'),
(42, 'AU', 'Australia/Lord_Howe', '11.00', '10.50', '10.50', 'no'),
(43, 'AU', 'Australia/Melbourne', '11.00', '10.00', '10.00', 'no'),
(44, 'AU', 'Australia/Perth', '8.00', '8.00', '8.00', 'no'),
(45, 'AU', 'Australia/Sydney', '11.00', '10.00', '10.00', 'no'),
(46, 'AW', 'America/Aruba', '-4.00', '-4.00', '-4.00', 'no'),
(47, 'AX', 'Europe/Mariehamn', '2.00', '3.00', '2.00', 'no'),
(48, 'AZ', 'Asia/Baku', '4.00', '5.00', '4.00', 'no'),
(49, 'BA', 'Europe/Sarajevo', '1.00', '2.00', '1.00', 'no'),
(50, 'BB', 'America/Barbados', '-4.00', '-4.00', '-4.00', 'no'),
(51, 'BD', 'Asia/Dhaka', '6.00', '6.00', '6.00', 'yes'),
(52, 'BE', 'Europe/Brussels', '1.00', '2.00', '1.00', 'no'),
(53, 'BF', 'Africa/Ouagadougou', '0.00', '0.00', '0.00', 'no'),
(54, 'BG', 'Europe/Sofia', '2.00', '3.00', '2.00', 'no'),
(55, 'BH', 'Asia/Bahrain', '3.00', '3.00', '3.00', 'no'),
(56, 'BI', 'Africa/Bujumbura', '2.00', '2.00', '2.00', 'no'),
(57, 'BJ', 'Africa/Porto-Novo', '1.00', '1.00', '1.00', 'no'),
(58, 'BL', 'America/St_Barthelemy', '-4.00', '-4.00', '-4.00', 'no'),
(59, 'BM', 'Atlantic/Bermuda', '-4.00', '-3.00', '-4.00', 'no'),
(60, 'BN', 'Asia/Brunei', '8.00', '8.00', '8.00', 'no'),
(61, 'BO', 'America/La_Paz', '-4.00', '-4.00', '-4.00', 'no'),
(62, 'BQ', 'America/Kralendijk', '-4.00', '-4.00', '-4.00', 'no'),
(63, 'BR', 'America/Araguaina', '-3.00', '-3.00', '-3.00', 'no'),
(64, 'BR', 'America/Bahia', '-3.00', '-3.00', '-3.00', 'no'),
(65, 'BR', 'America/Belem', '-3.00', '-3.00', '-3.00', 'no'),
(66, 'BR', 'America/Boa_Vista', '-4.00', '-4.00', '-4.00', 'no'),
(67, 'BR', 'America/Campo_Grande', '-3.00', '-4.00', '-4.00', 'no'),
(68, 'BR', 'America/Cuiaba', '-3.00', '-4.00', '-4.00', 'no'),
(69, 'BR', 'America/Eirunepe', '-5.00', '-5.00', '-5.00', 'no'),
(70, 'BR', 'America/Fortaleza', '-3.00', '-3.00', '-3.00', 'no'),
(71, 'BR', 'America/Maceio', '-3.00', '-3.00', '-3.00', 'no'),
(72, 'BR', 'America/Manaus', '-4.00', '-4.00', '-4.00', 'no'),
(73, 'BR', 'America/Noronha', '-2.00', '-2.00', '-2.00', 'no'),
(74, 'BR', 'America/Porto_Velho', '-4.00', '-4.00', '-4.00', 'no'),
(75, 'BR', 'America/Recife', '-3.00', '-3.00', '-3.00', 'no'),
(76, 'BR', 'America/Rio_Branco', '-5.00', '-5.00', '-5.00', 'no'),
(77, 'BR', 'America/Santarem', '-3.00', '-3.00', '-3.00', 'no'),
(78, 'BR', 'America/Sao_Paulo', '-2.00', '-3.00', '-3.00', 'no'),
(79, 'BS', 'America/Nassau', '-5.00', '-4.00', '-5.00', 'no'),
(80, 'BT', 'Asia/Thimphu', '6.00', '6.00', '6.00', 'no'),
(81, 'BW', 'Africa/Gaborone', '2.00', '2.00', '2.00', 'no'),
(82, 'BY', 'Europe/Minsk', '3.00', '3.00', '3.00', 'no'),
(83, 'BZ', 'America/Belize', '-6.00', '-6.00', '-6.00', 'no'),
(84, 'CA', 'America/Atikokan', '-5.00', '-5.00', '-5.00', 'no'),
(85, 'CA', 'America/Blanc-Sablon', '-4.00', '-4.00', '-4.00', 'no'),
(86, 'CA', 'America/Cambridge_Bay', '-7.00', '-6.00', '-7.00', 'no'),
(87, 'CA', 'America/Creston', '-7.00', '-7.00', '-7.00', 'no'),
(88, 'CA', 'America/Dawson', '-8.00', '-7.00', '-8.00', 'no'),
(89, 'CA', 'America/Dawson_Creek', '-7.00', '-7.00', '-7.00', 'no'),
(90, 'CA', 'America/Edmonton', '-7.00', '-6.00', '-7.00', 'no'),
(91, 'CA', 'America/Glace_Bay', '-4.00', '-3.00', '-4.00', 'no'),
(92, 'CA', 'America/Goose_Bay', '-4.00', '-3.00', '-4.00', 'no'),
(93, 'CA', 'America/Halifax', '-4.00', '-3.00', '-4.00', 'no'),
(94, 'CA', 'America/Inuvik', '-7.00', '-6.00', '-7.00', 'no'),
(95, 'CA', 'America/Iqaluit', '-5.00', '-4.00', '-5.00', 'no'),
(96, 'CA', 'America/Moncton', '-4.00', '-3.00', '-4.00', 'no'),
(97, 'CA', 'America/Montreal', '-5.00', '-4.00', '-5.00', 'no'),
(98, 'CA', 'America/Nipigon', '-5.00', '-4.00', '-5.00', 'no'),
(99, 'CA', 'America/Pangnirtung', '-5.00', '-4.00', '-5.00', 'no'),
(100, 'CA', 'America/Rainy_River', '-6.00', '-5.00', '-6.00', 'no'),
(101, 'CA', 'America/Rankin_Inlet', '-6.00', '-5.00', '-6.00', 'no'),
(102, 'CA', 'America/Regina', '-6.00', '-6.00', '-6.00', 'no'),
(103, 'CA', 'America/Resolute', '-6.00', '-5.00', '-6.00', 'no'),
(104, 'CA', 'America/St_Johns', '-3.50', '-2.50', '-3.50', 'no'),
(105, 'CA', 'America/Swift_Current', '-6.00', '-6.00', '-6.00', 'no'),
(106, 'CA', 'America/Thunder_Bay', '-5.00', '-4.00', '-5.00', 'no'),
(107, 'CA', 'America/Toronto', '-5.00', '-4.00', '-5.00', 'no'),
(108, 'CA', 'America/Vancouver', '-8.00', '-7.00', '-8.00', 'no'),
(109, 'CA', 'America/Whitehorse', '-8.00', '-7.00', '-8.00', 'no'),
(110, 'CA', 'America/Winnipeg', '-6.00', '-5.00', '-6.00', 'no'),
(111, 'CA', 'America/Yellowknife', '-7.00', '-6.00', '-7.00', 'no'),
(112, 'CC', 'Indian/Cocos', '6.50', '6.50', '6.50', 'no'),
(113, 'CD', 'Africa/Kinshasa', '1.00', '1.00', '1.00', 'no'),
(114, 'CD', 'Africa/Lubumbashi', '2.00', '2.00', '2.00', 'no'),
(115, 'CF', 'Africa/Bangui', '1.00', '1.00', '1.00', 'no'),
(116, 'CG', 'Africa/Brazzaville', '1.00', '1.00', '1.00', 'no'),
(117, 'CH', 'Europe/Zurich', '1.00', '2.00', '1.00', 'no'),
(118, 'CI', 'Africa/Abidjan', '0.00', '0.00', '0.00', 'no'),
(119, 'CK', 'Pacific/Rarotonga', '-10.00', '-10.00', '-10.00', 'no'),
(120, 'CL', 'America/Santiago', '-3.00', '-4.00', '-4.00', 'no'),
(121, 'CL', 'Pacific/Easter', '-5.00', '-6.00', '-6.00', 'no'),
(122, 'CM', 'Africa/Douala', '1.00', '1.00', '1.00', 'no'),
(123, 'CN', 'Asia/Chongqing', '8.00', '8.00', '8.00', 'no'),
(124, 'CN', 'Asia/Harbin', '8.00', '8.00', '8.00', 'no'),
(125, 'CN', 'Asia/Kashgar', '8.00', '8.00', '8.00', 'no'),
(126, 'CN', 'Asia/Shanghai', '8.00', '8.00', '8.00', 'no'),
(127, 'CN', 'Asia/Urumqi', '8.00', '8.00', '8.00', 'no'),
(128, 'CO', 'America/Bogota', '-5.00', '-5.00', '-5.00', 'no'),
(129, 'CR', 'America/Costa_Rica', '-6.00', '-6.00', '-6.00', 'no'),
(130, 'CU', 'America/Havana', '-5.00', '-4.00', '-5.00', 'no'),
(131, 'CV', 'Atlantic/Cape_Verde', '-1.00', '-1.00', '-1.00', 'no'),
(132, 'CW', 'America/Curacao', '-4.00', '-4.00', '-4.00', 'no'),
(133, 'CX', 'Indian/Christmas', '7.00', '7.00', '7.00', 'no'),
(134, 'CY', 'Asia/Nicosia', '2.00', '3.00', '2.00', 'no'),
(135, 'CZ', 'Europe/Prague', '1.00', '2.00', '1.00', 'no'),
(136, 'DE', 'Europe/Berlin', '1.00', '2.00', '1.00', 'no'),
(137, 'DE', 'Europe/Busingen', '1.00', '2.00', '1.00', 'no'),
(138, 'DJ', 'Africa/Djibouti', '3.00', '3.00', '3.00', 'no'),
(139, 'DK', 'Europe/Copenhagen', '1.00', '2.00', '1.00', 'no'),
(140, 'DM', 'America/Dominica', '-4.00', '-4.00', '-4.00', 'no'),
(141, 'DO', 'America/Santo_Domingo', '-4.00', '-4.00', '-4.00', 'no'),
(142, 'DZ', 'Africa/Algiers', '1.00', '1.00', '1.00', 'no'),
(143, 'EC', 'America/Guayaquil', '-5.00', '-5.00', '-5.00', 'no'),
(144, 'EC', 'Pacific/Galapagos', '-6.00', '-6.00', '-6.00', 'no'),
(145, 'EE', 'Europe/Tallinn', '2.00', '3.00', '2.00', 'no'),
(146, 'EG', 'Africa/Cairo', '2.00', '2.00', '2.00', 'no'),
(147, 'EH', 'Africa/El_Aaiun', '0.00', '0.00', '0.00', 'no'),
(148, 'ER', 'Africa/Asmara', '3.00', '3.00', '3.00', 'no'),
(149, 'ES', 'Africa/Ceuta', '1.00', '2.00', '1.00', 'no'),
(150, 'ES', 'Atlantic/Canary', '0.00', '1.00', '0.00', 'no'),
(151, 'ES', 'Europe/Madrid', '1.00', '2.00', '1.00', 'no'),
(152, 'ET', 'Africa/Addis_Ababa', '3.00', '3.00', '3.00', 'no'),
(153, 'FI', 'Europe/Helsinki', '2.00', '3.00', '2.00', 'no'),
(154, 'FJ', 'Pacific/Fiji', '13.00', '12.00', '12.00', 'no'),
(155, 'FK', 'Atlantic/Stanley', '-3.00', '-3.00', '-3.00', 'no'),
(156, 'FM', 'Pacific/Chuuk', '10.00', '10.00', '10.00', 'no'),
(157, 'FM', 'Pacific/Kosrae', '11.00', '11.00', '11.00', 'no'),
(158, 'FM', 'Pacific/Pohnpei', '11.00', '11.00', '11.00', 'no'),
(159, 'FO', 'Atlantic/Faroe', '0.00', '1.00', '0.00', 'no'),
(160, 'FR', 'Europe/Paris', '1.00', '2.00', '1.00', 'no'),
(161, 'GA', 'Africa/Libreville', '1.00', '1.00', '1.00', 'no'),
(162, 'GB', 'Europe/London', '0.00', '1.00', '0.00', 'no'),
(163, 'GD', 'America/Grenada', '-4.00', '-4.00', '-4.00', 'no'),
(164, 'GE', 'Asia/Tbilisi', '4.00', '4.00', '4.00', 'no'),
(165, 'GF', 'America/Cayenne', '-3.00', '-3.00', '-3.00', 'no'),
(166, 'GG', 'Europe/Guernsey', '0.00', '1.00', '0.00', 'no'),
(167, 'GH', 'Africa/Accra', '0.00', '0.00', '0.00', 'no'),
(168, 'GI', 'Europe/Gibraltar', '1.00', '2.00', '1.00', 'no'),
(169, 'GL', 'America/Danmarkshavn', '0.00', '0.00', '0.00', 'no'),
(170, 'GL', 'America/Godthab', '-3.00', '-2.00', '-3.00', 'no'),
(171, 'GL', 'America/Scoresbysund', '-1.00', '0.00', '-1.00', 'no'),
(172, 'GL', 'America/Thule', '-4.00', '-3.00', '-4.00', 'no'),
(173, 'GM', 'Africa/Banjul', '0.00', '0.00', '0.00', 'no'),
(174, 'GN', 'Africa/Conakry', '0.00', '0.00', '0.00', 'no'),
(175, 'GP', 'America/Guadeloupe', '-4.00', '-4.00', '-4.00', 'no'),
(176, 'GQ', 'Africa/Malabo', '1.00', '1.00', '1.00', 'no'),
(177, 'GR', 'Europe/Athens', '2.00', '3.00', '2.00', 'no'),
(178, 'GS', 'Atlantic/South_Georgia', '-2.00', '-2.00', '-2.00', 'no'),
(179, 'GT', 'America/Guatemala', '-6.00', '-6.00', '-6.00', 'no'),
(180, 'GU', 'Pacific/Guam', '10.00', '10.00', '10.00', 'no'),
(181, 'GW', 'Africa/Bissau', '0.00', '0.00', '0.00', 'no'),
(182, 'GY', 'America/Guyana', '-4.00', '-4.00', '-4.00', 'no'),
(183, 'HK', 'Asia/Hong_Kong', '8.00', '8.00', '8.00', 'no'),
(184, 'HN', 'America/Tegucigalpa', '-6.00', '-6.00', '-6.00', 'no'),
(185, 'HR', 'Europe/Zagreb', '1.00', '2.00', '1.00', 'no'),
(186, 'HT', 'America/Port-au-Prince', '-5.00', '-4.00', '-5.00', 'no'),
(187, 'HU', 'Europe/Budapest', '1.00', '2.00', '1.00', 'no'),
(188, 'ID', 'Asia/Jakarta', '7.00', '7.00', '7.00', 'no'),
(189, 'ID', 'Asia/Jayapura', '9.00', '9.00', '9.00', 'no'),
(190, 'ID', 'Asia/Makassar', '8.00', '8.00', '8.00', 'no'),
(191, 'ID', 'Asia/Pontianak', '7.00', '7.00', '7.00', 'no'),
(192, 'IE', 'Europe/Dublin', '0.00', '1.00', '0.00', 'no'),
(193, 'IL', 'Asia/Jerusalem', '2.00', '3.00', '2.00', 'no'),
(194, 'IM', 'Europe/Isle_of_Man', '0.00', '1.00', '0.00', 'no'),
(195, 'IN', 'Asia/Kolkata', '5.50', '5.50', '5.50', 'no'),
(196, 'IO', 'Indian/Chagos', '6.00', '6.00', '6.00', 'no'),
(197, 'IQ', 'Asia/Baghdad', '3.00', '3.00', '3.00', 'no'),
(198, 'IR', 'Asia/Tehran', '3.50', '4.50', '3.50', 'no'),
(199, 'IS', 'Atlantic/Reykjavik', '0.00', '0.00', '0.00', 'no'),
(200, 'IT', 'Europe/Rome', '1.00', '2.00', '1.00', 'no'),
(201, 'JE', 'Europe/Jersey', '0.00', '1.00', '0.00', 'no'),
(202, 'JM', 'America/Jamaica', '-5.00', '-5.00', '-5.00', 'no'),
(203, 'JO', 'Asia/Amman', '2.00', '3.00', '2.00', 'no'),
(204, 'JP', 'Asia/Tokyo', '9.00', '9.00', '9.00', 'no'),
(205, 'KE', 'Africa/Nairobi', '3.00', '3.00', '3.00', 'no'),
(206, 'KG', 'Asia/Bishkek', '6.00', '6.00', '6.00', 'no'),
(207, 'KH', 'Asia/Phnom_Penh', '7.00', '7.00', '7.00', 'no'),
(208, 'KI', 'Pacific/Enderbury', '13.00', '13.00', '13.00', 'no'),
(209, 'KI', 'Pacific/Kiritimati', '14.00', '14.00', '14.00', 'no'),
(210, 'KI', 'Pacific/Tarawa', '12.00', '12.00', '12.00', 'no'),
(211, 'KM', 'Indian/Comoro', '3.00', '3.00', '3.00', 'no'),
(212, 'KN', 'America/St_Kitts', '-4.00', '-4.00', '-4.00', 'no'),
(213, 'KP', 'Asia/Pyongyang', '9.00', '9.00', '9.00', 'no'),
(214, 'KR', 'Asia/Seoul', '9.00', '9.00', '9.00', 'no'),
(215, 'KW', 'Asia/Kuwait', '3.00', '3.00', '3.00', 'no'),
(216, 'KY', 'America/Cayman', '-5.00', '-5.00', '-5.00', 'no'),
(217, 'KZ', 'Asia/Almaty', '6.00', '6.00', '6.00', 'no'),
(218, 'KZ', 'Asia/Aqtau', '5.00', '5.00', '5.00', 'no'),
(219, 'KZ', 'Asia/Aqtobe', '5.00', '5.00', '5.00', 'no'),
(220, 'KZ', 'Asia/Oral', '5.00', '5.00', '5.00', 'no'),
(221, 'KZ', 'Asia/Qyzylorda', '6.00', '6.00', '6.00', 'no'),
(222, 'LA', 'Asia/Vientiane', '7.00', '7.00', '7.00', 'no'),
(223, 'LB', 'Asia/Beirut', '2.00', '3.00', '2.00', 'no'),
(224, 'LC', 'America/St_Lucia', '-4.00', '-4.00', '-4.00', 'no'),
(225, 'LI', 'Europe/Vaduz', '1.00', '2.00', '1.00', 'no'),
(226, 'LK', 'Asia/Colombo', '5.50', '5.50', '5.50', 'no'),
(227, 'LR', 'Africa/Monrovia', '0.00', '0.00', '0.00', 'no'),
(228, 'LS', 'Africa/Maseru', '2.00', '2.00', '2.00', 'no'),
(229, 'LT', 'Europe/Vilnius', '2.00', '3.00', '2.00', 'no'),
(230, 'LU', 'Europe/Luxembourg', '1.00', '2.00', '1.00', 'no'),
(231, 'LV', 'Europe/Riga', '2.00', '3.00', '2.00', 'no'),
(232, 'LY', 'Africa/Tripoli', '2.00', '2.00', '2.00', 'no'),
(233, 'MA', 'Africa/Casablanca', '0.00', '0.00', '0.00', 'no'),
(234, 'MC', 'Europe/Monaco', '1.00', '2.00', '1.00', 'no'),
(235, 'MD', 'Europe/Chisinau', '2.00', '3.00', '2.00', 'no'),
(236, 'ME', 'Europe/Podgorica', '1.00', '2.00', '1.00', 'no'),
(237, 'MF', 'America/Marigot', '-4.00', '-4.00', '-4.00', 'no'),
(238, 'MG', 'Indian/Antananarivo', '3.00', '3.00', '3.00', 'no'),
(239, 'MH', 'Pacific/Kwajalein', '12.00', '12.00', '12.00', 'no'),
(240, 'MH', 'Pacific/Majuro', '12.00', '12.00', '12.00', 'no'),
(241, 'MK', 'Europe/Skopje', '1.00', '2.00', '1.00', 'no'),
(242, 'ML', 'Africa/Bamako', '0.00', '0.00', '0.00', 'no'),
(243, 'MM', 'Asia/Rangoon', '6.50', '6.50', '6.50', 'no'),
(244, 'MN', 'Asia/Choibalsan', '8.00', '8.00', '8.00', 'no'),
(245, 'MN', 'Asia/Hovd', '7.00', '7.00', '7.00', 'no'),
(246, 'MN', 'Asia/Ulaanbaatar', '8.00', '8.00', '8.00', 'no'),
(247, 'MO', 'Asia/Macau', '8.00', '8.00', '8.00', 'no'),
(248, 'MP', 'Pacific/Saipan', '10.00', '10.00', '10.00', 'no'),
(249, 'MQ', 'America/Martinique', '-4.00', '-4.00', '-4.00', 'no'),
(250, 'MR', 'Africa/Nouakchott', '0.00', '0.00', '0.00', 'no'),
(251, 'MS', 'America/Montserrat', '-4.00', '-4.00', '-4.00', 'no'),
(252, 'MT', 'Europe/Malta', '1.00', '2.00', '1.00', 'no'),
(253, 'MU', 'Indian/Mauritius', '4.00', '4.00', '4.00', 'no'),
(254, 'MV', 'Indian/Maldives', '5.00', '5.00', '5.00', 'no'),
(255, 'MW', 'Africa/Blantyre', '2.00', '2.00', '2.00', 'no'),
(256, 'MX', 'America/Bahia_Banderas', '-6.00', '-5.00', '-6.00', 'no'),
(257, 'MX', 'America/Cancun', '-6.00', '-5.00', '-6.00', 'no'),
(258, 'MX', 'America/Chihuahua', '-7.00', '-6.00', '-7.00', 'no'),
(259, 'MX', 'America/Hermosillo', '-7.00', '-7.00', '-7.00', 'no'),
(260, 'MX', 'America/Matamoros', '-6.00', '-5.00', '-6.00', 'no'),
(261, 'MX', 'America/Mazatlan', '-7.00', '-6.00', '-7.00', 'no'),
(262, 'MX', 'America/Merida', '-6.00', '-5.00', '-6.00', 'no'),
(263, 'MX', 'America/Mexico_City', '-6.00', '-5.00', '-6.00', 'no'),
(264, 'MX', 'America/Monterrey', '-6.00', '-5.00', '-6.00', 'no'),
(265, 'MX', 'America/Ojinaga', '-7.00', '-6.00', '-7.00', 'no'),
(266, 'MX', 'America/Santa_Isabel', '-8.00', '-7.00', '-8.00', 'no'),
(267, 'MX', 'America/Tijuana', '-8.00', '-7.00', '-8.00', 'no'),
(268, 'MY', 'Asia/Kuala_Lumpur', '8.00', '8.00', '8.00', 'no'),
(269, 'MY', 'Asia/Kuching', '8.00', '8.00', '8.00', 'no'),
(270, 'MZ', 'Africa/Maputo', '2.00', '2.00', '2.00', 'no'),
(271, 'NA', 'Africa/Windhoek', '2.00', '1.00', '1.00', 'no'),
(272, 'NC', 'Pacific/Noumea', '11.00', '11.00', '11.00', 'no'),
(273, 'NE', 'Africa/Niamey', '1.00', '1.00', '1.00', 'no'),
(274, 'NF', 'Pacific/Norfolk', '11.50', '11.50', '11.50', 'no'),
(275, 'NG', 'Africa/Lagos', '1.00', '1.00', '1.00', 'no'),
(276, 'NI', 'America/Managua', '-6.00', '-6.00', '-6.00', 'no'),
(277, 'NL', 'Europe/Amsterdam', '1.00', '2.00', '1.00', 'no'),
(278, 'NO', 'Europe/Oslo', '1.00', '2.00', '1.00', 'no'),
(279, 'NP', 'Asia/Kathmandu', '5.75', '5.75', '5.75', 'no'),
(280, 'NR', 'Pacific/Nauru', '12.00', '12.00', '12.00', 'no'),
(281, 'NU', 'Pacific/Niue', '-11.00', '-11.00', '-11.00', 'no'),
(282, 'NZ', 'Pacific/Auckland', '13.00', '12.00', '12.00', 'no'),
(283, 'NZ', 'Pacific/Chatham', '13.75', '12.75', '12.75', 'no'),
(284, 'OM', 'Asia/Muscat', '4.00', '4.00', '4.00', 'no'),
(285, 'PA', 'America/Panama', '-5.00', '-5.00', '-5.00', 'no'),
(286, 'PE', 'America/Lima', '-5.00', '-5.00', '-5.00', 'no'),
(287, 'PF', 'Pacific/Gambier', '-9.00', '-9.00', '-9.00', 'no'),
(288, 'PF', 'Pacific/Marquesas', '-9.50', '-9.50', '-9.50', 'no'),
(289, 'PF', 'Pacific/Tahiti', '-10.00', '-10.00', '-10.00', 'no'),
(290, 'PG', 'Pacific/Port_Moresby', '10.00', '10.00', '10.00', 'no'),
(291, 'PH', 'Asia/Manila', '8.00', '8.00', '8.00', 'no'),
(292, 'PK', 'Asia/Karachi', '5.00', '5.00', '5.00', 'no'),
(293, 'PL', 'Europe/Warsaw', '1.00', '2.00', '1.00', 'no'),
(294, 'PM', 'America/Miquelon', '-3.00', '-2.00', '-3.00', 'no'),
(295, 'PN', 'Pacific/Pitcairn', '-8.00', '-8.00', '-8.00', 'no'),
(296, 'PR', 'America/Puerto_Rico', '-4.00', '-4.00', '-4.00', 'no'),
(297, 'PS', 'Asia/Gaza', '2.00', '3.00', '2.00', 'no'),
(298, 'PS', 'Asia/Hebron', '2.00', '3.00', '2.00', 'no'),
(299, 'PT', 'Atlantic/Azores', '-1.00', '0.00', '-1.00', 'no'),
(300, 'PT', 'Atlantic/Madeira', '0.00', '1.00', '0.00', 'no'),
(301, 'PT', 'Europe/Lisbon', '0.00', '1.00', '0.00', 'no'),
(302, 'PW', 'Pacific/Palau', '9.00', '9.00', '9.00', 'no'),
(303, 'PY', 'America/Asuncion', '-3.00', '-4.00', '-4.00', 'no'),
(304, 'QA', 'Asia/Qatar', '3.00', '3.00', '3.00', 'no'),
(305, 'RE', 'Indian/Reunion', '4.00', '4.00', '4.00', 'no'),
(306, 'RO', 'Europe/Bucharest', '2.00', '3.00', '2.00', 'no'),
(307, 'RS', 'Europe/Belgrade', '1.00', '2.00', '1.00', 'no'),
(308, 'RU', 'Asia/Anadyr', '12.00', '12.00', '12.00', 'no'),
(309, 'RU', 'Asia/Irkutsk', '9.00', '9.00', '9.00', 'no'),
(310, 'RU', 'Asia/Kamchatka', '12.00', '12.00', '12.00', 'no'),
(311, 'RU', 'Asia/Khandyga', '10.00', '10.00', '10.00', 'no'),
(312, 'RU', 'Asia/Krasnoyarsk', '8.00', '8.00', '8.00', 'no'),
(313, 'RU', 'Asia/Magadan', '12.00', '12.00', '12.00', 'no'),
(314, 'RU', 'Asia/Novokuznetsk', '7.00', '7.00', '7.00', 'no'),
(315, 'RU', 'Asia/Novosibirsk', '7.00', '7.00', '7.00', 'no'),
(316, 'RU', 'Asia/Omsk', '7.00', '7.00', '7.00', 'no'),
(317, 'RU', 'Asia/Sakhalin', '11.00', '11.00', '11.00', 'no'),
(318, 'RU', 'Asia/Ust-Nera', '11.00', '11.00', '11.00', 'no'),
(319, 'RU', 'Asia/Vladivostok', '11.00', '11.00', '11.00', 'no'),
(320, 'RU', 'Asia/Yakutsk', '10.00', '10.00', '10.00', 'no'),
(321, 'RU', 'Asia/Yekaterinburg', '6.00', '6.00', '6.00', 'no'),
(322, 'RU', 'Europe/Kaliningrad', '3.00', '3.00', '3.00', 'no'),
(323, 'RU', 'Europe/Moscow', '4.00', '4.00', '4.00', 'no'),
(324, 'RU', 'Europe/Samara', '4.00', '4.00', '4.00', 'no'),
(325, 'RU', 'Europe/Volgograd', '4.00', '4.00', '4.00', 'no'),
(326, 'RW', 'Africa/Kigali', '2.00', '2.00', '2.00', 'no'),
(327, 'SA', 'Asia/Riyadh', '3.00', '3.00', '3.00', 'no'),
(328, 'SB', 'Pacific/Guadalcanal', '11.00', '11.00', '11.00', 'no'),
(329, 'SC', 'Indian/Mahe', '4.00', '4.00', '4.00', 'no'),
(330, 'SD', 'Africa/Khartoum', '3.00', '3.00', '3.00', 'no'),
(331, 'SE', 'Europe/Stockholm', '1.00', '2.00', '1.00', 'no'),
(332, 'SG', 'Asia/Singapore', '8.00', '8.00', '8.00', 'no'),
(333, 'SH', 'Atlantic/St_Helena', '0.00', '0.00', '0.00', 'no'),
(334, 'SI', 'Europe/Ljubljana', '1.00', '2.00', '1.00', 'no'),
(335, 'SJ', 'Arctic/Longyearbyen', '1.00', '2.00', '1.00', 'no'),
(336, 'SK', 'Europe/Bratislava', '1.00', '2.00', '1.00', 'no'),
(337, 'SL', 'Africa/Freetown', '0.00', '0.00', '0.00', 'no'),
(338, 'SM', 'Europe/San_Marino', '1.00', '2.00', '1.00', 'no'),
(339, 'SN', 'Africa/Dakar', '0.00', '0.00', '0.00', 'no'),
(340, 'SO', 'Africa/Mogadishu', '3.00', '3.00', '3.00', 'no'),
(341, 'SR', 'America/Paramaribo', '-3.00', '-3.00', '-3.00', 'no'),
(342, 'SS', 'Africa/Juba', '3.00', '3.00', '3.00', 'no'),
(343, 'ST', 'Africa/Sao_Tome', '0.00', '0.00', '0.00', 'no'),
(344, 'SV', 'America/El_Salvador', '-6.00', '-6.00', '-6.00', 'no'),
(345, 'SX', 'America/Lower_Princes', '-4.00', '-4.00', '-4.00', 'no'),
(346, 'SY', 'Asia/Damascus', '2.00', '3.00', '2.00', 'no'),
(347, 'SZ', 'Africa/Mbabane', '2.00', '2.00', '2.00', 'no'),
(348, 'TC', 'America/Grand_Turk', '-5.00', '-4.00', '-5.00', 'no'),
(349, 'TD', 'Africa/Ndjamena', '1.00', '1.00', '1.00', 'no'),
(350, 'TF', 'Indian/Kerguelen', '5.00', '5.00', '5.00', 'no'),
(351, 'TG', 'Africa/Lome', '0.00', '0.00', '0.00', 'no'),
(352, 'TH', 'Asia/Bangkok', '7.00', '7.00', '7.00', 'no'),
(353, 'TJ', 'Asia/Dushanbe', '5.00', '5.00', '5.00', 'no'),
(354, 'TK', 'Pacific/Fakaofo', '13.00', '13.00', '13.00', 'no'),
(355, 'TL', 'Asia/Dili', '9.00', '9.00', '9.00', 'no'),
(356, 'TM', 'Asia/Ashgabat', '5.00', '5.00', '5.00', 'no'),
(357, 'TN', 'Africa/Tunis', '1.00', '1.00', '1.00', 'no'),
(358, 'TO', 'Pacific/Tongatapu', '13.00', '13.00', '13.00', 'no'),
(359, 'TR', 'Europe/Istanbul', '2.00', '3.00', '2.00', 'no'),
(360, 'TT', 'America/Port_of_Spain', '-4.00', '-4.00', '-4.00', 'no'),
(361, 'TV', 'Pacific/Funafuti', '12.00', '12.00', '12.00', 'no'),
(362, 'TW', 'Asia/Taipei', '8.00', '8.00', '8.00', 'no'),
(363, 'TZ', 'Africa/Dar_es_Salaam', '3.00', '3.00', '3.00', 'no'),
(364, 'UA', 'Europe/Kiev', '2.00', '3.00', '2.00', 'no'),
(365, 'UA', 'Europe/Simferopol', '2.00', '4.00', '4.00', 'no'),
(366, 'UA', 'Europe/Uzhgorod', '2.00', '3.00', '2.00', 'no'),
(367, 'UA', 'Europe/Zaporozhye', '2.00', '3.00', '2.00', 'no'),
(368, 'UG', 'Africa/Kampala', '3.00', '3.00', '3.00', 'no'),
(369, 'UM', 'Pacific/Johnston', '-10.00', '-10.00', '-10.00', 'no'),
(370, 'UM', 'Pacific/Midway', '-11.00', '-11.00', '-11.00', 'no'),
(371, 'UM', 'Pacific/Wake', '12.00', '12.00', '12.00', 'no'),
(372, 'US', 'America/Adak', '-10.00', '-9.00', '-10.00', 'no'),
(373, 'US', 'America/Anchorage', '-9.00', '-8.00', '-9.00', 'no'),
(374, 'US', 'America/Boise', '-7.00', '-6.00', '-7.00', 'no'),
(375, 'US', 'America/Chicago', '-6.00', '-5.00', '-6.00', 'no'),
(376, 'US', 'America/Denver', '-7.00', '-6.00', '-7.00', 'no'),
(377, 'US', 'America/Detroit', '-5.00', '-4.00', '-5.00', 'no'),
(378, 'US', 'America/Indiana/Indianapolis', '-5.00', '-4.00', '-5.00', 'no'),
(379, 'US', 'America/Indiana/Knox', '-6.00', '-5.00', '-6.00', 'no'),
(380, 'US', 'America/Indiana/Marengo', '-5.00', '-4.00', '-5.00', 'no'),
(381, 'US', 'America/Indiana/Petersburg', '-5.00', '-4.00', '-5.00', 'no'),
(382, 'US', 'America/Indiana/Tell_City', '-6.00', '-5.00', '-6.00', 'no'),
(383, 'US', 'America/Indiana/Vevay', '-5.00', '-4.00', '-5.00', 'no'),
(384, 'US', 'America/Indiana/Vincennes', '-5.00', '-4.00', '-5.00', 'no'),
(385, 'US', 'America/Indiana/Winamac', '-5.00', '-4.00', '-5.00', 'no'),
(386, 'US', 'America/Juneau', '-9.00', '-8.00', '-9.00', 'no'),
(387, 'US', 'America/Kentucky/Louisville', '-5.00', '-4.00', '-5.00', 'no'),
(388, 'US', 'America/Kentucky/Monticello', '-5.00', '-4.00', '-5.00', 'no'),
(389, 'US', 'America/Los_Angeles', '-8.00', '-7.00', '-8.00', 'no'),
(390, 'US', 'America/Menominee', '-6.00', '-5.00', '-6.00', 'no'),
(391, 'US', 'America/Metlakatla', '-8.00', '-8.00', '-8.00', 'no'),
(392, 'US', 'America/New_York', '-5.00', '-4.00', '-5.00', 'no'),
(393, 'US', 'America/Nome', '-9.00', '-8.00', '-9.00', 'no'),
(394, 'US', 'America/North_Dakota/Beulah', '-6.00', '-5.00', '-6.00', 'no'),
(395, 'US', 'America/North_Dakota/Center', '-6.00', '-5.00', '-6.00', 'no'),
(396, 'US', 'America/North_Dakota/New_Salem', '-6.00', '-5.00', '-6.00', 'no'),
(397, 'US', 'America/Phoenix', '-7.00', '-7.00', '-7.00', 'no'),
(398, 'US', 'America/Shiprock', '-7.00', '-6.00', '-7.00', 'no'),
(399, 'US', 'America/Sitka', '-9.00', '-8.00', '-9.00', 'no'),
(400, 'US', 'America/Yakutat', '-9.00', '-8.00', '-9.00', 'no'),
(401, 'US', 'Pacific/Honolulu', '-10.00', '-10.00', '-10.00', 'no'),
(402, 'UY', 'America/Montevideo', '-2.00', '-3.00', '-3.00', 'no'),
(403, 'UZ', 'Asia/Samarkand', '5.00', '5.00', '5.00', 'no'),
(404, 'UZ', 'Asia/Tashkent', '5.00', '5.00', '5.00', 'no'),
(405, 'VA', 'Europe/Vatican', '1.00', '2.00', '1.00', 'no'),
(406, 'VC', 'America/St_Vincent', '-4.00', '-4.00', '-4.00', 'no'),
(407, 'VE', 'America/Caracas', '-4.50', '-4.50', '-4.50', 'no'),
(408, 'VG', 'America/Tortola', '-4.00', '-4.00', '-4.00', 'no'),
(409, 'VI', 'America/St_Thomas', '-4.00', '-4.00', '-4.00', 'no'),
(410, 'VN', 'Asia/Ho_Chi_Minh', '7.00', '7.00', '7.00', 'no'),
(411, 'VU', 'Pacific/Efate', '11.00', '11.00', '11.00', 'no'),
(412, 'WF', 'Pacific/Wallis', '12.00', '12.00', '12.00', 'no'),
(413, 'WS', 'Pacific/Apia', '14.00', '13.00', '13.00', 'no'),
(414, 'YE', 'Asia/Aden', '3.00', '3.00', '3.00', 'no'),
(415, 'YT', 'Indian/Mayotte', '3.00', '3.00', '3.00', 'no'),
(416, 'ZA', 'Africa/Johannesburg', '2.00', '2.00', '2.00', 'no'),
(417, 'ZM', 'Africa/Lusaka', '2.00', '2.00', '2.00', 'no'),
(418, 'ZW', 'Africa/Harare', '2.00', '2.00', '2.00', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transcation_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `transcation_type` int(11) DEFAULT NULL COMMENT '1=service order, 2=Withdraw, 3= balance add, 4 = balance subtract, 5 = pacakge purchase',
  `user_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` double(8,2) DEFAULT NULL,
  `tax` float(8,2) DEFAULT '0.00',
  `pre_balance` double(8,2) DEFAULT NULL,
  `after_balance` double(8,2) DEFAULT NULL,
  `gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 -> banned or deactive, 1 -> active',
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_products`
--

CREATE TABLE `wishlist_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_services`
--

CREATE TABLE `wishlist_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `withdraw_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payable_amount` float(8,2) NOT NULL DEFAULT '0.00',
  `total_charge` float(8,2) NOT NULL DEFAULT '0.00',
  `additional_reference` text COLLATE utf8mb4_unicode_ci,
  `feilds` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_inputs`
--

CREATE TABLE `withdraw_method_inputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `withdraw_payment_method_id` bigint(20) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1-text, 2-select, 3-checkbox, 4-textarea, 5-datepicker, 6-timepicker, 7-number',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-required, 0- optional',
  `order_number` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_method_inputs`
--

INSERT INTO `withdraw_method_inputs` (`id`, `withdraw_payment_method_id`, `type`, `label`, `name`, `placeholder`, `required`, `order_number`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 'Account Number', 'Account_Number', 'Enter your account number', 1, 1, '2023-12-18 05:30:32', '2023-12-18 05:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_options`
--

CREATE TABLE `withdraw_method_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `withdraw_method_input_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_payment_methods`
--

CREATE TABLE `withdraw_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `min_limit` double(8,2) DEFAULT NULL,
  `max_limit` double(8,2) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `fixed_charge` float(8,2) DEFAULT '0.00',
  `percentage_charge` float(8,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_payment_methods`
--

INSERT INTO `withdraw_payment_methods` (`id`, `min_limit`, `max_limit`, `name`, `status`, `fixed_charge`, `percentage_charge`, `created_at`, `updated_at`) VALUES
(6, 20.00, 300.00, 'Perfect Money', 1, 5.00, 5.00, '2023-11-04 08:52:38', '2023-12-18 05:29:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_role_id_foreign` (`role_id`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_extends`
--
ALTER TABLE `basic_extends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_settings`
--
ALTER TABLE `basic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cookie_alerts_language_id_foreign` (`language_id`);

--
-- Indexes for table `cta_section_infos`
--
ALTER TABLE `cta_section_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_language_id_foreign` (`language_id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `footer_texts_language_id_foreign` (`language_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_inputs`
--
ALTER TABLE `form_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_statics`
--
ALTER TABLE `hero_statics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_builders`
--
ALTER TABLE `menu_builders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_gateways`
--
ALTER TABLE `online_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_contents_language_id_foreign` (`language_id`),
  ADD KEY `page_contents_page_id_foreign` (`page_id`);

--
-- Indexes for table `page_headings`
--
ALTER TABLE `page_headings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_headings_language_id_foreign` (`language_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `popups_language_id_foreign` (`language_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_informations`
--
ALTER TABLE `post_informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quick_links_language_id_foreign` (`language_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_titles`
--
ALTER TABLE `section_titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_infos`
--
ALTER TABLE `seller_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seos`
--
ALTER TABLE `seos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seos_language_id_foreign` (`language_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_addons`
--
ALTER TABLE `service_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `service_contents`
--
ALTER TABLE `service_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_faqs`
--
ALTER TABLE `service_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_orders`
--
ALTER TABLE `service_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_order_messages`
--
ALTER TABLE `service_order_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_subcategories`
--
ALTER TABLE `service_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_subcategories_language_id_foreign` (`language_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_medias`
--
ALTER TABLE `social_medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_id_unique` (`email_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_conversations`
--
ALTER TABLE `ticket_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_address_unique` (`email_address`) USING BTREE,
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `wishlist_products`
--
ALTER TABLE `wishlist_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist_services`
--
ALTER TABLE `wishlist_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `basic_extends`
--
ALTER TABLE `basic_extends`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `basic_settings`
--
ALTER TABLE `basic_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cta_section_infos`
--
ALTER TABLE `cta_section_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_contents`
--
ALTER TABLE `footer_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `form_inputs`
--
ALTER TABLE `form_inputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hero_statics`
--
ALTER TABLE `hero_statics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_builders`
--
ALTER TABLE `menu_builders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `online_gateways`
--
ALTER TABLE `online_gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `page_contents`
--
ALTER TABLE `page_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `page_headings`
--
ALTER TABLE `page_headings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `popups`
--
ALTER TABLE `popups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_informations`
--
ALTER TABLE `post_informations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_links`
--
ALTER TABLE `quick_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section_titles`
--
ALTER TABLE `section_titles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_infos`
--
ALTER TABLE `seller_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seos`
--
ALTER TABLE `seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_addons`
--
ALTER TABLE `service_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `service_contents`
--
ALTER TABLE `service_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_faqs`
--
ALTER TABLE `service_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_orders`
--
ALTER TABLE `service_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_order_messages`
--
ALTER TABLE `service_order_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_packages`
--
ALTER TABLE `service_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_subcategories`
--
ALTER TABLE `service_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_medias`
--
ALTER TABLE `social_medias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ticket_conversations`
--
ALTER TABLE `ticket_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist_products`
--
ALTER TABLE `wishlist_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist_services`
--
ALTER TABLE `wishlist_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD CONSTRAINT `blog_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD CONSTRAINT `cookie_alerts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD CONSTRAINT `footer_texts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD CONSTRAINT `page_contents_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `page_contents_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `popups`
--
ALTER TABLE `popups`
  ADD CONSTRAINT `popups_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD CONSTRAINT `quick_links_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seos`
--
ALTER TABLE `seos`
  ADD CONSTRAINT `seos_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_subcategories`
--
ALTER TABLE `service_subcategories`
  ADD CONSTRAINT `service_subcategories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
