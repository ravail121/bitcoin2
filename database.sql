-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2019 at 02:55 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cc_lbc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `login_time` datetime DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `mobile`, `image`, `status`, `login_time`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@admin.com', '0123456789', 'admin_1531922370.jpg', 1, '2018-05-04 14:36:07', '$2y$10$bYa23M2DS8SHgJufrKnIPOG6Mxg4wDduuAxTKVEUhtLbOMDIMV.bi', 'DiqL89IB8KElngPTl7SQii2c8exBvmEq3E6PAHRcbeH8fy8a5WLiH4S6QStP', '2018-03-26 06:08:23', '2018-07-18 13:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertise_deals`
--

CREATE TABLE `advertise_deals` (
  `id` int(10) UNSIGNED NOT NULL,
  `add_type` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `trans_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usd_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `basic_settings`
--

CREATE TABLE `basic_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `sitename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usd_rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_sym` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration` tinyint(1) NOT NULL DEFAULT '0',
  `email_verification` tinyint(1) NOT NULL DEFAULT '0',
  `sms_verification` tinyint(1) NOT NULL DEFAULT '0',
  `email_notification` tinyint(1) NOT NULL DEFAULT '0',
  `sms_notification` tinyint(4) NOT NULL DEFAULT '0',
  `withdraw_status` tinyint(1) NOT NULL DEFAULT '0',
  `withdraw_charge` double DEFAULT '0',
  `captcha` tinyint(4) NOT NULL DEFAULT '0',
  `decimal` int(2) NOT NULL,
  `refcom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `about` blob NOT NULL,
  `policy` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_map` text COLLATE utf8mb4_unicode_ci,
  `terms` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `copyright` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_comment` text COLLATE utf8mb4_unicode_ci,
  `trx_charge` int(11) NOT NULL,
  `banner_title` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_sub_title` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `basic_settings`
--

INSERT INTO `basic_settings` (`id`, `sitename`, `color`, `phone`, `email`, `address`, `currency`, `usd_rate`, `currency_sym`, `registration`, `email_verification`, `sms_verification`, `email_notification`, `sms_notification`, `withdraw_status`, `withdraw_charge`, `captcha`, `decimal`, `refcom`, `about`, `policy`, `google_map`, `terms`, `copyright`, `fb_comment`, `trx_charge`, `banner_title`, `banner_sub_title`, `created_at`, `updated_at`) VALUES
(1, 'LBC', 'ffffff', '+880 123 456 7890', 'do-not-reply@waredex.com', 'Company Location, Country', 'BDT', '84.21', 'Tk', 1, 0, 0, 1, 0, 0, 12, 0, 8, '0', 0x3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20636f6c6f723a2072676228302c20302c2030293b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e49742069732061206c6f6e672065737461626c6973686564206661637420746861742061207265616465722077696c6c206265206469737472616374656420627920746865207265616461626c6520636f6e74656e74206f6620612070616765207768656e206c6f6f6b696e6720617420697473206c61796f75742e2054686520706f696e74206f66207573696e67204c6f72656d20497073756d2069732074686174206974206861732061206d6f72652d6f722d6c657373206e6f726d616c20646973747269627574696f6e206f66206c6574746572732c206173206f70706f73656420746f207573696e672027436f6e74656e7420686572652c20636f6e74656e742068657265272c206d616b696e67206974206c6f6f6b206c696b65207265616461626c6520456e676c6973682e204d616e79206465736b746f70207075626c697368696e67207061636b6167657320616e6420776562207061676520656469746f7273206e6f7720757365204c6f72656d20497073756d2061732074686569722064656661756c74206d6f64656c20746578742c20616e6420612073656172636820666f7220276c6f72656d20697073756d272077696c6c20756e636f766572206d616e7920776562207369746573207374696c6c20696e20746865697220696e66616e63792e20566172696f75732076657273696f6e7320686176652065766f6c766564206f766572207468652079656172732c20736f6d6574696d6573206279206163636964656e742c20736f6d6574696d6573206f6e20707572706f73652028696e6a65637465642068756d6f757220616e6420746865206c696b65293c2f703e3c6469763e3c62723e3c2f6469763e, '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga vel \r\nlaudantium, itaque repellendus fugiat nostrum, nulla libero aliquam, \r\nducimus aut quisquam. Minus quod aperiam blanditiis explicabo commodi \r\nperspiciatis aut facere, voluptatum ad sit. Expedita totam dignissimos \r\nadipisci alias reprehenderit nam culpa soluta cum veniam et aut odio \r\nexcepturi perferendis maiores possimus impedit officia hic incidunt \r\nquas, quidem ea sint tempore accusamus magni. Quidem sunt placeat, \r\ndolorem iure dicta asperiores accusantium quaerat cum veritatis. Facilis\r\n numquam laboriosam delectus sit quam et deleniti, blanditiis itaque \r\nullam nobis!!!</div><div><br></div><div><br></div><div>earum laborum \r\niste, eum. Qui nihil quod, praesentium minima dolorum deleniti \r\nrepudiandae officia corrupti perspiciatis earum distinctio, omnis \r\nfacilis, quibusdam eligendi minus delectus id? Ex voluptate fuga \r\nquibusdam, molestiae quam, iste cum, non sed veritatis alias eveniet. \r\nDolore deleniti natus officiis voluptatibus animi vel sapiente recusand</div><div><br></div><div align=\"center\">ae\r\n porro debitis illum aliquam, expedita! Debitis alias, fugit nostrum \r\nexpedita. Ea architecto libero et, nobis nulla accusamus sequi eveniet \r\nut eius adipisci sit commodi qui eaque voluptatum culpa doloremque atque\r\n vitae pariatur recusandae labore illum aspernatur corrupti. Fugiat \r\nratione sequi minima iure, debitis corrupti at amet enim voluptatem, \r\nrepellat vel perferendis. Perspiciatis animi, natus tempore alias \r\nsimilique quod vero a, impedit ratione quae nisi laborum, eos rerum \r\nerror esse repellendus suscipit cupiditate totam magni accusantium \r\ndicta, explicabo? Omnis vero quisquam blanditiis, amet numquam optio cu</div><div><br></div><div><b>lpa, commodi ea, aspernatur qui accusamus unde.</b></div><div><br></div><ul><li> Repellat laboriosam aspernatur fugiat, quisquam tempore</li><li> nulla tempora dicta animi aliquid ab repelle</li><li>ndus dolore, deserunt, accusamus cumque volupta</li><li>tibus magni corrupti quod. Consequuntur rem deserunt eaque </li><li>enim fuga perferendis iste voluptate sequi. </li><li>Necessitatibus accusamus, omnis hic rerum possim</li><li>us doloremque recusandae soluta quaerat explicab</li></ul><div><br></div><div><br></div><div>o\r\n dolore laboriosam, natus molestias, dicta ad excepturi? Amet non est at\r\n ex, quidem, facere deserunt corrupti, suscipit reprehenderit dolor a \r\nminus animi laboriosam atque nesciunt fuga perspiciatis accusamus \r\nconsectetur ullam. Molestias reprehenderit non quidem magnam, culpa qui \r\nsimilique illum distinctio assumenda aliquid odit molestiae obcaecati \r\nexcepturi, nostrum placeat consequuntur vitae. Inventore nobis harum \r\nsunt doloremque facilis, a est ab reprehenderit aut rem ipsam similique \r\nplaceat error modi non suscipit, accusantium voluptas iste odio minus. \r\nQuis dolore nam inventore delectus molestias facere earum. Neque ullam, \r\nimpedit quasi mollitia voluptate, cupiditate vel aut provident quos \r\nveritatis consequatur quisquam ducimus cum. Consectetur eos perspiciatis\r\n obcaecati earum. Labore, ipsam, cum. Nostrum quia blanditiis a, \r\nrecusandae iste. Incidunt labo</div><div><br></div><div>re autem qui \r\nveritatis explicabo est harum laborum. Mollitia ullam unde harum, ut \r\nipsum doloremque nisi culpa repudiandae eveniet quo fuga laudantium, \r\nfacilis deleniti, nobis laborum. Qui animi temporibus eveniet, sint quod\r\n neque omnis nihil adipisci quam corporis esse velit, aliquam nisi a \r\nfacilis ipsa voluptatibus magnam nulla! Illum obcaecati vel, facere. Est\r\n quisquam aspernatur, qui dolorum nihil aut excepturi consequuntur quod \r\nsoluta suscipit cupiditate veritatis. Fugiat culpa saepe eligendi \r\narchitecto hic alias ex incidunt. Iure ipsa repellat ex! Totam earum, \r\nquod quibusdam nisi hic, placeat nobis et eveniet fugiat consectetur \r\nrecusandae, magni ea asperiores dolor saepe, aliquam sed. Voluptate \r\nperspiciatis, error consequuntur animi nobis totam omnis et officia, \r\ndistinctio culpa voluptas suscipit possimus dignissimos hic ea fugiat \r\nvoluptatibus fuga adipisci sit iure?</div><br><br>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3648.4380622262165!2d90.38837081543295!3d23.8740801899561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c416ec497185%3A0x628e52943c152c40!2sZam+Zam+Tower!5e0!3m2!1sen!2sbd!4v1540977841897\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga vel laudantium, itaque repellendus fugiat nostrum, nulla libero aliquam, ducimus aut quisquam. Minus quod aperiam blanditiis explicabo commodi perspiciatis aut facere, voluptatum ad sit. Expedita totam dignissimos adipisci alias reprehenderit nam culpa soluta cum veniam et aut odio excepturi perferendis maiores possimus impedit officia hic incidunt quas, quidem ea sint tempore accusamus magni. Quidem sunt placeat, dolorem iure dicta asperiores accusantium quaerat cum veritatis. Facilis numquam laboriosam delectus sit quam et deleniti, blanditiis itaque ullam nobis!!!</div><div><br></div><div><br></div><div>earum laborum iste, eum. Qui nihil quod, praesentium minima dolorum deleniti repudiandae officia corrupti perspiciatis earum distinctio, omnis facilis, quibusdam eligendi minus delectus id? Ex voluptate fuga quibusdam, molestiae quam, iste cum, non sed veritatis alias eveniet. Dolore deleniti natus officiis voluptatibus animi vel sapiente recusand</div><div><br></div><div align=\"center\">ae porro debitis illum aliquam, expedita! Debitis alias, fugit nostrum expedita. Ea architecto libero et, nobis nulla accusamus sequi eveniet ut eius adipisci sit commodi qui eaque voluptatum culpa doloremque atque vitae pariatur recusandae labore illum aspernatur corrupti. Fugiat ratione sequi minima iure, debitis corrupti at amet enim voluptatem, repellat vel perferendis. Perspiciatis animi, natus tempore alias similique quod vero a, impedit ratione quae nisi laborum, eos rerum error esse repellendus suscipit cupiditate totam magni accusantium dicta, explicabo? Omnis vero quisquam blanditiis, amet numquam optio cu</div><div><br></div><div><b>lpa, commodi ea, aspernatur qui accusamus unde.</b></div><div><br></div><ul><li> Repellat laboriosam aspernatur fugiat, quisquam tempore</li><li> nulla tempora dicta animi aliquid ab repelle</li><li>ndus dolore, deserunt, accusamus cumque volupta</li><li>tibus magni corrupti quod. Consequuntur rem deserunt eaque </li><li>enim fuga perferendis iste voluptate sequi. </li><li>Necessitatibus accusamus, omnis hic rerum possim</li><li>us doloremque recusandae soluta quaerat explicab</li></ul><div><br></div><div><br></div><div>o dolore laboriosam, natus molestias, dicta ad excepturi? Amet non est at ex, quidem, facere deserunt corrupti, suscipit reprehenderit dolor a minus animi laboriosam atque nesciunt fuga perspiciatis accusamus consectetur ullam. Molestias reprehenderit non quidem magnam, culpa qui similique illum distinctio assumenda aliquid odit molestiae obcaecati excepturi, nostrum placeat consequuntur vitae. Inventore nobis harum sunt doloremque facilis, a est ab reprehenderit aut rem ipsam similique placeat error modi non suscipit, accusantium voluptas iste odio minus. Quis dolore nam inventore delectus molestias facere earum. Neque ullam, impedit quasi mollitia voluptate, cupiditate vel aut provident quos veritatis consequatur quisquam ducimus cum. Consectetur eos perspiciatis obcaecati earum. Labore, ipsam, cum. Nostrum quia blanditiis a, recusandae iste. Incidunt labo</div><div><br></div><div>re autem qui veritatis explicabo est harum laborum. Mollitia ullam unde harum, ut ipsum doloremque nisi culpa repudiandae eveniet quo fuga laudantium, facilis deleniti, nobis laborum. Qui animi temporibus eveniet, sint quod neque omnis nihil adipisci quam corporis esse velit, aliquam nisi a facilis ipsa voluptatibus magnam nulla! Illum obcaecati vel, facere. Est quisquam aspernatur, qui dolorum nihil aut excepturi consequuntur quod soluta suscipit cupiditate veritatis. Fugiat culpa saepe eligendi architecto hic alias ex incidunt. Iure ipsa repellat ex! Totam earum, quod quibusdam nisi hic, placeat nobis et eveniet fugiat consectetur recusandae, magni ea asperiores dolor saepe, aliquam sed. Voluptate perspiciatis, error consequuntur animi nobis totam omnis et officia, distinctio culpa voluptas suscipit possimus dignissimos hic ea fugiat voluptatibus fuga adipisci sit iure?</div><br><br>', 'Copyright © 2018 CoinTrade -  All RIght  Reserved.', '<div id=\"fb-root\"></div>\r\n<script>\r\n    (function(d, s, id) {\r\n        var js, fjs = d.getElementsByTagName(s)[0];\r\n        if (d.getElementById(id)) return;\r\n        js = d.createElement(s); js.id = id;\r\n        js.src = \'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=205856110142667&autoLogAppEvents=1\';\r\n        fjs.parentNode.insertBefore(js, fjs);\r\n    }(document, \'script\', \'facebook-jssdk\'));\r\n</script>', 2, 'Buy & Sell Coins Near You', 'Team of Creative Designers & Developers. We Develop Digital Strategies, Products and Services. Architecting secure, efficient and user-friendly applications and systems by writing standard, well-documented and efficient codes to turn ideas into reality.', NULL, '2018-04-26 04:03:19');

-- --------------------------------------------------------

--
-- Table structure for table `cryptos`
--

CREATE TABLE `cryptos` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_addvertises`
--

CREATE TABLE `crypto_addvertises` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_type` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `margin` int(11) NOT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usd_rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deal_convertions`
--

CREATE TABLE `deal_convertions` (
  `id` int(10) UNSIGNED NOT NULL,
  `deal_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `deal_detail` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usd_amo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_amo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `try` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etemplates`
--

CREATE TABLE `etemplates` (
  `id` int(10) UNSIGNED NOT NULL,
  `esender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emessage` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `smsapi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `etemplates`
--

INSERT INTO `etemplates` (`id`, `esender`, `mobile`, `emessage`, `smsapi`, `created_at`, `updated_at`) VALUES
(1, 'do-not-reply@waredex.com', '+01234567890', '<br><br>\r\n	<div class=\"contents\" style=\"max-width: 600px; margin: 0 auto; border: 2px solid #000036;\">\r\n\r\n<div class=\"header\" style=\"background-color: #000036; padding: 15px; text-align: center;\">\r\n	<div class=\"logo\" style=\"width: 260px;text-align: center; margin: 0 auto;\">\r\n		<img src=\"https://i.imgur.com/4NN55uD.png\" alt=\"THESOFTKING\" style=\"width: 100%;\">\r\n	</div>\r\n</div>\r\n\r\n<div class=\"mailtext\" style=\"padding: 30px 15px; background-color: #f0f8ff; font-family: \'Open Sans\', sans-serif; font-size: 16px; line-height: 26px;\">\r\n\r\nHi {{name}},\r\n<br><br>\r\n{{message}}\r\n<br><br>\r\n<br><br>\r\n</div>\r\n\r\n<div class=\"footer\" style=\"background-color: #000036; padding: 15px; text-align: center;\">\r\n<a href=\"https://waredex.com/\" style=\"	background-color: #2ecc71;\r\n	padding: 10px 0;\r\n	margin: 10px;\r\n	display: inline-block;\r\n	width: 100px;\r\n	text-transform: uppercase;\r\n	text-decoration: none;\r\n	color: #ffff;\r\n	font-weight: 600;\r\n	border-radius: 4px;\">Website</a>\r\n<a href=\"https://waredex.com/products\" style=\"	background-color: #2ecc71;\r\n	padding: 10px 0;\r\n	margin: 10px;\r\n	display: inline-block;\r\n	width: 100px;\r\n	text-transform: uppercase;\r\n	text-decoration: none;\r\n	color: #ffff;\r\n	font-weight: 600;\r\n	border-radius: 4px;\">Products</a>\r\n<a href=\"https://waredex.com/contact\" style=\"	background-color: #2ecc71;\r\n	padding: 10px 0;\r\n	margin: 10px;\r\n	display: inline-block;\r\n	width: 100px;\r\n	text-transform: uppercase;\r\n	text-decoration: none;\r\n	color: #ffff;\r\n	font-weight: 600;\r\n	border-radius: 4px;\">Contact</a>\r\n</div>\r\n\r\n\r\n<div class=\"footer\" style=\"background-color: #000036; padding: 15px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.2);\">\r\n\r\n<strong style=\"color: #fff;\">© 2011 - 2018 THESOFTKING. All Rights Reserved.</strong>\r\n<p style=\"color: #ddd;\">TheSoftKing is not partnered with any other \r\ncompany or person. We work as a team and do not have any reseller, \r\ndistributor or partner!</p>\r\n\r\n\r\n</div>\r\n\r\n	</div>\r\n<br><br>', 'https://api.infobip.com/api/v3/sendsms/plain?user=****&password=****&sender=lbc&SMSText={{message}}&GSM={{number}}&type=longSMS', '2018-01-09 23:45:09', '2019-03-30 13:49:19');

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `main_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minamo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maxamo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_charge` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percent_charge` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `val1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `val2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `main_name`, `name`, `minamo`, `maxamo`, `fixed_charge`, `percent_charge`, `currency`, `rate`, `val1`, `val2`, `status`, `created_at`, `updated_at`) VALUES
(505, 'CoinPayment - BTC', 'BitCoin', '0', '50000', '0', '0', 'BTC', '80', '596f0097ed9d1ab8cfed05eb59c70e9f066513dfe4df64a8fc3917d309328315', '7472928395208f70E3cE30B9e10dc882cBDD3e9967b7942AaE492106d9C7bE44', 1, NULL, '2019-03-30 13:54:50'),
(506, 'CoinPayment - ETH', 'Etherium', '0', '50000', '0', '0', 'ETH', '80', '596f0097ed9d1ab8cfed05eb59c70e9f066513dfe4df64a8fc3917d309328315', '7472928395208f70E3cE30B9e10dc882cBDD3e9967b7942AaE492106d9C7bE44', 1, NULL, '2019-03-30 13:54:50'),
(509, 'CoinPayment - DOGE', 'Doge', '0', '50000', '0', '0', 'DOGE', '80', '596f0097ed9d1ab8cfed05eb59c70e9f066513dfe4df64a8fc3917d309328315', '7472928395208f70E3cE30B9e10dc882cBDD3e9967b7942AaE492106d9C7bE44', 1, NULL, '2019-03-30 13:54:50'),
(510, 'CoinPayment - LTC', 'LiteCoin', '0', '50000', '0', '0', 'LTC', '80', '596f0097ed9d1ab8cfed05eb59c70e9f066513dfe4df64a8fc3917d309328315', '7472928395208f70E3cE30B9e10dc882cBDD3e9967b7942AaE492106d9C7bE44', 1, NULL, '2019-03-30 13:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `description` blob NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'How It\'s Work', 'how-its-work', 0x3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e3c666f6e7420636f6c6f723d2223303030303030223e49742069732061206c6f6e672065737461626c6973686564206661637420746861742061207265616465722077696c6c206265206469737472616374656420627920746865207265616461626c6520636f6e74656e74206f6620612070616765207768656e206c6f6f6b696e6720617420697473206c61796f75742e2054686520706f696e74206f66207573696e67204c6f72656d20497073756d2069732074686174206974206861732061206d6f72652d6f722d6c657373206e6f726d616c20646973747269627574696f6e206f66206c6574746572732c206173206f70706f73656420746f207573696e672027436f6e74656e7420686572652c20636f6e74656e742068657265272c206d616b696e67206974206c6f6f6b206c696b65207265616461626c6520456e676c6973682e204d616e79206465736b746f70207075626c697368696e67207061636b6167657320616e6420776562207061676520656469746f7273206e6f7720757365204c6f72656d20497073756d2061732074686569722064656661756c74206d6f64656c20746578742c20616e6420612073656172636820666f7220276c6f72656d20697073756d272077696c6c20756e636f766572206d616e7920776562207369746573207374696c6c20696e20746865697220696e66616e63792e20566172696f75732076657273696f6e7320686176652065766f6c766564206f766572207468652079656172732c20736f6d6574696d6573206279206163636964656e742c20736f6d6574696d6573206f6e20707572706f73652028696e6a65637465642068756d6f757220616e6420746865206c696b65292e3c2f666f6e743e3c2f703e3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e3c666f6e7420636f6c6f723d2223303030303030223e3c62723e3c2f666f6e743e3c2f703e3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e3c753e3c693e3c623e3c666f6e7420636f6c6f723d2223303030303030223e576f726b696e672050726f636573733a3c62723e3c2f666f6e743e3c2f623e3c2f693e3c2f753e3c2f703e3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e3c666f6e7420636f6c6f723d2223303030303030223e3c62723e3c2f666f6e743e3c2f703e3c6469763e3c756c3e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e49742069732061206c6f6e672065737461626c6973686564206661637420746861742061207265616465722077696c6c20626520646973747261637465643c2f666f6e743e3c2f6c693e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e20627920746865207265616461626c6520636f6e74656e74206f6620612070616765207768656e206c6f6f6b696e6720617420697473206c61796f75742e203c62723e3c2f666f6e743e3c2f6c693e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e54686520706f696e74206f66207573694c6f72656d20497073756d2069732074686174206974206861732061206d6f72652d6f722d6c657373206e6f726d613c2f666f6e743e3c2f6c693e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e6c20646973747269627574696f6e206f66206c6574746572732c206173206f70706f73656420746f207573696e672027436f6e74656e7420686572652c20636f6e74656e742068657265272c3c2f666f6e743e3c2f6c693e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e6d616b696e67206974206c6f6f6b206c696b65207265616461626c6520456e676c6973682e203c62723e3c2f666f6e743e3c2f6c693e3c6c693e3c666f6e7420636f6c6f723d2223303030303030223e4d616e79206465736b746f70207075626c697368696e67207061636b6167657320616e642077656220706167653c62723e3c2f666f6e743e3c2f6c693e3c2f756c3e3c6469763e3c62723e3c2f6469763e3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b2220616c69676e3d2263656e746572223e3c666f6e7420636f6c6f723d2223303030303030223e6469746f7273206e6f7720757365204c6f72656d20497073756d2061732074686569722064656661756c74206d6f64656c20746578742c20616e6420612073656172636820666f7220276c6f72656d20697073756d272077696c6c20756e636f766572206d616e7920776562207369746573207374696c6c20696e20746865697220696e66616e63792e20566172696f75732076657273696f6e7320686176652065766f6c766564206f766572207468652079656172732c20736f6d6574696d6573206279206163636964656e742c20736f6d6574696d6573206f6e20707572706f73652028696e6a65637465642068756d6f757220616e6420746865206c696b65292e3c2f666f6e743e3c2f703e3c2f6469763e3c6469763e3c70207374796c653d226d617267696e2d626f74746f6d3a20313570783b2070616464696e673a203070783b20746578742d616c69676e3a206a7573746966793b20666f6e742d66616d696c793a202671756f743b4f70656e2053616e732671756f743b2c20417269616c2c2073616e732d73657269663b223e3c666f6e7420636f6c6f723d2223303030303030223e49742069732061206c6f6e672065737461626c6973686564206661637420746861742061207265616465722077696c6c206265206469737472616374656420627920746865207265616461626c6520636f6e74656e74206f6620612070616765207768656e206c6f6f6b696e6720617420697473206c61796f75742e2054686520706f696e74206f66207573696e67204c6f72656d20497073756d2069732074686174206974206861732061206d6f72652d6f722d6c657373206e6f726d616c20646973747269627574696f6e206f66206c6574746572732c206173206f70706f73656420746f207573696e672027436f6e74656e7420686572652c20636f6e74656e742068657265272c206d616b696e67206974206c6f6f6b206c696b65207265616461626c6520456e676c6973682e204d616e79206465736b746f70207075626c697368696e67207061636b6167657320616e6420776562207061676520656469746f7273206e6f7720757365204c6f72656d20497073756d2061732074686569722064656661756c74206d6f64656c20746578742c20616e6420612073656172636820666f7220276c6f72656d20697073756d272077696c6c20756e636f766572206d616e7920776562207369746573207374696c6c20696e20746865697220696e66616e63792e20566172696f75732076657273696f6e7320686176652065766f6c766564206f766572207468652079656172732c20736f6d6574696d6573206279206163636964656e742c20736f6d6574696d6573206f6e20707572706f73652028696e6a65637465642068756d6f757220616e6420746865206c696b65292e3c2f666f6e743e3c2f703e3c2f6469763e3c6469763e3c62723e3c2f6469763e, '2018-05-04 08:26:57', '2018-08-18 18:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 2),
(3, '2018_05_15_060641_create_events_table', 2),
(4, '2018_05_15_073146_create_matches_table', 3),
(5, '2018_05_15_130147_create_bet_questions_table', 4),
(7, '2018_05_16_060816_create_bet_options_table', 5),
(8, '2018_05_23_102456_create_bet_invests_table', 6),
(9, '2018_06_20_122315_create_cryptos_table', 7),
(10, '2018_06_20_191838_create_user_crypto_balances_table', 8),
(11, '2018_07_02_183422_create_crypto_addvertises_table', 9),
(12, '2018_07_07_121201_create_advertise_deals_table', 10),
(13, '2018_07_07_121757_create_deal_convertions_table', 10),
(14, '2018_07_07_131613_create_deal_attachments_table', 11),
(15, '2018_07_07_172234_create_currencies_table', 12),
(16, '2018_07_21_124756_create_tickets_table', 13),
(17, '2018_07_21_124923_create_ticket_comments_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `sub_title`, `image`, `created_at`, `updated_at`) VALUES
(5, 'Buy & Sell Coins Near You', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nam sed officiis in excepturi unde, vel? Illum dolorum nobis, labore iusto!', 'slider_1534583724.jpg', '2018-06-10 06:50:32', '2018-10-25 11:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `socials`
--

CREATE TABLE `socials` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `socials`
--

INSERT INTO `socials` (`id`, `name`, `code`, `link`, `created_at`, `updated_at`) VALUES
(3, 'Facebook', '<i class=\"fa fa-facebook\"></i>', '#', '2018-05-22 22:56:12', '2018-08-18 18:50:23'),
(4, 'Twitter', '<i class=\"fa fa-twitter\"></i>', '#', '2018-05-22 23:57:46', '2018-08-18 18:50:16'),
(5, 'Linkedin', '<i class=\"fa fa-linkedin\"></i>', '#', '2018-05-22 23:58:14', '2018-08-18 18:50:10'),
(7, 'G+', '<i class=\"fa fa-google-plus\"></i>', '#', '2018-06-27 09:36:43', '2018-08-18 18:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trxes`
--

CREATE TABLE `trxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `main_amo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `charge` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verify` tinyint(4) NOT NULL DEFAULT '0',
  `email_verify` tinyint(4) NOT NULL DEFAULT '0',
  `email_time` datetime DEFAULT NULL,
  `phone_time` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `login_time` datetime DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tauth` int(11) NOT NULL DEFAULT '1',
  `tfver` int(11) DEFAULT NULL,
  `secretcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_crypto_balances`
--

CREATE TABLE `user_crypto_balances` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `balance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD KEY `admin_password_resets_email_index` (`email`),
  ADD KEY `admin_password_resets_token_index` (`token`);

--
-- Indexes for table `advertise_deals`
--
ALTER TABLE `advertise_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_settings`
--
ALTER TABLE `basic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cryptos`
--
ALTER TABLE `cryptos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crypto_addvertises`
--
ALTER TABLE `crypto_addvertises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_convertions`
--
ALTER TABLE `deal_convertions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `etemplates`
--
ALTER TABLE `etemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socials`
--
ALTER TABLE `socials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trxes`
--
ALTER TABLE `trxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_crypto_balances`
--
ALTER TABLE `user_crypto_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advertise_deals`
--
ALTER TABLE `advertise_deals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `basic_settings`
--
ALTER TABLE `basic_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cryptos`
--
ALTER TABLE `cryptos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto_addvertises`
--
ALTER TABLE `crypto_addvertises`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deal_convertions`
--
ALTER TABLE `deal_convertions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etemplates`
--
ALTER TABLE `etemplates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `socials`
--
ALTER TABLE `socials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trxes`
--
ALTER TABLE `trxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_crypto_balances`
--
ALTER TABLE `user_crypto_balances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
