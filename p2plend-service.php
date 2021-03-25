<?php
/*
Plugin Name: Point 2 Point,Lend Service
Plugin URI:
Description: Point 2 Point,Lend Service
Version: 1.0
Author: stallioni Aruljothi
Author URI: http://stallioni.in
*/
/*********************************************/
/* DEFINEINF THE PLUGIN DIR,URL, NAME etc   */
/*******************************************/
if ( ! defined( 'P2P_PLUGIN_BASENAME' ) )
	define( 'P2P_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'P2P_PLUGIN_NAME' ) )
	define( 'P2P_PLUGIN_NAME', trim( dirname( P2P_PLUGIN_BASENAME ), '/' ) );
if ( ! defined( 'P2P_PLUGIN_DIR' ) )
	define( 'P2P_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . P2P_PLUGIN_NAME );
if ( ! defined( 'P2P_PLUGIN_URL' ) )
	define( 'P2P_PLUGIN_URL', WP_PLUGIN_URL . '/' . P2P_PLUGIN_NAME );
 include_once('text-transfer.php')

/***************** Activation and deactivation hooks *************************/
?>

<link rel="stylesheet" href="<?php echo  P2P_PLUGIN_URL;?>/style.css">
<link rel="stylesheet" href="<?php echo  P2P_PLUGIN_URL;?>/css/jquery.steps.css">

	<?php
register_activation_hook(__FILE__,'wp_p2p_install');
register_deactivation_hook( __FILE__, 'wp_p2p__remove' );
function wp_p2p_install()
{
	global $wpdb;
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."p2p_application_record` (
  `loan_application_no` int(11) NOT NULL AUTO_INCREMENT,
  `loan_amount` varchar(20) NOT NULL,
  `repayment_period` varchar(25) NOT NULL,
  `loan_type` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_date` datetime NOT NULL,
  `additional` varchar(200) NOT NULL,
  `extra` varchar(200) NOT NULL,
  `application_status` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
	PRIMARY KEY (  `loan_application_no` )
)");
$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."p2p_user_details` (
`user_data` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL ,
`loan_application_no` int(11) NOT NULL,
`chinessname` varchar(100) NOT NULL,
`englishname` varchar(100) NOT NULL,
`english_surname` varchar(100) NOT NULL,
`dob` datetime NOT NULL,
`sex` varchar(10) NOT NULL,
`age` varchar(10) NOT NULL,
`email` varchar(200) NOT NULL,
`phonenumber` varchar(20) NOT NULL,
`address` varchar(100) NOT NULL,
`city` varchar(100) NOT NULL,
`company_name` varchar(100) NOT NULL,
`status` int(11) NOT NULL,
PRIMARY KEY (  `user_id` )
)");

}
function wp_p2p__remove()
{

}
register_nav_menus( array(
	'client_menu' => 'Client Menu',
	'partner_menu' => 'Partner Menu',
) );
/******************************* ADD ROLE ************************************************/
$result = add_role(
    'partner',
    __( 'Partner' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => true,
		'delete_posts' => true, // Use false to explicitly deny
	)
);

/**************************************************************************************************************/
add_action( 'admin_menu', 'admin_menu_init_p2p' );
	function admin_menu_init_p2p()
	{
		add_menu_page   ( 'p2plend', 'P2P Lend', 'edit_posts', 'p2plend','display_p2p_dashboard_page', '', 85 );
		add_submenu_page( 'p2plend', 'view_Quotes', 'Quotes Summary', 'publish_posts','view_Quotes', 'display_p2p_main_page' );

	}
/*******************************************************************************************************/
include_once('loan_application.php');
include_once('loan_record.php');
function display_p2p_dashboard_page()
{
	echo 'plugin page';

}
function display_p2p_main_page()
{
	global $user,$wpdb;
	?>
	<div class="container">

		<div class="row">
			<div class="twelve columns">
					<table class="responsive responstable">

					<tr>
						<th><?php echo  P2P_application_no;?></th>
						<th><?php echo  P2P_loan_amount;?></th>
						<th><?php echo  P2P_repayment_period;?></th>
						<th><?php echo  P2P_loan_type;?></th>
						<th><?php echo  P2P_application_date;?></th>
						<th><?php echo  P2P_Zeng_Bidco;?></th>
						<th>name</th>
						<th>Phone no</th>
						<th>Email</th>
						<th><?php echo  P2P_status;?></th>
					 </tr>
					<?php
					$select_data = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."p2p_application_record` WHERE application_status='send'");
					 //  echo '<pre>';print_r($select_data);echo '</pre>';
					foreach( $select_data as $data)
					{
						$app_id = $data->loan_application_no;
						 $selectuser_data = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."p2p_user_details` WHERE loan_application_no='$app_id'");
						 foreach($selectuser_data as $users)
						 {
							 $username = $users->chinessname .'<br/>'.$users->english_surname .' '.$users->englishname;
							 $email =$users->email ;
							 $phone_no = $users->phonenumber ;
						 }
						 echo '<tr><td>'.$data->loan_application_no.'</td>
						 <td>'.$data->loan_amount.'</td>
						 <td>'.$data->repayment_period.' months</td>
						 <td>'.$data->loan_type.'</td>
						 <td>'.$data->application_date.'</td>
						 <td>'.$data->user_id.'</td>
						  <td>'.$username.'</td>
							<td>'.$phone_no.'</td>
							<td>'.$email.'</td>
						 <td>'.$data->application_status.'</td>
 						 </tr>';
					}
					?>


				</table>

			</div>
		</div>

	</div>
	<?php


}
?>
