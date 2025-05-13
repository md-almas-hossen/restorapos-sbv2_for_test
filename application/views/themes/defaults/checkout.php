<?php $webinfo = $this->webinfo;

$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}
$defaultship=$this->session->userdata('shippingid');
$shiptype=$this->session->userdata('shiptype');
$shippingaddress=$this->session->userdata('shippingaddress');

	if($shiptype==3){
		$address=$shippingaddress;
	}else{
		$address=$this->settinginfo->address;
		}
$intinfo=$this->db->select('*')->from('shipping_method')->where('ship_id', $defaultship)->get()->row();
$slpayment=explode(',',$intinfo->payment_method);
$pvalue=$slpayment[0];
foreach($slpayment as $checkmethod){
		if($checkmethod == 4){
			$pvalue=4;
		}
	}?>
    
<div class="modal fade" id="lostpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo display('forgot_password'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body passwordupdate">
                <div class="form-group">
                    <label class="control-label" for="user_email"><?php echo display('email'); ?></label>
                    <input type="text" id="user_email2" class="form-control" name="user_email2">
                </div>
                <a onclick="lostpassword();" class="btn btn-success btn-sm lost-pass"><?php echo display('submit'); ?></a>
            </div>
        </div>
    </div>
</div>
<!--========== Checkout area ==========-->
<section class="checkout_area sect_pad">
    <div class="container">
        <?php if ($this->session->flashdata('exception')) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('exception') ?>
        </div>
        <?php }
        
        ?>
         <?php echo form_open('hungry/placeorder','method="post" class="row"')?>
            <div class="col-xl-8 col-lg-7">
                <?php if (empty($this->session->userdata('CusUserID'))) { ?>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                            <i class="fa fa-question-circle"></i> <?php echo display('returning_customer') ?><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <?php echo display('click_login') ?></a>
                            </h6>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse show">
                            <div class="panel-body">
                                <p><?php echo display('checkout_msg') ?></p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="user_email"><?php echo display('username_or_email') ?></label>
                                            <input type="text" id="user_email" class="form-control" name="user_email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="u_pass"><?php echo display('password') ?> <abbr class="required" title="required">*</abbr></label>
                                            <input type="password" id="u_pass" class="form-control" name="u_pass">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="checkbox checkbox-success">
                                            <input id="brand1" type="checkbox">
                                            <label for="brand1"><?php echo display('remember_me') ?></label>
                                        </div>
                                        <?php $facrbooklogn = $this->db->where('directory', 'facebooklogin')->where('status', 1)->get('module')->num_rows();
                                        if ($facrbooklogn == 1) {
                                        ?>
                                        <a class="btn btn-primary btn-sm  search text-white" href="<?php echo base_url('facebooklogin/facebooklogin/index') ?>"><i class="fa fa-facebook pr-1"></i><?php echo display('facebook_login') ?></a>
                                        <?php } ?>
                                        <a class="btn btn-success btn-sm search" onclick="logincustomer();"><?php echo display('login') ?></a>
                                        <a class="lost-pass" data-toggle="modal" data-target="#lostpassword" data-dismiss="modal"><?php echo display('forgot_password') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="billing-form mt-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="billing-title"><?php echo display('billing_address') ?></h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-error">
                                <label class="control-label" for="f_name"><?php echo display('first_name') ?> <abbr class="required" title="required">*</abbr></label>
                                <?php
                                $cusfname = "";
                                $cusfname = $this->session->has_userdata('cusfname') ? $this->session->userdata('cusfname') : NULL; ?>
                                <input type="text" id="f_name" class="form-control" name="f_name" value="<?php echo (!empty($billinginfo->firstname) ? $billinginfo->firstname : null) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="l_name"><?php echo display('last_name') ?> <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="l_name" class="form-control" name="l_name" value="<?php echo (!empty($billinginfo->lastname) ? $billinginfo->lastname : null) ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo display('country') ?></label>
                                <select name="country" id="country">
                                    <option value=""><?php echo display('select_country') ?></option>
                                    <?php if (!empty($countryinfo)) {
                                    foreach ($countryinfo as $mcountry) {
                                    ?>
                                    <option value="<?php echo $mcountry->countryname; ?>" data-id="<?php echo $mcountry->countryid; ?>"><?php echo $mcountry->countryname; ?></option>
                                    <?php }
                                    }  ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="email"><?php echo display('email') ?> <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="email" name="email" class="form-control" value="<?php echo (!empty($billinginfo->email) ? $billinginfo->email : null) ?>">
                            </div>
                            <div class="form-group">
									<label for="countryCode" class="form-label"><?php echo "Country Code"; ?></label>
                                    <select name="countryCode" class="form-control select2" id="countrycode">
                                        <option <?php if($webinfo->country=="GB"){ echo "selected";}?> data-countryCode="GB" value="44" Selected>UK (+44)</option>
                                    <option <?php if($webinfo->country=="US"){ echo "selected";}?> data-countryCode="US" value="1">USA (+1)</option>
                                    <optgroup label="Other countries">
                                    <option <?php if($webinfo->country=="DZ"){ echo "selected";}?> data-countryCode="DZ" value="213">Algeria (+213)</option>
                                    <option <?php if($webinfo->country=="AD"){ echo "selected";}?> data-countryCode="AD" value="376">Andorra (+376)</option>
                                    <option <?php if($webinfo->country=="AO"){ echo "selected";}?> data-countryCode="AO" value="244">Angola (+244)</option>
                                    <option <?php if($webinfo->country=="AI"){ echo "selected";}?> data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                    <option <?php if($webinfo->country=="AG"){ echo "selected";}?> data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                    <option <?php if($webinfo->country=="AR"){ echo "selected";}?> data-countryCode="AR" value="54">Argentina (+54)</option>
                                    <option <?php if($webinfo->country=="AM"){ echo "selected";}?> data-countryCode="AM" value="374">Armenia (+374)</option>
                                    <option <?php if($webinfo->country=="AW"){ echo "selected";}?> data-countryCode="AW" value="297">Aruba (+297)</option>
                                    <option <?php if($webinfo->country=="AU"){ echo "selected";}?> data-countryCode="AU" value="61">Australia (+61)</option>
                                    <option <?php if($webinfo->country=="AT"){ echo "selected";}?> data-countryCode="AT" value="43">Austria (+43)</option>
                                    <option <?php if($webinfo->country=="AZ"){ echo "selected";}?> data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                    <option <?php if($webinfo->country=="BS"){ echo "selected";}?> data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                    <option <?php if($webinfo->country=="BH"){ echo "selected";}?> data-countryCode="BH" value="973">Bahrain (+973)</option>
                                    <option <?php if($webinfo->country=="BD"){ echo "selected";}?> data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                    <option <?php if($webinfo->country=="BB"){ echo "selected";}?> data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                    <option <?php if($webinfo->country=="BY"){ echo "selected";}?> data-countryCode="BY" value="375">Belarus (+375)</option>
                                    <option <?php if($webinfo->country=="BE"){ echo "selected";}?> data-countryCode="BE" value="32">Belgium (+32)</option>
                                    <option <?php if($webinfo->country=="BZ"){ echo "selected";}?> data-countryCode="BZ" value="501">Belize (+501)</option>
                                    <option <?php if($webinfo->country=="BJ"){ echo "selected";}?> data-countryCode="BJ" value="229">Benin (+229)</option>
                                    <option <?php if($webinfo->country=="BM"){ echo "selected";}?> data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                    <option <?php if($webinfo->country=="BT"){ echo "selected";}?> data-countryCode="BT" value="975">Bhutan (+975)</option>
                                    <option <?php if($webinfo->country=="BO"){ echo "selected";}?> data-countryCode="BO" value="591">Bolivia (+591)</option>
                                    <option <?php if($webinfo->country=="BA"){ echo "selected";}?> data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                    <option <?php if($webinfo->country=="BW"){ echo "selected";}?> data-countryCode="BW" value="267">Botswana (+267)</option>
                                    <option <?php if($webinfo->country=="BR"){ echo "selected";}?> data-countryCode="BR" value="55">Brazil (+55)</option>
                                    <option <?php if($webinfo->country=="BN"){ echo "selected";}?> data-countryCode="BN" value="673">Brunei (+673)</option>
                                    <option <?php if($webinfo->country=="BG"){ echo "selected";}?> data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                    <option <?php if($webinfo->country=="BF"){ echo "selected";}?> data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                    <option <?php if($webinfo->country=="BI"){ echo "selected";}?> data-countryCode="BI" value="257">Burundi (+257)</option>
                                    <option <?php if($webinfo->country=="KH"){ echo "selected";}?> data-countryCode="KH" value="855">Cambodia (+855)</option>
                                    <option <?php if($webinfo->country=="CM"){ echo "selected";}?> data-countryCode="CM" value="237">Cameroon (+237)</option>
                                    <option <?php if($webinfo->country=="CA"){ echo "selected";}?> data-countryCode="CA" value="1">Canada (+1)</option>
                                    <option <?php if($webinfo->country=="CV"){ echo "selected";}?> data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                    <option <?php if($webinfo->country=="KY"){ echo "selected";}?> data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                    <option <?php if($webinfo->country=="CF"){ echo "selected";}?> data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                    <option <?php if($webinfo->country=="CL"){ echo "selected";}?> data-countryCode="CL" value="56">Chile (+56)</option>
                                    <option <?php if($webinfo->country=="CN"){ echo "selected";}?> data-countryCode="CN" value="86">China (+86)</option>
                                    <option <?php if($webinfo->country=="CO"){ echo "selected";}?> data-countryCode="CO" value="57">Colombia (+57)</option>
                                    <option <?php if($webinfo->country=="KM"){ echo "selected";}?> data-countryCode="KM" value="269">Comoros (+269)</option>
                                    <option <?php if($webinfo->country=="CG"){ echo "selected";}?> data-countryCode="CG" value="242">Congo (+242)</option>
                                    <option <?php if($webinfo->country=="CK"){ echo "selected";}?> data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                    <option <?php if($webinfo->country=="CR"){ echo "selected";}?> data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                    <option <?php if($webinfo->country=="HR"){ echo "selected";}?> data-countryCode="HR" value="385">Croatia (+385)</option>
                                    <option <?php if($webinfo->country=="CU"){ echo "selected";}?> data-countryCode="CU" value="53">Cuba (+53)</option>
                                    <option <?php if($webinfo->country=="CY"){ echo "selected";}?> data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                    <option <?php if($webinfo->country=="CY"){ echo "selected";}?> data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                    <option <?php if($webinfo->country=="CZ"){ echo "selected";}?> data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                    <option <?php if($webinfo->country=="DK"){ echo "selected";}?> data-countryCode="DK" value="45">Denmark (+45)</option>
                                    <option <?php if($webinfo->country=="DJ"){ echo "selected";}?> data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                    <option <?php if($webinfo->country=="DM"){ echo "selected";}?> data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                    <option <?php if($webinfo->country=="DO"){ echo "selected";}?> data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                    <option <?php if($webinfo->country=="EC"){ echo "selected";}?> data-countryCode="EC" value="593">Ecuador (+593)</option>
                                    <option <?php if($webinfo->country=="EG"){ echo "selected";}?> data-countryCode="EG" value="20">Egypt (+20)</option>
                                    <option <?php if($webinfo->country=="SV"){ echo "selected";}?> data-countryCode="SV" value="503">El Salvador (+503)</option>
                                    <option <?php if($webinfo->country=="GQ"){ echo "selected";}?> data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                    <option <?php if($webinfo->country=="ER"){ echo "selected";}?> data-countryCode="ER" value="291">Eritrea (+291)</option>
                                    <option <?php if($webinfo->country=="EE"){ echo "selected";}?> data-countryCode="EE" value="372">Estonia (+372)</option>
                                    <option <?php if($webinfo->country=="ET"){ echo "selected";}?> data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                    <option <?php if($webinfo->country=="FK"){ echo "selected";}?> data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                    <option <?php if($webinfo->country=="FO"){ echo "selected";}?> data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                    <option <?php if($webinfo->country=="FJ"){ echo "selected";}?> data-countryCode="FJ" value="679">Fiji (+679)</option>
                                    <option <?php if($webinfo->country=="FI"){ echo "selected";}?> data-countryCode="FI" value="358">Finland (+358)</option>
                                    <option <?php if($webinfo->country=="FR"){ echo "selected";}?> data-countryCode="FR" value="33">France (+33)</option>
                                    <option <?php if($webinfo->country=="GF"){ echo "selected";}?> data-countryCode="GF" value="594">French Guiana (+594)</option>
                                    <option <?php if($webinfo->country=="PF"){ echo "selected";}?> data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                    <option <?php if($webinfo->country=="GA"){ echo "selected";}?> data-countryCode="GA" value="241">Gabon (+241)</option>
                                    <option <?php if($webinfo->country=="GM"){ echo "selected";}?> data-countryCode="GM" value="220">Gambia (+220)</option>
                                    <option <?php if($webinfo->country=="GE"){ echo "selected";}?> data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                    <option <?php if($webinfo->country=="DE"){ echo "selected";}?> data-countryCode="DE" value="49">Germany (+49)</option>
                                    <option <?php if($webinfo->country=="GH"){ echo "selected";}?> data-countryCode="GH" value="233">Ghana (+233)</option>
                                    <option <?php if($webinfo->country=="GI"){ echo "selected";}?> data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                    <option <?php if($webinfo->country=="GR"){ echo "selected";}?> data-countryCode="GR" value="30">Greece (+30)</option>
                                    <option <?php if($webinfo->country=="GL"){ echo "selected";}?> data-countryCode="GL" value="299">Greenland (+299)</option>
                                    <option <?php if($webinfo->country=="GD"){ echo "selected";}?> data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                    <option <?php if($webinfo->country=="GP"){ echo "selected";}?> data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                    <option <?php if($webinfo->country=="GU"){ echo "selected";}?> data-countryCode="GU" value="671">Guam (+671)</option>
                                    <option <?php if($webinfo->country=="GT"){ echo "selected";}?> data-countryCode="GT" value="502">Guatemala (+502)</option>
                                    <option <?php if($webinfo->country=="GN"){ echo "selected";}?> data-countryCode="GN" value="224">Guinea (+224)</option>
                                    <option <?php if($webinfo->country=="GW"){ echo "selected";}?> data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                    <option <?php if($webinfo->country=="GY"){ echo "selected";}?> data-countryCode="GY" value="592">Guyana (+592)</option>
                                    <option <?php if($webinfo->country=="HT"){ echo "selected";}?> data-countryCode="HT" value="509">Haiti (+509)</option>
                                    <option <?php if($webinfo->country=="HN"){ echo "selected";}?> data-countryCode="HN" value="504">Honduras (+504)</option>
                                    <option <?php if($webinfo->country=="HK"){ echo "selected";}?> data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                    <option <?php if($webinfo->country=="HU"){ echo "selected";}?> data-countryCode="HU" value="36">Hungary (+36)</option>
                                    <option <?php if($webinfo->country=="IS"){ echo "selected";}?> data-countryCode="IS" value="354">Iceland (+354)</option>
                                    <option <?php if($webinfo->country=="IN"){ echo "selected";}?> data-countryCode="IN" value="91">India (+91)</option>
                                    <option <?php if($webinfo->country=="ID"){ echo "selected";}?> data-countryCode="ID" value="62">Indonesia (+62)</option>
                                    <option <?php if($webinfo->country=="IR"){ echo "selected";}?> data-countryCode="IR" value="98">Iran (+98)</option>
                                    <option <?php if($webinfo->country=="IQ"){ echo "selected";}?> data-countryCode="IQ" value="964">Iraq (+964)</option>
                                    <option <?php if($webinfo->country=="IE"){ echo "selected";}?> data-countryCode="IE" value="353">Ireland (+353)</option>
                                    <option <?php if($webinfo->country=="IL"){ echo "selected";}?> data-countryCode="IL" value="972">Israel (+972)</option>
                                    <option <?php if($webinfo->country=="IT"){ echo "selected";}?> data-countryCode="IT" value="39">Italy (+39)</option>
                                    <option <?php if($webinfo->country=="JM"){ echo "selected";}?> data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                    <option <?php if($webinfo->country=="JP"){ echo "selected";}?> data-countryCode="JP" value="81">Japan (+81)</option>
                                    <option <?php if($webinfo->country=="JO"){ echo "selected";}?> data-countryCode="JO" value="962">Jordan (+962)</option>
                                    <option <?php if($webinfo->country=="KZ"){ echo "selected";}?> data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                    <option <?php if($webinfo->country=="KE"){ echo "selected";}?> data-countryCode="KE" value="254">Kenya (+254)</option>
                                    <option <?php if($webinfo->country=="KI"){ echo "selected";}?> data-countryCode="KI" value="686">Kiribati (+686)</option>
                                    <option <?php if($webinfo->country=="KP"){ echo "selected";}?> data-countryCode="KP" value="850">Korea North (+850)</option>
                                    <option <?php if($webinfo->country=="KR"){ echo "selected";}?> data-countryCode="KR" value="82">Korea South (+82)</option>
                                    <option <?php if($webinfo->country=="KW"){ echo "selected";}?> data-countryCode="KW" value="965">Kuwait (+965)</option>
                                    <option <?php if($webinfo->country=="KG"){ echo "selected";}?> data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                    <option <?php if($webinfo->country=="LA"){ echo "selected";}?> data-countryCode="LA" value="856">Laos (+856)</option>
                                    <option <?php if($webinfo->country=="LV"){ echo "selected";}?> data-countryCode="LV" value="371">Latvia (+371)</option>
                                    <option <?php if($webinfo->country=="LB"){ echo "selected";}?> data-countryCode="LB" value="961">Lebanon (+961)</option>
                                    <option <?php if($webinfo->country=="LS"){ echo "selected";}?> data-countryCode="LS" value="266">Lesotho (+266)</option>
                                    <option <?php if($webinfo->country=="LR"){ echo "selected";}?> data-countryCode="LR" value="231">Liberia (+231)</option>
                                    <option <?php if($webinfo->country=="LY"){ echo "selected";}?> data-countryCode="LY" value="218">Libya (+218)</option>
                                    <option <?php if($webinfo->country=="LI"){ echo "selected";}?> data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                    <option <?php if($webinfo->country=="LT"){ echo "selected";}?> data-countryCode="LT" value="370">Lithuania (+370)</option>
                                    <option <?php if($webinfo->country=="LU"){ echo "selected";}?> data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                    <option <?php if($webinfo->country=="MO"){ echo "selected";}?> data-countryCode="MO" value="853">Macao (+853)</option>
                                    <option <?php if($webinfo->country=="MK"){ echo "selected";}?> data-countryCode="MK" value="389">Macedonia (+389)</option>
                                    <option <?php if($webinfo->country=="MG"){ echo "selected";}?> data-countryCode="MG" value="261">Madagascar (+261)</option>
                                    <option <?php if($webinfo->country=="MW"){ echo "selected";}?> data-countryCode="MW" value="265">Malawi (+265)</option>
                                    <option <?php if($webinfo->country=="MY"){ echo "selected";}?> data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    <option <?php if($webinfo->country=="MV"){ echo "selected";}?> data-countryCode="MV" value="960">Maldives (+960)</option>
                                    <option <?php if($webinfo->country=="ML"){ echo "selected";}?> data-countryCode="ML" value="223">Mali (+223)</option>
                                    <option <?php if($webinfo->country=="MT"){ echo "selected";}?> data-countryCode="MT" value="356">Malta (+356)</option>
                                    <option <?php if($webinfo->country=="MH"){ echo "selected";}?> data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                    <option <?php if($webinfo->country=="MQ"){ echo "selected";}?> data-countryCode="MQ" value="596">Martinique (+596)</option>
                                    <option <?php if($webinfo->country=="MR"){ echo "selected";}?> data-countryCode="MR" value="222">Mauritania (+222)</option>
                                    <option <?php if($webinfo->country=="YT"){ echo "selected";}?> data-countryCode="YT" value="269">Mayotte (+269)</option>
                                    <option <?php if($webinfo->country=="MX"){ echo "selected";}?> data-countryCode="MX" value="52">Mexico (+52)</option>
                                    <option <?php if($webinfo->country=="FM"){ echo "selected";}?> data-countryCode="FM" value="691">Micronesia (+691)</option>
                                    <option <?php if($webinfo->country=="MD"){ echo "selected";}?> data-countryCode="MD" value="373">Moldova (+373)</option>
                                    <option <?php if($webinfo->country=="MC"){ echo "selected";}?> data-countryCode="MC" value="377">Monaco (+377)</option>
                                    <option <?php if($webinfo->country=="MN"){ echo "selected";}?> data-countryCode="MN" value="976">Mongolia (+976)</option>
                                    <option <?php if($webinfo->country=="MS"){ echo "selected";}?> data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                    <option <?php if($webinfo->country=="MA"){ echo "selected";}?> data-countryCode="MA" value="212">Morocco (+212)</option>
                                    <option <?php if($webinfo->country=="MZ"){ echo "selected";}?> data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                    <option <?php if($webinfo->country=="MN"){ echo "selected";}?> data-countryCode="MN" value="95">Myanmar (+95)</option>
                                    <option <?php if($webinfo->country=="NA"){ echo "selected";}?> data-countryCode="NA" value="264">Namibia (+264)</option>
                                    <option <?php if($webinfo->country=="NR"){ echo "selected";}?> data-countryCode="NR" value="674">Nauru (+674)</option>
                                    <option <?php if($webinfo->country=="NP"){ echo "selected";}?> data-countryCode="NP" value="977">Nepal (+977)</option>
                                    <option <?php if($webinfo->country=="NL"){ echo "selected";}?> data-countryCode="NL" value="31">Netherlands (+31)</option>
                                    <option <?php if($webinfo->country=="NC"){ echo "selected";}?> data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                    <option <?php if($webinfo->country=="NZ"){ echo "selected";}?> data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                    <option <?php if($webinfo->country=="NI"){ echo "selected";}?> data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                    <option <?php if($webinfo->country=="NE"){ echo "selected";}?> data-countryCode="NE" value="227">Niger (+227)</option>
                                    <option <?php if($webinfo->country=="NG"){ echo "selected";}?> data-countryCode="NG" value="234">Nigeria (+234)</option>
                                    <option <?php if($webinfo->country=="NU"){ echo "selected";}?> data-countryCode="NU" value="683">Niue (+683)</option>
                                    <option <?php if($webinfo->country=="NF"){ echo "selected";}?> data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                    <option <?php if($webinfo->country=="NP"){ echo "selected";}?> data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                    <option <?php if($webinfo->country=="NO"){ echo "selected";}?> data-countryCode="NO" value="47">Norway (+47)</option>
                                    <option <?php if($webinfo->country=="OM"){ echo "selected";}?> data-countryCode="OM" value="968">Oman (+968)</option>
                                    <option <?php if($webinfo->country=="PW"){ echo "selected";}?> data-countryCode="PW" value="680">Palau (+680)</option>
                                    <option <?php if($webinfo->country=="PA"){ echo "selected";}?> data-countryCode="PA" value="507">Panama (+507)</option>
                                    <option <?php if($webinfo->country=="PG"){ echo "selected";}?> data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                    <option <?php if($webinfo->country=="PY"){ echo "selected";}?> data-countryCode="PY" value="595">Paraguay (+595)</option>
                                    <option <?php if($webinfo->country=="PE"){ echo "selected";}?> data-countryCode="PE" value="51">Peru (+51)</option>
                                    <option <?php if($webinfo->country=="PH"){ echo "selected";}?> data-countryCode="PH" value="63">Philippines (+63)</option>
                                    <option <?php if($webinfo->country=="PL"){ echo "selected";}?> data-countryCode="PL" value="48">Poland (+48)</option>
                                    <option <?php if($webinfo->country=="PT"){ echo "selected";}?> data-countryCode="PT" value="351">Portugal (+351)</option>
                                    <option <?php if($webinfo->country=="PR"){ echo "selected";}?> data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                    <option <?php if($webinfo->country=="QA"){ echo "selected";}?> data-countryCode="QA" value="974">Qatar (+974)</option>
                                    <option <?php if($webinfo->country=="RE"){ echo "selected";}?> data-countryCode="RE" value="262">Reunion (+262)</option>
                                    <option <?php if($webinfo->country=="RO"){ echo "selected";}?> data-countryCode="RO" value="40">Romania (+40)</option>
                                    <option <?php if($webinfo->country=="RU"){ echo "selected";}?> data-countryCode="RU" value="7">Russia (+7)</option>
                                    <option <?php if($webinfo->country=="RW"){ echo "selected";}?> data-countryCode="RW" value="250">Rwanda (+250)</option>
                                    <option <?php if($webinfo->country=="SM"){ echo "selected";}?> data-countryCode="SM" value="378">San Marino (+378)</option>
                                    <option <?php if($webinfo->country=="ST"){ echo "selected";}?> data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                    <option <?php if($webinfo->country=="SA"){ echo "selected";}?> data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                    <option <?php if($webinfo->country=="SN"){ echo "selected";}?> data-countryCode="SN" value="221">Senegal (+221)</option>
                                    <option <?php if($webinfo->country=="CS"){ echo "selected";}?> data-countryCode="CS" value="381">Serbia (+381)</option>
                                    <option <?php if($webinfo->country=="SC"){ echo "selected";}?> data-countryCode="SC" value="248">Seychelles (+248)</option>
                                    <option <?php if($webinfo->country=="SL"){ echo "selected";}?> data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                    <option <?php if($webinfo->country=="SG"){ echo "selected";}?> data-countryCode="SG" value="65">Singapore (+65)</option>
                                    <option <?php if($webinfo->country=="SK"){ echo "selected";}?> data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                    <option <?php if($webinfo->country=="SI"){ echo "selected";}?> data-countryCode="SI" value="386">Slovenia (+386)</option>
                                    <option <?php if($webinfo->country=="SB"){ echo "selected";}?> data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                    <option <?php if($webinfo->country=="SO"){ echo "selected";}?> data-countryCode="SO" value="252">Somalia (+252)</option>
                                    <option <?php if($webinfo->country=="ZA"){ echo "selected";}?> data-countryCode="ZA" value="27">South Africa (+27)</option>
                                    <option <?php if($webinfo->country=="ES"){ echo "selected";}?> data-countryCode="ES" value="34">Spain (+34)</option>
                                    <option <?php if($webinfo->country=="LK"){ echo "selected";}?> data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                    <option <?php if($webinfo->country=="SH"){ echo "selected";}?> data-countryCode="SH" value="290">St. Helena (+290)</option>
                                    <option <?php if($webinfo->country=="KN"){ echo "selected";}?> data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                    <option <?php if($webinfo->country=="SC"){ echo "selected";}?> data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                    <option <?php if($webinfo->country=="SD"){ echo "selected";}?> data-countryCode="SD" value="249">Sudan (+249)</option>
                                    <option <?php if($webinfo->country=="SR"){ echo "selected";}?> data-countryCode="SR" value="597">Suriname (+597)</option>
                                    <option <?php if($webinfo->country=="SZ"){ echo "selected";}?> data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                    <option <?php if($webinfo->country=="SE"){ echo "selected";}?> data-countryCode="SE" value="46">Sweden (+46)</option>
                                    <option <?php if($webinfo->country=="CH"){ echo "selected";}?> data-countryCode="CH" value="41">Switzerland (+41)</option>
                                    <option <?php if($webinfo->country=="SI"){ echo "selected";}?> data-countryCode="SI" value="963">Syria (+963)</option>
                                    <option <?php if($webinfo->country=="TW"){ echo "selected";}?> data-countryCode="TW" value="886">Taiwan (+886)</option>
                                    <option <?php if($webinfo->country=="TJ"){ echo "selected";}?> data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                    <option <?php if($webinfo->country=="TH"){ echo "selected";}?> data-countryCode="TH" value="66">Thailand (+66)</option>
                                    <option <?php if($webinfo->country=="TG"){ echo "selected";}?> data-countryCode="TG" value="228">Togo (+228)</option>
                                    <option <?php if($webinfo->country=="TO"){ echo "selected";}?> data-countryCode="TO" value="676">Tonga (+676)</option>
                                    <option <?php if($webinfo->country=="TT"){ echo "selected";}?> data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                    <option <?php if($webinfo->country=="TN"){ echo "selected";}?> data-countryCode="TN" value="216">Tunisia (+216)</option>
                                    <option <?php if($webinfo->country=="TR"){ echo "selected";}?> data-countryCode="TR" value="90">Turkey (+90)</option>
                                    <option <?php if($webinfo->country=="TM"){ echo "selected";}?> data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                    <option <?php if($webinfo->country=="TM"){ echo "selected";}?> data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                    <option <?php if($webinfo->country=="TC"){ echo "selected";}?> data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                    <option <?php if($webinfo->country=="TV"){ echo "selected";}?> data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                    <option <?php if($webinfo->country=="UG"){ echo "selected";}?> data-countryCode="UG" value="256">Uganda (+256)</option>
                                    <option <?php if($webinfo->country=="UA"){ echo "selected";}?> data-countryCode="UA" value="380">Ukraine (+380)</option>
                                    <option <?php if($webinfo->country=="AE"){ echo "selected";}?> data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                    <option <?php if($webinfo->country=="UY"){ echo "selected";}?> data-countryCode="UY" value="598">Uruguay (+598)</option>
                                    <option <?php if($webinfo->country=="UZ"){ echo "selected";}?> data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                    <option <?php if($webinfo->country=="VU"){ echo "selected";}?> data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                    <option <?php if($webinfo->country=="VA"){ echo "selected";}?> data-countryCode="VA" value="379">Vatican City (+379)</option>
                                    <option <?php if($webinfo->country=="VE"){ echo "selected";}?> data-countryCode="VE" value="58">Venezuela (+58)</option>
                                    <option <?php if($webinfo->country=="VN"){ echo "selected";}?> data-countryCode="VN" value="84">Vietnam (+84)</option>
                                    <option <?php if($webinfo->country=="VG"){ echo "selected";}?> data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                    <option <?php if($webinfo->country=="VI"){ echo "selected";}?> data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                    <option <?php if($webinfo->country=="WF"){ echo "selected";}?> data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                    <option <?php if($webinfo->country=="YE"){ echo "selected";}?> data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                    <option <?php if($webinfo->country=="YE"){ echo "selected";}?> data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                    <option <?php if($webinfo->country=="ZM"){ echo "selected";}?> data-countryCode="ZM" value="260">Zambia (+260)</option>
                                    <option <?php if($webinfo->country=="ZW"){ echo "selected";}?> data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                    </optgroup>
                                    </select>
							</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="district"><?php echo display('state') ?> <abbr class="required" title="required">*</abbr></label>
                                <select name="district" id="district">
                                    <option value=""><?php echo display('select_state') ?></option>
                                    <option value="<?php echo (!empty($billinginfo->district) ? $billinginfo->district : null) ?>" data-stateid=''><?php echo (!empty($billinginfo->district) ? $billinginfo->district : null) ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="town"><?php echo display('town_city')?></label>
                                <select name="town" id="town">
                                    <option value=""><?php echo display('select_city')?></option>
                                    <option value="<?php echo (!empty($billinginfo->city) ? $billinginfo->city : null) ?>" data-city=''><?php echo (!empty($billinginfo->city) ? $billinginfo->city : null) ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo display('street_address')?></label>
                                <input type="text" id="billing_address_1" class="form-control" name="billing_address_1" value="<?php echo (!empty($billinginfo->address) ? $billinginfo->address : null) ?>">
                            </div>
                            <div class="form-group">
                                        <label class="control-label" for="postcode"><?php echo display('postcode_zip')?></label>
                                        <input type="text" id="postcode" class="form-control" name="postcode" value="<?php echo (!empty($billinginfo->zip) ? $billinginfo->zip : null) ?>">
                                    </div>
                            <div class="form-group">
                                        <label class="control-label" for="phone"><?php echo display('phone')?> <abbr class="required" title="required">*</abbr></label>
                                        <input type="number" id="phone" class="form-control" value="<?php echo (!empty($billinginfo->phone) ? $billinginfo->phone : null) ?>" placeholder="Add Country Code" name="phone" required>
                                    </div>
                        </div>
                    </div>
                    <?php if (empty($this->session->userdata('CusUserID'))) { ?>
                    <div class="checkbox checkbox-success" data-toggle="collapse" data-target="#account-pass">
                        <input id="creat_ac" type="checkbox" name="isaccount">
                        <label for="creat_ac"><?php echo display('create_account')?></label>
                    </div>
                    <div class="collapse" id="account-pass">
                        <div class="form-group">
                            <label class="control-label" for="ac_pass"><?php echo display('create_account_password')?></label>
                            <input type="text" class="form-control" id="ac_pass" name="password">
                        </div>
                    </div>
                    <?php } ?>
                    <div class="checkbox checkbox-success" data-toggle="collapse" data-target="#billind-different-address">
                        <input type="checkbox" id="shipping_address2" name="isdiffship">
                        <label for="shipping_address2"><?php echo display('shipping_different_address')?></label>
                    </div>
                    <div class="collapse" id="billind-different-address">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group has-error">
                                    <label class="control-label" for="f_name3"><?php echo display('first_name')?> <abbr class="required" title="required">*</abbr></label>
                                    <input type="text" id="f_name3" class="form-control" name="f_name3" value="<?php echo (!empty($shippinginfo->firstname) ? $shippinginfo->firstname : null) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="l_name2"><?php echo display('last_name')?> <abbr class="required" title="required">*</abbr></label>
                                    <input type="text" id="l_name2" class="form-control" name="l_name2" value="<?php echo (!empty($shippinginfo->lastname) ? $shippinginfo->lastname : null) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo display('country')?></label>
                                    <select name="country2" id="country2">
                                        <option value=""><?php echo display('select_country')?></option>
                                        <?php if (!empty($countryinfo)) {
                                        foreach ($countryinfo as $mcountry) {
                                        ?>
                                        <option value="<?php echo $mcountry->countryname; ?>" data-id="<?php echo $mcountry->countryid; ?>"><?php echo $mcountry->countryname; ?></option>
                                        <?php }
                                        }  ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="email2"><?php echo display('email')?> <abbr class="required" title="required">*</abbr></label>
                                    <input type="text" id="email2" name="email2" class="form-control" value="<?php echo (!empty($shippinginfo->email) ? $shippinginfo->email : null) ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="district2"><?php echo display('state')?> <abbr class="required" title="required">*</abbr></label>
                                    <select name="district2" id="district2">
                                        <option value=""><?php echo display('select_state')?></option>
                                        <option value="<?php echo (!empty($billinginfo->district) ? $billinginfo->district : null) ?>" data-stateid=''><?php echo (!empty($billinginfo->district) ? $billinginfo->district : null) ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="town2"><?php echo display('town_city')?></label>
                                    <select name="town2" id="town2">
                                        <option value=""><?php echo display('select_city')?></option>
                                        <option value="<?php echo (!empty($billinginfo->city) ? $billinginfo->city : null) ?>" data-stateid=''><?php echo (!empty($billinginfo->city) ? $billinginfo->city : null) ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo display('street_address')?></label>
                                    <input type="text" id="billing_address_3" class="form-control" name="billing_address_3" value="<?php echo (!empty($shippinginfo->address) ? $shippinginfo->address : null) ?>">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="postcode2"><?php echo display('postcode_zip')?></label>
                                            <input type="text" id="postcode2" class="form-control" name="postcode2" value="<?php echo (!empty($shippinginfo->zip) ? $shippinginfo->zip : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label" for="phone2"><?php echo display('phone')?> <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" id="phone2" class="form-control"  name="phone2" value="<?php echo (!empty($shippinginfo->phone) ? $shippinginfo->phone : null) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ordre_notes"><?php echo display('ordnote')?></label>
                        <textarea class="form-control" id="ordre_notes" rows="5" name="ordre_notes"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="check_order">
                    <h5 class="text-center"><?php echo display('your_order')?></h5>
                    <?php
                    if (!empty($this->cart->contents())) {
                    $totalqty = count($this->cart->contents());
                    }; ?>
                    <?php
                    $calvat = 0;
                    $discount = 0;
                    $itemtotal = 0;
                    $pvat = 0;
                    $totalamount = 0;
                    $subtotal = 0;
                    $multiplletax = array();
                    if ($cart = $this->cart->contents()) {
                    $totalamount = 0;
                    $subtotal = 0;
                    $pvat = 0;
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="product-name"><?php echo display('product')?></th>
                                <th class="product-total"><?php echo display('total')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            foreach ($cart as $item) {

                            $itemprice = $item['price'] * $item['qty'];
                            $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
                            $mypdiscountprice = 0;
                            if (!empty($taxinfos)) {
                                $tx = 0;
                                if ($iteminfo->OffersRate > 0) {
                                $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
                                }
                                $itemvalprice =  ($itemprice - $mypdiscountprice);
                                foreach ($taxinfos as $taxinfo) {
                                $fildname = 'tax' . $tx;
                                if (!empty($iteminfo->$fildname)) {

                                // $vatcalc = $itemvalprice * $iteminfo->$fildname / 100;
                                $vatcalc = Vatclaculation($itemvalprice,$iteminfo->$fildname);

                                $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                                } else {
                                // $vatcalc = $itemvalprice * $taxinfo['default_value'] / 100;
                                $vatcalc = Vatclaculation($itemvalprice,$taxinfo['default_value']);
                                $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                                }
                                $pvat = $pvat + $vatcalc;
                                $vatcalc = 0;
                                $tx++;
                            }
                            } else {
                            // $vatcalc = $itemprice * $iteminfo->productvat / 100;
                            $vatcalc = Vatclaculation($itemprice,$iteminfo->productvat);
                            $pvat = $pvat + $vatcalc;
                            }
                            if ($iteminfo->OffersRate > 0) {
                            $discal = $itemprice * $iteminfo->OffersRate / 100;
                            $discount = $discal + $discount;
                            } else {
                            $discal = 0;
                            $discount = $discount;
                            }
							if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
                            $nittotal = $item['addontpr']+$item['alltoppingprice'];
                            $itemprice = $itemprice + $item['addontpr']+$item['alltoppingprice'];
                            } else {
                            $nittotal = 0;
                            $itemprice = $itemprice;
                            }
                            $totalamount = $totalamount + $nittotal;
                            $subtotal = $subtotal - $discal + ($item['price'] * $item['qty']);
                            $i++;
                            ?>
                            <tr class="cart_item">
                                <td class="product-name">
                                    <?php echo $item['name'];
									echo "<br>";
							echo $item['size'];
                                    if (!empty($item['addonsid'])) {
                                    echo "<br>";
                                    echo $item['addonname'] . ' -Qty:' . $item['addonsqty'];
                                    if (!empty($taxinfos)) {
                                    $addonsarray = explode(',', $item['addonsid']);
                                    $addonsqtyarray = explode(',', $item['addonsqty']);
                                    $getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $addonsarray)->get()->result_array();
                                    $addn = 0;
                                    foreach ($getaddonsdatas as $getaddonsdata) {
                                    $tax = 0;
                                    foreach ($taxinfos as $taxainfo) {
                                    $fildaname = 'tax' . $tax;
                                    if (!empty($getaddonsdata[$fildaname])) {
                                    // $avatcalc = ($getaddonsdata['price'] * $addonsqtyarray[$addn]) * $getaddonsdata[$fildaname] / 100;

                                    $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn],$getaddonsdata[$fildaname]);
                                    $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                    } else {
                                    // $avatcalc = ($getaddonsdata['price'] * $addonsqtyarray[$addn]) * $taxainfo['default_value'] / 100;
                                    $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn],$taxainfo['default_value']);
                                    $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                    }
                                    $pvat = $pvat + $avatcalc;
                                    $tax++;
                                    }
                                    $addn++;
                                    }
                                    }
                                    }
									if(!empty($item['toppingid'])){
									     $toppingarray = explode(',',$item['toppingid']);
										 $toppingnamearray = explode(',',$item['toppingname']);
                                         $toppingpryarray = explode(',',$item['toppingprice']);
										 $t=0;
										 foreach($toppingarray as $tpname){
											if($toppingpryarray[$t]>0){
												echo "<br>";
												echo $toppingnamearray[$t]; 
											}
											$t++;	 
										 }

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

                                            $tvatcalc = Vatclaculation($toppingpryarray[$tpn],$gettoppingdata[$fildaname]);


											$multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;
                                        }
                                        else{
                                        //   $tvatcalc=$toppingpryarray[$tpn]*$taxainfo['default_value']/100; 

                                          $tvatcalc = Vatclaculation($toppingpryarray[$tpn],$taxainfo['default_value']);

                                          $multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;  
                                        }

                                      $pvat=$pvat+$tvatcalc;

                                            $tptax++;
                                          }
                                          $tpn++;
                                        }
                                        }
                                 }	
                                    ?>
                                    <strong class="product-sum">× <?php echo $item['qty']; ?></strong>
                                </td>
                                <td class="product-total">
                                    <span class="woocommerce-Price-amount amount">
                                        <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                            echo $this->storecurrency->curr_icon;
                                        } ?></span><?php echo $itemprice - $discal; ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span></span>
                                </td>
                            </tr>
                            <?php }
                            $itemtotal = $totalamount + $subtotal;
                            /*check $taxsetting info*/
                            if (empty($taxinfos)) {
                            if ($this->settinginfo->vat > 0) {
                            // $calvat = $itemtotal * $this->settinginfo->vat / 100;
                            $calvat = Vatclaculation($itemtotal,$this->settinginfo->vat);
                            } else {
                            $calvat = $pvat;
                            }
                            } else {
                            $calvat = $pvat;
                            }
                            
                            $multiplletaxvalue = htmlentities(serialize($multiplletax));
                            ?>
                        </tbody>
                        <tfoot>
                        <tr class="cart-subtotal">
                            <th><?php echo display('subtotal')?></th>
                            <td>
                                <strong>
                                <span class="woocommerce-Price-amount amount">
                                    <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span><?php echo $itemtotal; ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                    echo $this->storecurrency->curr_icon;
                                } ?></span></span>
                                </strong>
                                <input name="orggrandTotal" type="hidden" value="<?php echo $itemtotal+$discount; ?>" />
                            </td>
                        </tr>
                        <?php if (empty($taxinfos)) { ?>
                        <tr class="order-total">
                            <th><?php echo display('total_vat')?></th>
                            <td>
                                <strong>
                                <span class="woocommerce-Price-amount amount">
                                    <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span><?php echo numbershow($calvat, $settinginfo->showdecimal); ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                    echo $this->storecurrency->curr_icon;
                                } ?></span></span>
                                </strong>
                                <input name="vat" type="hidden" value="<?php echo $calvat; ?>" />
                            </td>
                        </tr>
                        <?php } else {
                        $i = 0;
                        foreach ($taxinfos as $mvat) {
                        if ($mvat['is_show'] == 1) {
                        ?>
                        <tr class="order-total">
                            <th><?php echo $mvat['tax_name']; ?></th>
                            <td>
                                <strong>
                                <span class="woocommerce-Price-amount amount">
                                    <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span><?php echo numbershow($multiplletax['tax' . $i], $settinginfo->showdecimal);?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                    echo $this->storecurrency->curr_icon;
                                } ?></span></span>
                                </strong>
                            </td>
                        </tr>
                        <?php $i++;
                        }
                        } ?>
                        <input name="vat" type="hidden" value="<?php echo $calvat; ?>" />
                        <?php } ?>
                        <tr class="order-total">
                            <th><?php echo display('discount')?></th>
                            <td>
                                <strong>
                                <span class="woocommerce-Price-amount amount">
                                    <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span><?php echo $discount;
                                    ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span></span>
                                    </strong>
                                </td>
                            </tr>
                            <?php $coupon = 0;
                            if (!empty($this->session->userdata('couponcode'))) { ?>
                            <tr class="order-total">
                                <th><?php echo display('coupon_discount')?></th>
                                <td>
                                    <strong>
                                    <span class="woocommerce-Price-amount amount">
                                        <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                            echo $this->storecurrency->curr_icon;
                                        } ?></span><?php echo $coupon = $this->session->userdata('couponprice'); ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span></span>
                                    </strong>
                                </td>
                            </tr>
                            <?php } ?>
                            <input name="invoice_discount" type="hidden" value="<?php echo $discount + $coupon; ?>" />
                            <tr class="order-total">
                                <th><?php echo display('service')?></th>
                                <td>
                                    <strong>
                                    <span class="woocommerce-Price-amount amount">
                                        <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                            echo $this->storecurrency->curr_icon;
                                        } ?></span><?php echo $this->session->userdata('shippingrate'); ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span></span>
                                    </strong>
                                    <input name="service_charge" type="hidden" value="<?php echo $this->session->userdata('shippingrate'); ?>" />
                                </td>
                            </tr>
                            <tr class="order-total">
                                <th><?php echo display('total')?></th>
                                <td>
                                    <strong>
                                    <span class="woocommerce-Price-amount amount">
                                        <span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 1) {
                                            echo $this->storecurrency->curr_icon;
                                        } ?></span><?php 
					$isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
					if(!empty($isvatinclusive)){
						echo ($itemtotal + $this->session->userdata('shippingrate')) - ($coupon);
					}else{
						echo ($calvat + $itemtotal + $this->session->userdata('shippingrate')) - ($coupon);
					}
										
										
										 ?><span class="woocommerce-Price-currencySymbol"><?php if ($this->storecurrency->position == 2) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?></span></span>
                                    </strong><input name="grandtotal" type="hidden" value="<?php echo ($calvat + $itemtotal + $this->session->userdata('shippingrate')) - ($coupon); ?>" />
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                        <?php } ?>
                        <!-- /.End of product list table -->
                        <div class="payment-block" id="payment">
                            <?php 
							if (!empty($paymentinfo)) {
                                foreach ($paymentinfo as $payment) {
									if(!array_filter($slpayment)){?>
                                    <div class="payment-item">
                                            <input type="radio" name="card_type" id="payment_method_cre" data-parent="#payment" data-target="#description_cre" value="<?php echo $payment->payment_method_id; ?>" class="" <?php if ($payment->payment_method_id == 4) {
                                            echo "checked";
                                            } ?>>
                                            <label for="payment_method_cre"><?php echo $payment->payment_method; ?></label>
                                        </div>									
                           <?php } 
									else{
										foreach($slpayment as $selmethod){
											if($selmethod==$payment->payment_method_id){
										?>
										<div class="payment-item">
                                            <input type="radio" name="card_type" id="payment_method_cre<?php echo $payment->payment_method_id; ?>" data-parent="#payment" data-target="#description_cre" value="<?php echo $payment->payment_method_id; ?>" class="" <?php if ($payment->payment_method_id == $pvalue) { echo "checked";} ?>>
                                            <label for="payment_method_cre<?php echo $payment->payment_method_id; ?>"><?php echo $payment->payment_method; ?></label>
                                        </div>
										<?php }
						   }
                            } 
								}
							}
							?>
                        </div>
                        <!-- /.End of payment method -->
                        <!-- <a href="#" class="btn btn-success btn-block">Place order</a>-->
                        <input name="multiplletaxvalue" id="multiplletaxvalue" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />
                        <input class="btn btn-success btn-block" name="" type="submit" value="<?php echo display('placeorder')?>" />
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--======== End Checkout area ==========-->
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/checkout.js"></script>
     