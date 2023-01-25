<?php

/**

 * @package beam-woocommerce-buy-subscription

 */

/*

Plugin Name: Beam Buy Subscription for Woocommerce

Plugin URI: https://gbc-beam.com

Description: Subscrição para instalação como produto (possível pagar com Beam)

Version: 1.1

Author: GBC-Beam

Author URI: 

License: GPLv2 or later

Text Domain: beam-woocommerce-buy-subscription

*/

if (!defined('ABSPATH')) exit; // Sair se acedido diretamente

require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/beam-functions.php';

/**
 * Load do textdomain do plugin
 */

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    add_action('plugins_loaded', 'init_bwbs_class');

    function init_bwbs_class(){

        class Beam_Woocommerce_Buy_Subscription
        {

            public function __construct()
            {
                //add cart icon
                add_shortcode ('bwbs_cart_icon', array($this, 'bwbs_cart_icon') );
                //add adesao to cart so we can always have 1 item in the cart
                add_shortcode ('add_loja_fisica_to_cart', array($this, 'add_loja_fisica_to_cart') );

                // add_filter( 'wp_nav_menu_items', array($this, 'bwbs_add_cart_icon_to_menu'), 10, 2 );
                //add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'bwbs_cart_count') );
                //enqueue scripts
                add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

                //make changes to product category "beam-forms"
                add_action('template_redirect', array($this, 'on_loaded_hooks'));

                //when added to cart
                // add_filter( 'woocommerce_add_cart_item_data', array($this, 'bwbs_add_data_to_cart'), 10, 3 );

                //before redirecting send emails
                // add_action('woocommerce_before_thankyou', array($this, 'after_checkout_send_email'));
                // Redirect WooCommerce checkout page to a custom thank you page
                add_action( 'woocommerce_thankyou', array($this, 'bwbs_redirect_checkout'));


                // $form_fields_empresa_empresa = new stdClass();
                // $form_fields_titular = new stdClass();
                // $form_fields_contacto = new stdClass();
                // $form_fields_info = new stdClass();

                // //form - empresa
                // $this->form_fields_empresa["nome_emp"] = array("type" => "text", "title" => __("Nome da empresa"), "id" => "nome_empresa");
                // $this->form_fields_empresa["cod_ac_cert_perm"] = array("type" => "text", "title" => __("Código de acesso certidão permanente"), "id" => "cod_ac_cert_perm");
                // $this->form_fields_empresa["morada_emp"] = array("type" => "text", "title" => __("Morada"), "id" => "morada_emp");
                // $this->form_fields_empresa["telefone_emp"] = array("type" => "text", "title" => __("Telefone/Telemóvel da empresa"), "id" => "telefone_emp");
                // $this->form_fields_empresa["email_emp"] = array("type" => "email", "title" => __("Email da empresa"), "id" => "email");
                // $this->form_fields_empresa["nome_estabelecimento"] = array("type" => "text", "title" => __("Nome do estabelecimento"), "id" => "nome_estabelecimento");
                // $this->form_fields_empresa["area_negocios"] = array("type" => "text", "title" => __("Área de Negócios"), "id" => "area_negocios");
                // $this->form_fields_empresa["cae"] = array("type" => "text", "title" => __("CAE"), "id" => "cae");
                // $this->form_fields_empresa["website"] = array("type" => "text", "title" => __("Website da loja"), "id" => "website");
                // $this->form_fields_empresa["nif_nipc"] = array("type" => "text", "title" => __("NIF/NIPC"), "id" => "nif_nipc");
                // $this->form_fields_empresa["iban"] = array("type" => "text", "title" => __("IBAN"), "id" => "iban");
                // $this->form_fields_empresa["nome_banco"] = array("type" => "text", "title" => __("Nome do banco"), "id" => "nome_banco");
                // $this->form_fields_empresa["codigo_swift"] = array("type" => "text", "title" => __("Código Swift"), "id" => "codigo_swift");

                // //form - titular
                // $this->form_fields_titular["nome_tit"] = array("type" => "text", "title" => __("Nome do titular"), "id" => "nome_tit");
                // $this->form_fields_titular["apelido_tit"] = array("type" => "text", "title" => __("Apelido do titular"), "id" => "apelido_tit");
                // $this->form_fields_titular["nif_cc"] = array("type" => "text", "title" => __("NIF/Cartão de Cidadão"), "id" => "nif_cc");
                // $this->form_fields_titular["email_tit"] = array("type" => "email", "title" => __("Email do titular"), "id" => "email_tit");
                // $this->form_fields_titular["telefone_tit"] = array("type" => "text", "title" => __("Telefone/Telemóvel"), "id" => "telefone_tit");
                // $this->form_fields_titular["morada_tit"] = array("type" => "text", "title" => __("Morada do titular"), "id" => "morada_tit");
                // $this->form_fields_titular["codigo_postal_tit"] = array("type" => "text", "title" => __("Código postal do titular"), "id" => "codigo_postal_tit");

                // //form - pessoa de contacto
                // $this->form_fields_contacto["nome_cont"] = array("type" => "text", "title" => __("Nome do contacto"), "id" => "nome_cont");
                // $this->form_fields_contacto["apelido_cont"] = array("type" => "text", "title" => __("Apelido do contacto"), "id" => "apelido_cont");
                // $this->form_fields_contacto["email_cont"] = array("type" => "email", "title" => __("Email do contacto"), "id" => "email_cont");
                // $this->form_fields_contacto["cargo_cont"] = array("type" => "text", "title" => __("Cargo na Empresa"), "id" => "cargo_cont");
                
                // //form - mais informações
                // $this->form_fields_info["perc_cashback_cliente"] = array(
                //     "type" => "select", 
                //     "title" => __("Percentagem de cashback para o cliente"), 
                //     "id" => "perc_cashback_cliente",
                //     "options" => [
                //         array("key" => "10%", "value" => "10%"),
                //         array("key" => "15%", "value" => "15%"),
                //         array("key" => "20%", "value" => "20%"),
                //         array("key" => "25%", "value" => "25%"),
                //         array("key" => "30%", "value" => "30%"),
                //         array("key" => "35%", "value" => "35%"),
                //         array("key" => "40%", "value" => "40%"),
                //         array("key" => "45%", "value" => "45%"),
                //         array("key" => "50%", "value" => "50%"),
                //     ]);
                    
                // $this->form_fields_info["morada_inst"] = array("type" => "text", "title" => __("Morada de instalação"), "id" => "morada_inst");
                // $this->form_fields_info["email_publico"] = array("type" => "text", "title" => __("Email para o público"), "id" => "email_publico");
                // $this->form_fields_info["telefone_publico"] = array("type" => "text", "title" => __("Telefone/Telemóvel para o público"), "id" => "telefone_publico");
                // $this->form_fields_info["horario_func_ab"] = array("type" => "text", "title" => __("Horário de funcionamento (Abertura)"), "id" => "horario_func_ab");
                // $this->form_fields_info["horario_func_enc"] = array("type" => "text", "title" => __("Horário de funcionamento (Encerramento)"), "id" => "horario_func_enc");
                // $this->form_fields_info["desc_curta"] = array("type" => "text", "title" => __("Descrição curta"), "id" => "desc_curta");
                // $this->form_fields_info["desc_longa"] = array("type" => "text", "title" => __("Descrição longa"), "id" => "desc_longa");
                // $this->form_fields_info["facebook"] = array("type" => "text", "title" => __("Facebook"), "id" => "facebook");
                // $this->form_fields_info["instagram"] = array("type" => "text", "title" => __("Instagram"), "id" => "instagram");
                // $this->form_fields_info["outro"] = array("type" => "text", "title" => __("Outro"), "id" => "outro");

            add_filter( 'woocommerce_cart_item_name', array($this, 'add_excerpt_in_cart_item_name'), 10, 3 );
            // add_action( 'admin_menu', array($this, 'plugin_settings_page') );
            // add_action( 'admin_init', array($this, 'plugin_register_settings') );
            
          
                //change cart product link
                // add_filter( 'woocommerce_cart_item_permalink', array($this, 'change_cart_item_permalink'), 10, 3 );

                add_action( 'admin_menu', array($this, 'dbi_add_settings_page') );
                add_action( 'admin_init', array($this, 'dbi_register_settings') );

            }

            function dbi_add_settings_page() {
                add_options_page( 'Example plugin page', 'Example Plugin Menu', 'manage_options', 'dbi-example-plugin', array($this, 'dbi_render_plugin_settings_page') );
            }

            function dbi_render_plugin_settings_page() {
                ?>
                <h2>Example Plugin Settings</h2>
                <form action="options.php" method="post">
                    <?php 
                    settings_fields( 'dbi_example_plugin_options' );
                    do_settings_sections( 'dbi_example_plugin' ); ?>
                    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
                </form>
                <?php
            }

            function dbi_example_plugin_options_validate( $input ) {
                return $input;
                // $newinput['api_key'] = trim( $input['api_key'] );
                // if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
                //     $newinput['api_key'] = '';
                // }
            
                // return $newinput;
            }

            function dbi_plugin_setting_api_key() {
                $options = get_option( 'dbi_example_plugin_options' );
                wp_dropdown_pages(array('name' => 'dbi_example_plugin_options[api_key]', 'selected' => $options['api_key']));
                // echo "<input id='dbi_plugin_setting_api_key' name='dbi_example_plugin_options[api_key]' type='text' value='" . esc_attr( $options['api_key'] ) . "' />";
            }
            

            function dbi_register_settings() {
                register_setting( 'dbi_example_plugin_options', 'dbi_example_plugin_options', array($this, 'dbi_example_plugin_options_validate') );
                add_settings_section( 'api_settings', 'API Settings', array($this, 'dbi_plugin_section_text'), 'dbi_example_plugin' );
            
                add_settings_field( 'dbi_plugin_setting_api_key', 'Redirect Checkout URL', array($this, 'dbi_plugin_setting_api_key'), 'dbi_example_plugin', 'api_settings' );
            }

            function dbi_plugin_section_text() {
                echo '<p>Here you can set all the options for using the API</p>';
            }

            // function change_cart_item_permalink($product_get_permalink_cart_item, $cart_item, $cart_item_key){
            //     $product_id = $cart_item['product_id'];
            //     $this->dd(__FUNCTION__, $product_get_permalink_cart_item);
            //     $customUrl = get_permalink( get_page_by_path( 'box_select_product' ) );
            //     $updatLink =add_query_arg( 'product_box_id', $productId, $customUrl );
            //     return $updatLink; 
            //   }
            // function plugin_settings_page() {
            //     add_options_page( 'Beam Plugin', 'Beam Plugin Menu', 'manage_options', 'bs_plugin', array($this, 'render_plugin_settings_page') );
            // }

       
 
            
            // function bwbs_settings_validate($args){
            // }
            
            function on_loaded_hooks(){

                if(!$this->check_product_category()) return;

                //disable product page tabs
                add_filter( 'woocommerce_product_tabs', array($this, 'bwbs_remove_product_tabs'), 98 );
                //remove related products
                remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
                //validate fields and send email
                // add_filter( 'woocommerce_add_to_cart_validation', array($this, 'bwbs_add_to_cart_validation'), 10, 3 );
                
                //gets form values
                // add_filter( 'woocommerce_get_item_data', array($this, 'bwbs_get_item_data'), 10, 2 );
                //remove thumbnail image
                // add_action('loop_start', array($this, 'bwbs_remove_gallery_and_product_images'));
                
                
                // add_action( 'wp_mail_failed', array($this, 'on_mail_error'), 10, 1 );
                // $this->add_to_cart_send_email(array());

                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

                add_action( 'woocommerce_single_product_summary', array($this, 'bwbs_change_product_title'), 5);

                // add_filter( 'woocommerce_cart_item_thumbnail', '__return_false' );

                add_filter( 'woocommerce_product_single_add_to_cart_text', array($this, 'bwbs_add_to_cart_button_text_single') ); 

                // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

                // add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
                add_action( 'woocommerce_single_product_summary', array($this, 'bwbs_woocommerce_template_product_description'), 20 );

                //remove product summary
                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

                // add_action( 'wp_mail_failed', array($this, 'on_mail_error'), 10, 1 );
                
            }

function add_excerpt_in_cart_item_name( $item_name,  $cart_item,  $cart_item_key ){
    $excerpt = wp_strip_all_tags( get_the_excerpt($cart_item['product_id']), true );
    $style = ' style="font-size:14px; line-height:normal;"';
    $excerpt_html = '<br>
        <p name="short-description"'.$style.'>'.$excerpt.'</p>';
    return $item_name . $excerpt_html;
}

            function bwbs_redirect_checkout( $order_id ){
                $order = wc_get_order( $order_id );
                $options = get_option( 'dbi_example_plugin_options' );

                $this->after_checkout_send_email($order);

                $url = get_permalink($options['api_key']);

                if ( ! $order->has_status( 'failed' ) ) {
                    wp_safe_redirect( $url );
                    exit;
                }
            }

            function bwbs_woocommerce_template_product_description() {
                wc_get_template( 'single-product/tabs/description.php' );
            }

            function bwbs_add_cart_icon_to_menu ( $items, $args ) {
                    $items .= do_shortcode( "[bwbs_cart_icon]" );
                    return $items;
            }

            /**
             * Add AJAX Shortcode when cart contents update
             */
            function bwbs_cart_count( $fragments ) {
            
                ob_start();
                
                $cart_count = WC()->cart->cart_contents_count;
                $cart_url = wc_get_cart_url();
                
                ?>
                <li class="menu-item"><a class="cart-contents my" href="<?php echo $cart_url; ?>" title="<?php _e( 'View your shopping cart' ); ?>">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php
                if ( $cart_count > 0 ) {
                    ?>
                    <span class="cart-contents-count"><?php echo $cart_count; ?></span>
                    <?php            
                }
                    ?></a></li>
                <?php
            
                $fragments['a.cart-contents'] = ob_get_clean();
                
                return $fragments;
            }


            function bwbs_cart_icon() {
                $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
                $cart_url = wc_get_cart_url();  // Set Cart URL
                $html = "";
                $html .= '<a class="cart-contents2 t" href="'.$cart_url.'" title="My Basket">
                <i class="fa fa-solid fa-shopping-cart">';
                if ( $cart_count > 0 ) {
                //    $html .= '<span class="cart-contents-count">'.$cart_count.'</span>';
                }
                $html .= "</a></li>";
                return $html;
            
            }

            function dd($action, $object){

                echo "Action: ".$action;
                die("<pre>".print_r($object, true)."</pre>");

            }

            function add_loja_fisica_to_cart(){

                if(!is_admin()){

                    $products = wc_get_products(array(
                        'category' => array('beam-forms'),
                    ));
                    if ( WC()->cart->get_cart_contents_count() == 0 ) {
                        foreach($products as $p){
                            WC()->cart->add_to_cart( $p->id );
                        }
                    }
                }
            }

            function bwbs_add_to_cart_button_text_single() {
                return __( 'Avançar', 'woocommerce' ); 
            }

            function bwbs_change_product_title(){

                echo '<h2 class="product_title entry-title">'.__("Instalação Beam").'</h2>';
            }
            function bwbs_add_values_to_order_item_meta($item_id, $values)
            {
                global $woocommerce,$wpdb;

                $fields = array_merge($this->form_fields_empresa, $this->form_fields_titular, $this->form_fields_contacto, $this->form_fields_info);

                foreach($fields as $f){
                
                    $inputs[$f["id"]] = array("title" => $f["title"], "value" => $values[$f["id"]]);

                }

                foreach($inputs as $i){
                    wc_add_order_item_meta($item_id,$i["title"],$i["value"]);  
                }
            }

            public function payment_scripts()
            {
                // if (!is_cart() && !is_checkout() && !is_product()) {
                //     return;
                // }

                wp_enqueue_style('bwbs_css', plugins_url('assets/css/plugin.css', __FILE__));

                wp_register_script('bwbs_js', plugins_url('assets/js/plugin.js', __FILE__), array('jquery'));

                wp_enqueue_script('bwbs_js');
            }

            function bwbs_remove_gallery_and_product_images() {
                if ( is_product() ) {
                    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
                }
            }

       
        
            //remove product tabs
            function bwbs_remove_product_tabs( $tabs ) {

                unset( $tabs['description'] );          // Remove the description tab
                unset( $tabs['reviews'] );          // Remove the reviews tab
                unset( $tabs['additional_information'] );   // Remove the additional information tab

                return $tabs;
            }

            //check the category
            function check_product_category(){

                global $post;

                $category_name = "beam-forms";

                $terms = get_the_terms( $post->ID, 'product_cat' );

                if($terms){

                    foreach ($terms as $term) {
                        $product_cat_name = $term->name;
                        if($product_cat_name == $category_name) return true;
                    }
                }
                return false;
            }
            
            function create_inputs($field){

                $html = "";

                switch($field["type"]){
                    case "text":
                        $html .='
                        <p class="form-row">
                        <label for="'.$field["id"].'">'.__($field["title"].": ", "woocommerce").'</label>
                        <input type="text" id="'.$field["id"].'" name="'.$field["id"].'" value="" />
                        </p>';
                    break;
                    case "email":
                        $html .='
                        <p class="form-row">
                        <label for="'.$field["id"].'">'.__($field["title"].": ", "woocommerce").'</label>
                        <input type="email" id="'.$field["id"].'" name="'.$field["id"].'" value="" />
                        </p>';
                    break;
                    case "select":
                    $html .='
                    <div class="form-row">
                    <label for="'.$field["id"].'">'.__($field["title"].": ", "woocommerce").'</label>
                    <select id="'.$field["id"].'" name="'.$field["id"].'" value="">';
                    foreach($field["options"] as $o){
                        $html .="<option value=".$o["value"].">".$o["key"]."</option>";
                    }
                    $html .= "</select>";
                    $html .= "</div>";
                    break;
                }

                return $html;

            }

            //display the custom fields in the product page
            function woocommerce_bwbs_fields_display()
            {
                global $post;
                
                $product = wc_get_product($post->ID);

                //name field
                echo '<div class="sub_form">';

                echo "<h2>".__("Empresa", "woocommerce")."</h1>";
                echo "<p>".__("Informações sobre a empresa")."</p>";

                foreach($this->form_fields_empresa as $f){
                    echo $this->create_inputs($f);
                }

                echo "<h2>".__("Legal Representante do Titular do Contrato", "woocommerce")."</h1>";
                echo "<p>".__("Informações sobre a empresa")."</p>";

                foreach($this->form_fields_titular as $f){
                    echo $this->create_inputs($f);
                }

                echo "<h2>".__("Pessoa de Contato", "woocommerce")."</h1>";
                echo "<p>".__("Informações sobre a pessoa que podemos contactar")."</p>";

                foreach($this->form_fields_contacto as $f){
                    echo $this->create_inputs($f);
                }

                echo "<h2>".__("Mais informações", "woocommerce")."</h1>";
                echo "<p>".__(" Informações necessárias para a criação da sua loja na plataforma Beam")."</p>";

                foreach($this->form_fields_info as $f){
                    echo $this->create_inputs($f);
                }

                echo "</div>";
            }
            

        // function save_woocommerce_product_custom_fields($post_id)
        // {
        //     $product = wc_get_product($post_id);
        //     var_dump($product);die();
        //     $bwbs_name = isset($_POST['bwbs_name']) ? $_POST['bwbs_name'] : '';
        //     $product->update_meta_data('bwbs_name', sanitize_text_field($bwbs_name));
        //     $product->save();
        // }
        // add_action('woocommerce_process_product_meta', 'save_woocommerce_product_custom_fields');

            // function bwbs_add_to_cart_validation( $passed, $product_id, $qty ){

            //     // $inputs = array();

            //     // $fields = array_merge($this->form_fields_empresa, $this->form_fields_titular, $this->form_fields_contacto, $this->form_fields_info);

            //     // foreach($fields as $f){
                
            //     //     $inputs[$f["id"]] = array("title" => $f["title"], "value" => filter_input( INPUT_POST, $f["id"] ));

            //     // }

            //     // $product = wc_get_product( $product_id );

            //     // foreach($inputs as $i){
            //     //     if(empty($i["value"])){
            //     //         $passed = false;
            //     //         wc_add_notice( sprintf( __( '%s não pode ser adicionado ao carrinho sem preencher o campo <b>%s</b>.', 'woocommerce' ), $product->get_title(), $i["title"] ), 'error' );
            //     //     }
            //     // }

            //     return $passed;
            
            // }

            function bwbs_add_data_to_cart( $cart_item_data, $product_id, $variation_id ) {

                $this->add_to_cart_send_email($cart_item_data);
            
                return $cart_item_data;
            }

            // show wp_mail() errors
            function on_mail_error( $wp_error ) {

                $this->add_log("on_mail_error:", $wp_error);

                echo "<pre>";
                print_r($wp_error);
                echo "</pre>";
            } 
            
            function add_log($action, $object){
                $log_dir_path = WP_PLUGIN_DIR . '/logs';
            
                $log_file_path = $log_dir_path . "/log_".date('d-M-Y').".txt";
            
                if(!is_dir($log_dir_path))
                    mkdir($log_dir_path.'/', 0777, TRUE) || die("Could not create directory");
            
                $log_entry = "[".date("d-M-Y H:i:s")."] ";
                $log_entry .= "Action: ".$action . " | Message: ";
                $log_entry .= print_r( $object, true )." ".PHP_EOL;
            
                // echo "path: ".$log_file_path." entry: ".$log_entry;
            
                file_put_contents($log_file_path, $log_entry."\n", FILE_APPEND) || die("Could not create log");
            
            }

            function add_to_cart_send_email($mail_data){

                $content = "";

                foreach($mail_data as $md){
                    $content .= "<p><b>".$md["title"].":</b> ".$md["value"]."</p>";
                }

                $to = "adesoes@beamportugal.com";
                $subject = __("Nova instalação adicionada ao carrinho!");
                $headers[] = 'Cc: joao.costa@gbc-beam.com';
                // Sending your custom email notification
                wp_mail( $to, $subject, $content, $headers );

            }

            function after_checkout_send_email($order){

                $content = "";

                //nome
                //email
                //telefone
                //morada

                add_filter( 'wp_mail_content_type',array($this, 'bwbs_set_content_type') );

                $content .= "<pre>".print_r($order, true)."</pre>";

                $to = "adesoes@beamportugal.com";
                $subject = __("Pagamento de instalação concluído");
                $headers[] = 'Cc: joao.costa@gbc-beam.com';


                // Sending your custom email notification
                wp_mail( $to, $subject, $content, $headers );
            }

            function bwbs_set_content_type(){
                return "text/html";
            }

            function bwbs_get_item_data( $item_data, $cart_item ) {

                $fields = array_merge($this->form_fields_empresa, $this->form_fields_titular, $this->form_fields_contacto, $this->form_fields_info);

                foreach($fields as $f){

                    if ( isset( $cart_item[$f["id"]] ) ){
                        $item_data[] = array(
                            'key' => $f["title"],
                            'display' => wc_clean($cart_item[$f["id"]])
                        );
                    }
                }

                return $item_data;
            
            }
            
        }
        $bwbs = new Beam_Woocommerce_Buy_Subscription();

    }
}
