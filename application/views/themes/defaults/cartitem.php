<?php $totalqty=0;
if($this->cart->contents()>0){ $totalqty= count($this->cart->contents());} ;?>
<input name="totalitem" type="hidden" id="totalitem" value="<?php echo $totalqty;?>" />
<?php
$calvat=0;
$discount=0;
$itemtotal=0;
$pvat=0;
$multiplletax = array();
if ($cart = $this->cart->contents()){
  $i=0;
      $totalamount=0;
        $subtotal=0;
        $pvat=0;
        foreach ($cart as $item){
          $itemprice= $item['price']*$item['qty'];
          $iteminfo=$this->hungry_model->getiteminfo($item['pid']);
          $mypdiscountprice =0;
          if(!empty($taxinfos)){
          $tx=0;
          if($iteminfo->OffersRate>0){
          $mypdiscountprice=$iteminfo->OffersRate*$itemprice/100;
          }
          $itemvalprice =  ($itemprice-$mypdiscountprice);
          foreach ($taxinfos as $taxinfo)
          {
          $fildname='tax'.$tx;
          if(!empty($iteminfo->$fildname)){

          // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
          $vatcalc= Vatclaculation($itemvalprice,$iteminfo->$fildname);

          $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
          }
          else{
          // $vatcalc=$itemvalprice*$taxinfo['default_value']/100;

          $vatcalc= Vatclaculation($itemvalprice,$taxinfo['default_value']);


          $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
          }
          $pvat=$pvat+$vatcalc;
          $vatcalc =0;
          $tx++;
          }
          }
          else{
          // $vatcalc=$itemprice*$iteminfo->productvat/100;

          $vatcalc= Vatclaculation($itemprice,$iteminfo->productvat);


          $pvat=$pvat+$vatcalc;
          }
          if($iteminfo->OffersRate>0){
            $discal=$itemprice*$iteminfo->OffersRate/100;
            $discount=$discal+$discount;
            }
          else{
            $discal=0;
            $discount=$discount;
            }
          if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
            if(!empty($taxinfos)){
			$addonsarray = explode(',',$item['addonsid']);
			$addonsqtyarray = explode(',',$item['addonsqty']);
			$getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$addonsarray)->get()->result_array();
			$addn=0;
			foreach ($getaddonsdatas as $getaddonsdata) {
			$tax=0;
			
			foreach ($taxinfos as $taxainfo)
			{
			$fildaname='tax'.$tax;
			if(!empty($getaddonsdata[$fildaname])){
			
			// $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$getaddonsdata[$fildaname]/100;

      $avatcalc= Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$getaddonsdata[$fildaname]);

			$multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;
			}
			else{
			// $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$taxainfo['default_value']/100;

      $avatcalc= Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$taxainfo['default_value']);


			$multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;
			}
			$pvat=$pvat+$avatcalc;
			$tax++;
		}
		$addn++;
	}
	}
            $nittotal=$item['addontpr']+$item['alltoppingprice'];
            $itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
            }
          else{
            $nittotal=0;
            $itemprice=$itemprice;
            }
			if(!empty($item['toppingid'])){
									     $toppingarray = explode(',',$item['toppingid']);
										 $toppingnamearray = explode(',',$item['toppingname']);
                                         $toppingpryarray = explode(',',$item['toppingprice']);
										 $t=0;

										 if(!empty($taxinfos)){
                                         $gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$toppingarray)->get()->result_array();
										 //echo $this->db->last_query();
                                         $tpn=0;
                                        foreach ($gettoppingdatas as $gettoppingdata) {
                                          $tptax=0;
                                          foreach ($taxinfos as $taxainfo) 
                                          {

                                            $fildaname='tax'.$tptax;

                                        if(!empty($gettoppingdata[$fildaname])){
                                        	// $tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;
                                          $tvatcalc= Vatclaculation($toppingpryarray[$tpn],$gettoppingdata[$fildaname]);
									                    		$multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;
                                        }
                                        else{
                                          // $tvatcalc=$toppingpryarray[$tpn]*$taxainfo['default_value']/100; 
                                          $tvatcalc= Vatclaculation($toppingpryarray[$tpn],$taxainfo['default_value']);
                                          $multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;  
                                        }

                                      $pvat=$pvat+$tvatcalc;

                                            $tptax++;
                                          }
                                          $tpn++;
                                        }
                                        }
                                 }
          $totalamount=$totalamount+$nittotal;
          $subtotal=$subtotal-$discal+$item['price']*$item['qty'];
        $i++;
?>



<li class="cart-content row">
  <div class="img-box">
    <img src="<?php echo base_url(!empty($iteminfo->small_thumb)?$iteminfo->small_thumb:'assets/img/no-image.png'); ?>" class="img-fluid" alt="<?php echo $item['name'];?>">
  </div>
  <div class="content">
    <h6><?php echo $item['name'];?><br><?php echo $item['size']; ?></h6>
    <p><?php echo $item['qty'];?> X <?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['price']-$discal;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
  </div>
  <div class="delete_box">
    <a onclick="removecart('<?php echo $item['rowid'];?>')" class="serach">
      <i class="fa fa-trash"></i>
    </a>
  </div>
</li>
<?php }
            $itemtotal=$totalamount+$subtotal;
                  
                if(empty($taxinfos)){
                if($this->settinginfo->vat>0 ){
                  // $calvat=$itemtotal*$this->settinginfo->vat/100;
                  $calvat= Vatclaculation($itemtotal,$this->settinginfo->vat);
                }
                else{
                  $calvat=$pvat;
                  }
                }
                else{
                  $calvat=$pvat;
                }
                $multiplletaxvalue=htmlentities(serialize($multiplletax));
?>
<li>
  <table class="table total-cost">
    <tbody>
      <tr>
        <td><?php echo display('subtotal') ?></td>
        <td><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $itemtotal;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
      </tr>
      <tr>
        <td><?php echo display('vat') ?></td>
        <td><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat, $settinginfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
      </tr>
      <tr>
        <td><?php echo display('discount') ?></td>
        <td><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $discount;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
      </tr>
      
    </tbody>
    <tfoot>
    <tr>
      <td><?php echo display('total') ?></td>
      <td><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php 
	    $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
		if(!empty($isvatinclusive)){
			echo $itemtotal;
		}else{
			echo $calvat+$itemtotal;
		}
	  
	  
	  ?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
    </tr>
    </tfoot>
  </table>
</li>
<li class="cart-footer text-right">
  <div class="checkout-box">
    <a href="<?php echo base_url();?>cart" class="simple_btn mt-0"><?php echo display('go_to_checkout') ?></a>
  </div>
</li>
<?php }
                    else{
?>
<li class="cart-header text-center">
  <h6><?php echo display('cart') ?></h6>
</li>
<?php } ?>