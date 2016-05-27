function model_AddressCountry() {
 this.country_id = '';
 this.country_name = '';
}

function model_AddressCountryFactory() { return { Create: function(){ return new model_AddressCountry();} } }

function model_AddressState() {
 this.state_id = '';
 this.state_country_id = '';
 this.state_name = '';
}

function model_AddressStateFactory() { return { Create: function(){ return new model_AddressState();} } }

function model_Admin() {
 this.admin_id = '';
 this.admin_uname = '';
 this.admin_password = '';
 this.admin_level = '';
}

function model_AdminFactory() { return { Create: function(){ return new model_Admin();} } }

function model_CustomerAddress() {
 this.cust_addr_cust_id = '';
 this.cust_addr_street_1 = '';
 this.cust_addr_street_2 = '';
 this.cust_addr_city = '';
 this.cust_addr_state = '';
 this.cust_addr_code = '';
 this.cust_addr_country = '';
 this.cust_addr_created = '';
 this.cust_addr_modified = '';
}

function model_CustomerAddressFactory() { return { Create: function(){ return new model_CustomerAddress();} } }

function model_CustomerPaymentType() {
 this.cust_pay_id = '';
 this.cust_pay_cust_id = '';
 this.cust_pay_card_type = '';
 this.cust_pay_card_num = '';
 this.cust_pay_card_exp = '';
 this.cust_pay_card_cvv = '';
 this.cust_pay_created = '';
 this.cust_pay_modified = '';
}

function model_CustomerPaymentTypeFactory() { return { Create: function(){ return new model_CustomerPaymentType();} } }

function model_FishSpecies() {
 this.species_id = '';
 this.species_name = '';
 this.species_desc = '';
 this.species_img = '';
 this.species_color = '';
 this.species_is_saltwater = '';
 this.species_cost = '';
 this.species_created = '';
 this.species_modified = '';
}

function model_FishSpeciesFactory() { return { Create: function(){ return new model_FishSpecies();} } }

function model_FishSpeciesColor() {
 this.species_color_id = '';
 this.species_color_name = '';
 this.species_color_value = '';
 this.species_color_created = '';
 this.species_color_modified = '';
}

function model_FishSpeciesColorFactory() { return { Create: function(){ return new model_FishSpeciesColor();} } }

function model_Item() {
 this.item_id = '';
 this.item_ref_id = '';
}

function model_ItemFactory() { return { Create: function(){ return new model_Item();} } }

function model_OtherItems() {
 this.other_item_id = '';
 this.other_item_name = '';
 this.other_item_desc = '';
 this.other_item_img = '';
 this.other_item_cost = '';
 this.other_item_created = '';
 this.other_item_modified = '';
}

function model_OtherItemsFactory() { return { Create: function(){ return new model_OtherItems();} } }

function model_Sale() {
 this.sale_id = '';
 this.sale_cust_id = '';
 this.sale_invoice_paid = '';
 this.sale_created = '';
 this.sale_modified = '';
}

function model_SaleFactory() { return { Create: function(){ return new model_Sale();} } }

function model_SaleLineItem() {
 this.sale_li_id = '';
 this.sale_li_sale_id = '';
 this.sale_li_item_id = '';
 this.sale_li_quantity = '';
 this.sale_li_subtotal = '';
 this.sale_li_created = '';
 this.sale_li_modified = '';
}

function model_SaleLineItemFactory() { return { Create: function(){ return new model_SaleLineItem();} } }

function model_Session() {
 this.session_id = '';
 this.session_usr_id = '';
 this.session_created = '';
}

function model_SessionFactory() { return { Create: function(){ return new model_Session();} } }

function model_User() {
 this.usr_id = '';
 this.usr_email = '';
 this.usr_password = '';
 this.usr_first_name = '';
 this.usr_middle_init = '';
 this.usr_last_name = '';
 this.usr_phone = '';
 this.usr_birthday = '';
 this.usr_profile_img = '';
 this.usr_is_admin = '';
 this.usr_created = '';
 this.usr_modified = '';
 this.usr_is_suspended = '';
}

function model_UserFactory() { return { Create: function(){ return new model_User();} } }

