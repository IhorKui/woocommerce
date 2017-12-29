<?php
/*

Plugin Name: send email

Description:send email with pdf

Plugin URI: 

Version:1

Author: Ihor Kuibida

Author URI: 

*/
include("mpdf61/mpdf.php");
 $order_id = '';
 // get order id
function woocommerce_order_details( $order, $sent_to_admin, $plain_text, $email ) {
global $order_id;     
		$orderData = $order->get_data(); // The Order data
		$order_id = $orderData['id'];
		
}; 

add_action( 'woocommerce_email_order_details', 'woocommerce_order_details', 8, 4 );

// get email content and trasvert it code to pdf file  and save in pdf folder
    function send_message($message){
    	global $order_id;
		$mpdf = new mPDF('utf-8', array( 152.4, 228.6), '12', '', 0, 0, 0, 0, 0, 0); 
			$mpdf->charset_in = 'UTF-8';
			$mpdf->setBasePath(__DIR__."/pdf");
			//$mpdf->setBasePath(__DIR__);
			$mpdf->debug = true;
			$mpdf->showImageErrors = true;
			$mpdf->WriteHTML($message, 0); /*create pdf*/
			$output = $mpdf->Output('mpdf.pdf', 'S');
			$bc_err = file_put_contents(__DIR__ . "/pdf/". $order_id .".pdf", $output);

   	return $message;
     }
add_filter( 'woocommerce_mail_content', 'send_message', 10,1);

//attach saved pdf file to woocommerce email

add_filter('woocommerce_email_attachments', 'pdf_to_email',1000,3);
function pdf_to_email($attachments, $type, $object) {
	global $order_id;
    $your_pdf_path = __DIR__ . "/pdf/". $order_id .".pdf";
    $attachments[] = $your_pdf_path;
     // error_log("лінія 45 type==".$order_id."---object\r\n");
    return $attachments;
} 