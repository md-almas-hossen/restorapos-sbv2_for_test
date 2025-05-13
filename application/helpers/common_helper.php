<?php use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;








if (!function_exists('http_post'))
{

    function http_post($url, $data)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }

}
if (!function_exists('Zatca'))
{
function Zatca($orderid){
		
	   $ci =& get_instance();
	   $settinginfo = $ci->db->select('*')->from('setting')->where('id',2)->get()->row();
	   $billinfo = $ci->db->select('*')->from('bill')->where('order_id',$orderid)->get()->row();
	   $billbyinfo = $ci->db->select('*')->from('user')->where('id',$billinfo->create_by)->get()->row();
	   //$seller=$billbyinfo->firstname.' '.$billbyinfo->lastname;
	   $seller=$settinginfo->storename;
	   $taxnumber=$settinginfo->vattinno;
	   $billdatetime=$billinfo->bill_date.' '.$billinfo->bill_time;
	   $invoicedate=date("Y-m-d\TH:i:s.000\Z", strtotime($billdatetime));
	   
	   if($settinginfo->showdecimal==0){$billtotal=round($billinfo->bill_amount);}else{$billtotal=number_format($billinfo->bill_amount,$settinginfo->showdecimal);}
	   if($settinginfo->showdecimal==0){$taxamount=round($billinfo->VAT);}else{$taxamount=number_format($billinfo->VAT,$settinginfo->showdecimal);}
		$displayQRCodeAsBase64 = GenerateQrCode::fromArray(array(
			new Seller($seller),   
			new TaxNumber($taxnumber),
			new InvoiceDate($invoicedate),
			new InvoiceTotalAmount($billtotal),
			new InvoiceTaxAmount($taxamount)
		))->render();
		return $displayQRCodeAsBase64;
}
}
if (!function_exists('Voucharprefix'))
{
function Voucharprefix($type){		
	   $ci =& get_instance();
	   $row=$ci->db->select("*")->from('tbl_vouchartype')->where('id',$type)->get()->row();	   
	   return $row->PrefixCode;
}
}

if (!function_exists('generateQrCode'))
{
  function generateQrCode($baseurl,$tableid){
    require_once(APPPATH . 'libraries/phpqrcode/qrlib.php'); 
    $qrDirectory = FCPATH . 'assets/img/';
    if (!is_dir($qrDirectory) || !is_writable($qrDirectory)) {
        die('QR code directory is not writable.');
    }

    $PNG_WEB_DIR =  base_url(). 'assets/img/';

    // Define the file prefix
    $prefix = $tableid . 'qrcode_';

    // Delete any old QR code images with the same prefix (e.g., 'v1qrcode_')
    $files = glob($qrDirectory . $prefix . '*.png');  // Find all files with the prefix
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);  // Delete the file
        }
    }
    
    // $filename = $qrDirectory .$tableid.'qrcode.png';
    $filename = $qrDirectory . $tableid . 'qrcode_' . time() . '.png';

    //echo $filename = $PNG_TEMP_DIR.'qrcode.png';
    $errorCorrectionLevel = 'H';
    $matrixPointSize = 10;

    //echo $PNG_WEB_DIR.basename($filename);
    QRcode::png($baseurl, $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
    return $PNG_WEB_DIR.basename($filename);

  }
}

// if (!function_exists('generateQrCode'))
// {
// function generateQrCode($baseurl,$tableid){
//   require_once(APPPATH . 'libraries/phpqrcode/qrlib.php'); 
//   $qrDirectory = FCPATH . 'assets/img/';
//   if (!is_dir($qrDirectory) || !is_writable($qrDirectory)) {
//       die('QR code directory is not writable.');
//   }

//   $PNG_WEB_DIR =  base_url(). 'assets/img/';
  
//   $filename = $qrDirectory .$tableid.'qrcode.png';
//   //echo $filename = $PNG_TEMP_DIR.'qrcode.png';
//   $errorCorrectionLevel = 'H';
//   $matrixPointSize = 10;

//   //echo $PNG_WEB_DIR.basename($filename);
//   QRcode::png($baseurl, $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
//    return $PNG_WEB_DIR.basename($filename);

// }
// }
if (!function_exists('Vatclaculation'))
{
function Vatclaculation($amount,$vatpercernt){
		
	   $ci =& get_instance();
	   $isvatinclusive=$ci->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
	   if(!empty($isvatinclusive)){
			$vatcalinc=$vatpercernt/100;
			$totalincvat=1+$vatcalinc;
			$vatcalc=$amount-($amount/$totalincvat);
		}else{
		$vatcalc=$amount*$vatpercernt/100; 	
		}
	   return $vatcalc;
}
}
if (!function_exists('NumberShow'))
{
function numbershow($amount,$format){
		if($format==0){
				return round($amount);
			}else{
				return number_format($amount,$format);
			}
}
}
if (!function_exists('QuantityShow'))
{
function quantityshow($qty,$format){
		if($format==1){
				return $qty;
			}else{
				return round($qty);
			}
}
}
if (!function_exists('RandomPassword'))
{
function RandomPassword(){
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while($i <= 7){
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass.$tmp;
			$i++;
		}
		return $pass;
	}
}
if (!function_exists('time_elapsed'))
{

    function time_elapsed($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k)
            {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            }
            else
            {
                unset($string[$k]);
            }
        }

        if (!$full)
        {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}
if (!function_exists('SubscribeEmail'))
{
	function SubscribeEmail($email){
	   
$emailcontent='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Subscription/Contact</title>
    <style>
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }
      table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
        font-size: 16px !important;
      }
      table[class=body] .wrapper,
            table[class=body] .article {
        padding: 10px !important;
      }
      table[class=body] .content {
        padding: 0 !important;
      }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }
      table[class=body] .btn table {
        width: 100% !important;
      }
      table[class=body] .btn a {
        width: 100% !important;
      }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    @media all {
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
        line-height: 100%;
      }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }
      .btn-primary table td:hover {
        background-color: #34495e !important;
      }
      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }
    }
    </style>
  </head>
  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi '.$email.',</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Thanks for your subscription</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                          <tbody>
                            
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            
            <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';
	   
	return $emailcontent;
	}
}
if (!function_exists('ReservationEmail'))
{
	function ReservationEmail($id,$mobile=null){
	  $ci =& get_instance();
	  $reservesql = $ci->db->query("SELECT * FROM tblreservation where reserveid='".$id."'"); 
    $reserveinfo= $reservesql->row();
    $resql = $ci->db->query("SELECT * FROM customer_info where customer_id='".$reserveinfo->cid."'");	
	  $resinfo= $resql->row();
	  $tablesql = $ci->db->query("SELECT * FROM rest_table where tableid='".$reserveinfo->tableid."'");	
	  $tableinfo= $tablesql->row();
    $newdate= date('Y-m-d' , strtotime($reserveinfo->reserveday));

    
$emailcontent='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Subscription/Contact</title>
    <style>
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }
      table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
        font-size: 16px !important;
      }
      table[class=body] .wrapper,
            table[class=body] .article {
        padding: 10px !important;
      }
      table[class=body] .content {
        padding: 0 !important;
      }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }
      table[class=body] .btn table {
        width: 100% !important;
      }
      table[class=body] .btn a {
        width: 100% !important;
      }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    @media all {
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
        line-height: 100%;
      }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }
      .btn-primary table td:hover {
        background-color: #34495e !important;
      }
      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }
    }
    </style>
  </head>
  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi '.$resinfo->customer_name.',</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Phone:'.$mobile.'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Date:'.$newdate.'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Number Of People:'.$reserveinfo->person_capicity.'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Your Reservation is Booked.Please inform me if anything change.\r\n Thank You</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                          <tbody>
                            
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            
            <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';

	return $emailcontent;
	}
}
if (!function_exists('SendorderEmail'))
{
	function SendorderEmail($orderid,$customerid){
	   $ci =& get_instance();
	   $ordersql = $ci->db->query("SELECT * FROM customer_order where order_id='".$orderid."'");	
	   $orderinfo= $ordersql->row();
       $rowdt = $ci->db->query("SELECT order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.ProductImage,variant.variantid,variant.variantName,variant.price FROM order_menu Left Join item_foods ON order_menu.menu_id=item_foods.ProductsID Left Join variant ON order_menu.varientid=variant.variantid where order_menu.order_id='".$orderid."'");	
	   $oredritem= $rowdt->result();
	   $resql = $ci->db->query("SELECT * FROM customer_info where customer_id='".$customerid."'");	
	   $resinfo= $resql->row();
	   $bill = $ci->db->query("SELECT * FROM bill where order_id='".$orderid."'");	
	   $billinfo= $bill->row();
	   $items='';
	   $subtotal=0;
	   foreach($oredritem as $item){
		   $getitemin= $ci->db->query("SELECT item_foods.ProductsID,item_foods.ProductName,variant.variantid,variant.variantName,variant.price FROM item_foods Left Join variant ON item_foods.ProductsID=variant.menuid where item_foods.ProductsID='".$item->menu_id."' AND variant.variantid='".$item->varientid."'");
		   $itemininfo= $getitemin->row();	
		   if(!empty($item->add_on_id)){
			   
			   $addons=explode(",",$item->add_on_id);
			   $addonsqtym=explode(",",$item->addonsqty);
			     $x=0;
				 $addonsname='';
				 $addonsprice='';
				 $addonsqty='';
				 $adstotalprice='';
				 foreach($addons as $addonsid){
					  $getaddons = $ci->db->query("SELECT * FROM add_ons where add_on_id='".$addonsid."'");	
	                  $adonsinfo= $getaddons->row();
					  $addonsname.=$adonsinfo->add_on_name.',';
					  $addonsprice.=$adonsinfo->price.',';
					  $addonsqty.=$addonsqtym[$x].',';
					  $adstotalprice=$adonsinfo->price*$addonsqtym[$x];
					  $x++;
				 }
				  $addonsname=trim($addonsname,',');
				  $addonsprice=trim($addonsprice,',');
				  $addonsqty=trim($addonsqty,',');
				  $isaddons='Addons:'.$addonsname.' - price:'.$adstotalprice;
				  $totalp=($item->menuqty*$itemininfo->price)+$adstotalprice;
			   }
			else{
				$isaddons="";
				$adstotalprice="";
				$totalp=$item->menuqty*$itemininfo->price;
				}
	   $subtotal=$subtotal+$totalp;
	   $items.='<tr><td>'.$itemininfo->ProductName.' '.$isaddons.'</td><td>'.$itemininfo->variantName.'</td><td>'.$item->menuqty.'</td><td>'.$itemininfo->price.'</td><td>'.$totalp.'</td></tr>';
	   }
	   
$emailcontent='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Subscription/Contact</title>
    <style>
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }
      table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
        font-size: 16px !important;
      }
      table[class=body] .wrapper,
            table[class=body] .article {
        padding: 10px !important;
      }
      table[class=body] .content {
        padding: 0 !important;
      }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }
      table[class=body] .btn table {
        width: 100% !important;
      }
      table[class=body] .btn a {
        width: 100% !important;
      }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    @media all {
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
        line-height: 100%;
      }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }
      .btn-primary table td:hover {
        background-color: #34495e !important;
      }
      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }
    }
    </style>
  </head>
  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi '.$resinfo->customer_name.',</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Thanks for Order.Below Your order Item information.</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                          <tbody>
                            <tr>
								<td>Item Name</td>
								<td>Varient</td>
								<td>quantity</td>
								<td align="right">Unit Price</td>
								<td align="right">Total Price</td>
							</tr>
							'.$items.'
							<tr>
								<td colspan="4" align="right">Subtotal</td>
								<td align="right">'.$subtotal.'</td>
							</tr>
                             <tr>
								<td colspan="4" align="right">Vat/Tax</td>
								<td align="right">'.$billinfo->VAT.'</td>
							</tr>
                            <tr>
								<td colspan="4" align="right">Discount</td>
								<td align="right">'.$billinfo->discount.'</td>
							</tr>
                            <tr>
								<td colspan="4" align="right">Service charge</td>
								<td align="right">'.$billinfo->service_charge.'</td>
							</tr>
                            <tr>
								<td colspan="4" align="right">Grand Total</td>
								<td align="right">'.$orderinfo->totalamount.'</td>
							</tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            
            <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';
	   
	return $emailcontent;
	}
}
if (!function_exists('SendSMS'))
{

    function SendSMS($Phone, $SMS)
    {
				// Login Info
				
    }

}
if (!function_exists('generateRandomStr'))
{
function generateRandomStr($length = 4) {
        $UpperStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $LowerStr = "abcdefghijklmnopqrstuvwxyz";
        $numbers = "0123456789";
        
        $characters = $numbers;
        $charactersLength = strlen($characters);
        $randomStr = null;
        for ($i = 0; $i < $length; $i++) {
            $randomStr .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomStr;
    }
}

if (!function_exists('get_tableinfobyfield')) {

  function get_tableinfobyfield($tablename, $fieldname, $value) {
      $ci = & get_instance();
      $ci->db->select('*');
      $ci->db->where($fieldname, $value);
      $query = $ci->db->get($tablename);
      return $query->row();
  }
}


if (!function_exists('getPrefixSetting')) {

  function getPrefixSetting() {
      $ci = & get_instance();
      $ci->db->select('*');
      $query = $ci->db->get('prefix_setting');
      return $query->row();
  }
}


if (!function_exists('d')){
  function d($data = ''){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
  }
}



if (!function_exists('number_generator')) {

  function number_generator($tablename = null, $fieldname = null) {
    $CI = & get_instance();
    $CI->db->select_max($fieldname, $fieldname);
    $query = $CI->db->get($tablename);
    $result = $query->result_array();
    $invoice_no = $result[0][$fieldname];
    if ($invoice_no != '') {
        $invoice_no = $invoice_no + 1;
    } else {
        $invoice_no = 1000;
    }
    return $invoice_no;
  }
}

if (!function_exists('modules_mx_load')) {
  /**
   * Load module with mx loader
   *
   * @param string $modules_name
   * @return bool
   */
  function modules_mx_load(string $modules_name): bool
  {
    if(file_exists(sprintf("%smodules/%s/assets/data/env", APPPATH, $modules_name))) {
      $mx_loader_file = sprintf("%smodules/%s/config/mx_loader.php", APPPATH, $modules_name);

      if (file_exists($mx_loader_file)) {
        require_once $mx_loader_file;
      }

      return true;
    }

    return false;
  }
}




if (!function_exists('auto_manual_voucher_psoting')) {
  /**
   * Load module with mx loader
   *
   * @param string $modules_name
   * @return bool
   */

  // type 1 = sales, type 2 = purchase
  function auto_manual_voucher_posting($type)
  {
      $ci =& get_instance();

      $setting = $ci->db->select('posting_for_sales_voucher, posting_for_purchase_voucher')->from('setting')->get()->row_array();
      
      if (!$setting) {
          return false; 
      }
      
      if ($type == 1) {
          return $setting['posting_for_sales_voucher'] == 1;
      } else {
          return $setting['posting_for_purchase_voucher'] == 1;
      }
  }

}

/**
 * Random Order Number when taking orders
 *
 * @param integer $order_id
 * @return string
 */
if (!function_exists('random_order_number')) {

  function random_order_number($order_id)
  {
      // Ensure order_id is treated as a string
      $order_id = (string) $order_id;
  
      // Determine how many digits are in the order_id
      $order_id_length = strlen($order_id);
  
      // Calculate how many digits to generate to keep total length 8
      $random_length = 8 - $order_id_length;
  
      // Generate a base from current microtime
      $time = microtime(true);
  
      // Hash it and take digits only
      $hash = preg_replace('/\D/', '', md5($time));
  
      // Take the needed number of digits
      $random_digits = substr($hash, 0, $random_length);
  
      // Concatenate and return
      return $random_digits . $order_id;
  }

}