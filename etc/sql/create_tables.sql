
CREATE TABLE `tbl_address_country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL
);


CREATE TABLE `tbl_address_state` (
  `state_id` int(11) NOT NULL,
  `state_country_id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL
);


CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_uname` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_level` int(11) NOT NULL
);


CREATE TABLE `tbl_customer` (
  `cust_id` int(11) NOT NULL,
  `cust_email` varchar(100) NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_first_name` varchar(100) NOT NULL,
  `cust_middle_init` char(1) NOT NULL,
  `cust_last_name` varchar(100) NOT NULL,
  `cust_created` datetime NOT NULL,
  `cust_modified` datetime NOT NULL
);


CREATE TABLE `tbl_customer_address` (
  `cust_addr_cust_id` int(11) NOT NULL,
  `cust_addr_street_1` varchar(100) NOT NULL,
  `cust_addr_street_2` varchar(100) NOT NULL,
  `cust_addr_city` varchar(50) NOT NULL,
  `cust_addr_state` int(11) NOT NULL,
  `cust_addr_code` varchar(20) NOT NULL,
  `cust_addr_country` int(11) NOT NULL,
  `cust_addr_created` datetime NOT NULL,
  `cust_addr_modified` datetime NOT NULL
);


CREATE TABLE `tbl_customer_payment_type` (
  `cust_pay_id` int(11) NOT NULL,
  `cust_pay_cust_id` int(11) NOT NULL,
  `cust_pay_card_type` int(11) NOT NULL,
  `cust_pay_card_num` int(30) NOT NULL,
  `cust_pay_card_exp` date NOT NULL,
  `cust_pay_card_cvv` int(3) NOT NULL,
  `cust_pay_created` datetime NOT NULL,
  `cust_pay_modified` datetime NOT NULL
);


CREATE TABLE `tbl_fish_species` (
  `species_id` int(10) NOT NULL,
  `species_name` varchar(100) NOT NULL,
  `species_desc` text NOT NULL,
  `species_color` int(10) NOT NULL,
  `species_is_saltwater` tinyint(1) NOT NULL,
  `species_cost` decimal(10,0) NOT NULL,
  `species_created` datetime NOT NULL,
  `species_modified` datetime NOT NULL
);


CREATE TABLE `tbl_fish_species_color` (
  `species_color_id` int(10) NOT NULL,
  `species_color_name` varchar(50) NOT NULL,
  `species_color_value` int(6) NOT NULL,
  `species_color_created` datetime NOT NULL,
  `species_color_modified` datetime NOT NULL
);


CREATE TABLE `tbl_sale` (
  `sale_id` int(11) NOT NULL,
  `sale_cust_id` int(11) NOT NULL,
  `sale_invoice_paid` tinyint(1) NOT NULL,
  `sale_created` datetime NOT NULL,
  `sale_modified` datetime NOT NULL
);


CREATE TABLE `tbl_sale_line_item` (
  `sale_li_id` int(11) NOT NULL,
  `sale_li_sale_id` int(11) NOT NULL,
  `sale_li_species_id` int(11) NOT NULL,
  `sale_li_quantity` int(11) NOT NULL,
  `sale_li_subtotal` decimal(10,0) NOT NULL,
  `sale_li_created` datetime NOT NULL,
  `sale_li_modified` datetime NOT NULL
);

ALTER TABLE `tbl_address_country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_name` (`country_name`);


ALTER TABLE `tbl_address_state`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `IX_STATE` (`state_country_id`,`state_name`);

ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`cust_id`),
  ADD UNIQUE KEY `IX_CUST_EMAIL` (`cust_email`,`cust_password`) USING BTREE,
  ADD KEY `IX_CUST_NAME` (`cust_first_name`,`cust_middle_init`,`cust_last_name`);

ALTER TABLE `tbl_customer_address`
  ADD PRIMARY KEY (`cust_addr_cust_id`);

ALTER TABLE `tbl_customer_payment_type`
  ADD PRIMARY KEY (`cust_pay_id`);

ALTER TABLE `tbl_fish_species`
  ADD PRIMARY KEY (`species_id`),
  ADD UNIQUE KEY `IX_SPECIES` (`species_name`);

ALTER TABLE `tbl_fish_species_color`
  ADD PRIMARY KEY (`species_color_id`),
  ADD UNIQUE KEY `IX_SPECIES_COLOR_NAME` (`species_color_name`),
  ADD UNIQUE KEY `IX_SPECIES_COLOR_VAL` (`species_color_value`);

ALTER TABLE `tbl_sale`
  ADD PRIMARY KEY (`sale_id`);

ALTER TABLE `tbl_sale_line_item`
  ADD PRIMARY KEY (`sale_li_id`),
  ADD UNIQUE KEY `IX_SALE_LI` (`sale_li_species_id`,`sale_li_id`) USING BTREE;

ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_customer`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_customer_payment_type`
  MODIFY `cust_pay_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_fish_species`
  MODIFY `species_id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_fish_species_color`
  MODIFY `species_color_id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_sale_line_item`
  MODIFY `sale_li_id` int(11) NOT NULL AUTO_INCREMENT;
