<?php
/************************  LOAN RECORD DISPLAY***************************/

function show_application_record_user()
{
  ?><script src="<?php echo  P2P_PLUGIN_URL;?>/js/jquery-1.9.1.min.js"></script>
   <script src="<?php echo  P2P_PLUGIN_URL;?>/js/responsive-tables.js"></script>
   	<link rel="stylesheet" href="<?php echo  P2P_PLUGIN_URL;?>/css/responsive-tables.css">
   <?php
  global $wpdb,$user;
  global $current_user;
  $current_user = get_currentuserinfo();
  $user_id = $current_user->ID ;
  $user_level =$current_user->user_level ;

    //admin actions
    ?>
    <div class="container">

      <div class="row">
        <div class="twelve columns">
            <table class="responsive">

            <tr>
              <th><?php echo  P2P_application_no;?></th>
              <th><?php echo  P2P_loan_amount;?></th>
              <th><?php echo  P2P_repayment_period;?></th>
              <th><?php echo  P2P_loan_type;?></th>
              <th><?php echo  P2P_application_date;?></th>
              <th><?php echo  P2P_Zeng_Bidco;?></th>
              <th><?php echo  P2P_status;?></th>
             </tr>
            <?php
            $select_data = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."p2p_application_record`");
             //  echo '<pre>';print_r($select_data);echo '</pre>';
            foreach( $select_data as $data)
            {
               echo '<tr><td>'.$data->loan_application_no.'</td>
               <td>'.$data->loan_amount.'</td>
               <td>'.$data->repayment_period.' months</td>
               <td>'.$data->loan_type.'</td>
               <td>'.$data->application_date.'</td>
               <td>'.$data->user_id.'</td>
                <td>'.$data->status.'</td>
               </tr>';
            }
            ?>


          </table>

        </div>
      </div>

    </div>
<?php



}
add_shortcode( 'p2p_user_record_display', 'show_application_record_user' );
?>
