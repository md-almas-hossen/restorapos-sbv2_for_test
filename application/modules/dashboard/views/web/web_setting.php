<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
            		
                <?php 			
				echo form_open_multipart('dashboard/web_setting/common_create','class="form-inner"') ?>
                    <?php echo form_hidden('id',$websetting->id) ?>
                    <div class="form-group row">
                        <label for="email" class="col-xs-3 col-form-label"><?php echo display('email')?></label>
                        <div class="col-xs-9">
                            <input name="email" type="text" class="form-control" id="email" placeholder="<?php echo display('email')?>"  value="<?php echo $websetting->email ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-xs-3 col-form-label"><?php echo display('mobile') ?></label>
                        <div class="col-xs-9">
                            <input name="phone" type="text" class="form-control" id="phone" placeholder="<?php echo display('mobile') ?>"  value="<?php echo $websetting->phone ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone2" class="col-xs-3 col-form-label"><?php echo display('phone') ?></label>
                        <div class="col-xs-9">
                            <input name="phone2" type="text" class="form-control" id="phone2" placeholder="<?php echo display('phone') ?>"  value="<?php echo $websetting->phone_optional ?>" >
                        </div>
                    </div>
					<div class="form-group row">
                        <label for="country" class="col-xs-3 col-form-label"><?php echo display('country') ?></label>
                        <div class="col-sm-9 customesl">
                            <select name="country" class="form-control">
														<option value=""><?php echo display('select_option') ?></option>
                                                        <option data-countryCode="GB" value="GB" <?php if($websetting->country==GB){ echo "selected";}?>>UK (+44)</option>
                                                        <option data-countryCode="US" value="US" <?php if($websetting->country==US){ echo "selected";}?>>USA (+1)</option>
                                                        <optgroup label="Other countries">
                                                            <option data-countryCode="DZ" <?php if($websetting->country=="DZ"){ echo "selected";}?> value="DZ">Algeria (+213)</option>
                                                            <option data-countryCode="AD" <?php if($websetting->country=="AD"){ echo "selected";}?> value="AD">Andorra (+376)</option>
                                                            <option data-countryCode="AO" <?php if($websetting->country=="AO"){ echo "selected";}?> value="AO">Angola (+244)</option>
                                                            <option data-countryCode="AI" <?php if($websetting->country=="AI"){ echo "selected";}?> value="AI">Anguilla (+1264)</option>
                                                            <option data-countryCode="AG" <?php if($websetting->country=="AG"){ echo "selected";}?> value="AG">Antigua &amp; Barbuda (+1268)</option>
                                                            <option data-countryCode="AR" <?php if($websetting->country=="AR"){ echo "selected";}?> value="AR">Argentina (+54)</option>
                                                            <option data-countryCode="AM" <?php if($websetting->country=="AM"){ echo "selected";}?> value="AM">Armenia (+374)</option>
                                                            <option data-countryCode="AW" <?php if($websetting->country=="AW"){ echo "selected";}?> value="AW">Aruba (+297)</option>
                                                            <option data-countryCode="AU" <?php if($websetting->country=="AU"){ echo "selected";}?> value="AU">Australia (+61)</option>
                                                            <option data-countryCode="AT" <?php if($websetting->country=="AT"){ echo "selected";}?> value="AT">Austria (+43)</option>
                                                            <option data-countryCode="AZ" <?php if($websetting->country=="AZ"){ echo "selected";}?> value="AZ">Azerbaijan (+994)</option>
                                                            <option data-countryCode="BS" <?php if($websetting->country=="BS"){ echo "selected";}?> value="BS">Bahamas (+1242)</option>
                                                            <option data-countryCode="BH" <?php if($websetting->country=="BH"){ echo "selected";}?> value="BH">Bahrain (+973)</option>
                                                            <option data-countryCode="BD" <?php if($websetting->country=="BD"){ echo "selected";}?> value="BD">Bangladesh (+880)</option>
                                                            <option data-countryCode="BB" <?php if($websetting->country=="BB"){ echo "selected";}?> value="BB">Barbados (+1246)</option>
                                                            <option data-countryCode="BY" <?php if($websetting->country=="BY"){ echo "selected";}?> value="BY">Belarus (+375)</option>
                                                            <option data-countryCode="BE" <?php if($websetting->country=="BE"){ echo "selected";}?> value="BE">Belgium (+32)</option>
                                                            <option data-countryCode="BZ" <?php if($websetting->country=="BZ"){ echo "selected";}?> value="BZ">Belize (+501)</option>
                                                            <option data-countryCode="BJ" <?php if($websetting->country=="BJ"){ echo "selected";}?> value="BJ">Benin (+229)</option>
                                                            <option data-countryCode="BM" <?php if($websetting->country=="BM"){ echo "selected";}?> value="BM">Bermuda (+1441)</option>
                                                            <option data-countryCode="BT" <?php if($websetting->country=="BT"){ echo "selected";}?> value="BT">Bhutan (+975)</option>
                                                            <option data-countryCode="BO" <?php if($websetting->country=="BO"){ echo "selected";}?> value="BO">Bolivia (+591)</option>
                                                            <option data-countryCode="BA" <?php if($websetting->country=="BA"){ echo "selected";}?> value="BA">Bosnia Herzegovina (+387)</option>
                                                            <option data-countryCode="BW" <?php if($websetting->country=="BW"){ echo "selected";}?> value="BW">Botswana (+267)</option>
                                                            <option data-countryCode="BR" <?php if($websetting->country=="BR"){ echo "selected";}?> value="BR">Brazil (+55)</option>
                                                            <option data-countryCode="BN" <?php if($websetting->country=="BN"){ echo "selected";}?> value="BN">Brunei (+673)</option>
                                                            <option data-countryCode="BG" <?php if($websetting->country=="BG"){ echo "selected";}?> value="BG">Bulgaria (+359)</option>
                                                            <option data-countryCode="BF" <?php if($websetting->country=="BF"){ echo "selected";}?> value="BF">Burkina Faso (+226)</option>
                                                            <option data-countryCode="BI" <?php if($websetting->country=="BI"){ echo "selected";}?> value="BI">Burundi (+257)</option>
                                                            <option data-countryCode="KH" <?php if($websetting->country=="KH"){ echo "selected";}?> value="KH">Cambodia (+855)</option>
                                                            <option data-countryCode="CM" <?php if($websetting->country=="CM"){ echo "selected";}?> value="CM">Cameroon (+237)</option>
                                                            <option data-countryCode="CA" <?php if($websetting->country=="CA"){ echo "selected";}?> value="CA">Canada (+1)</option>
                                                            <option data-countryCode="CV" <?php if($websetting->country=="CV"){ echo "selected";}?> value="CV">Cape Verde Islands (+238)</option>
                                                            <option data-countryCode="KY" <?php if($websetting->country=="KY"){ echo "selected";}?> value="KY">Cayman Islands (+1345)</option>
                                                            <option data-countryCode="CF" <?php if($websetting->country=="CF"){ echo "selected";}?> value="CF">Central African Republic (+236)</option>
                                                            <option data-countryCode="CL" <?php if($websetting->country=="CL"){ echo "selected";}?> value="CL">Chile (+56)</option>
                                                            <option data-countryCode="CN" <?php if($websetting->country=="CN"){ echo "selected";}?> value="CN">China (+86)</option>
                                                            <option data-countryCode="CO" <?php if($websetting->country=="CO"){ echo "selected";}?> value="CO">Colombia (+57)</option>
                                                            <option data-countryCode="KM" <?php if($websetting->country=="KM"){ echo "selected";}?> value="KM">Comoros (+269)</option>
                                                            <option data-countryCode="CG" <?php if($websetting->country=="CG"){ echo "selected";}?> value="CG">Congo (+242)</option>
                                                            <option data-countryCode="CK" <?php if($websetting->country=="CK"){ echo "selected";}?> value="CK">Cook Islands (+682)</option>
                                                            <option data-countryCode="CR" <?php if($websetting->country=="CR"){ echo "selected";}?> value="CR">Costa Rica (+506)</option>
                                                            <option data-countryCode="HR" <?php if($websetting->country=="HR"){ echo "selected";}?> value="HR">Croatia (+385)</option>
                                                            <option data-countryCode="CU" <?php if($websetting->country=="CU"){ echo "selected";}?> value="CU">Cuba (+53)</option>
                                                            <option data-countryCode="CY" <?php if($websetting->country=="CY"){ echo "selected";}?> value="CY">Cyprus North (+90392)</option>
                                                            <option data-countryCode="CY" <?php if($websetting->country=="CY"){ echo "selected";}?> value="CY">Cyprus South (+357)</option>
                                                            <option data-countryCode="CZ" <?php if($websetting->country=="CZ"){ echo "selected";}?> value="CZ">Czech Republic (+42)</option>
                                                            <option data-countryCode="DK" <?php if($websetting->country=="DK"){ echo "selected";}?> value="DK">Denmark (+45)</option>
                                                            <option data-countryCode="DJ" <?php if($websetting->country=="DJ"){ echo "selected";}?> value="DJ">Djibouti (+253)</option>
                                                            <option data-countryCode="DM" <?php if($websetting->country=="DM"){ echo "selected";}?> value="DM">Dominica (+1809)</option>
                                                            <option data-countryCode="DO" <?php if($websetting->country=="DO"){ echo "selected";}?> value="DO">Dominican Republic (+1809)</option>
                                                            <option data-countryCode="EC" <?php if($websetting->country=="EC"){ echo "selected";}?> value="EC">Ecuador (+593)</option>
                                                            <option data-countryCode="EG" <?php if($websetting->country=="EG"){ echo "selected";}?> value="EG">Egypt (+20)</option>
                                                            <option data-countryCode="SV" <?php if($websetting->country=="SV"){ echo "selected";}?> value="SV">El Salvador (+503)</option>
                                                            <option data-countryCode="GQ" <?php if($websetting->country=="GQ"){ echo "selected";}?> value="GQ">Equatorial Guinea (+240)</option>
                                                            <option data-countryCode="ER" <?php if($websetting->country=="ER"){ echo "selected";}?> value="ER">Eritrea (+291)</option>
                                                            <option data-countryCode="EE" <?php if($websetting->country=="EE"){ echo "selected";}?> value="EE">Estonia (+372)</option>
                                                            <option data-countryCode="ET" <?php if($websetting->country=="ET"){ echo "selected";}?> value="ET">Ethiopia (+251)</option>
                                                            <option data-countryCode="FK" <?php if($websetting->country=="FK"){ echo "selected";}?> value="FK">Falkland Islands (+500)</option>
                                                            <option data-countryCode="FO" <?php if($websetting->country=="FO"){ echo "selected";}?> value="FO">Faroe Islands (+298)</option>
                                                            <option data-countryCode="FJ" <?php if($websetting->country=="FJ"){ echo "selected";}?> value="FJ">Fiji (+679)</option>
                                                            <option data-countryCode="FI" <?php if($websetting->country=="FI"){ echo "selected";}?> value="FI">Finland (+358)</option>
                                                            <option data-countryCode="FR" <?php if($websetting->country=="FR"){ echo "selected";}?> value="FR">France (+33)</option>
                                                            <option data-countryCode="GF" <?php if($websetting->country=="GF"){ echo "selected";}?> value="GF">French Guiana (+594)</option>
                                                            <option data-countryCode="PF" <?php if($websetting->country=="PF"){ echo "selected";}?> value="PF">French Polynesia (+689)</option>
                                                            <option data-countryCode="GA" <?php if($websetting->country=="GA"){ echo "selected";}?> value="GA">Gabon (+241)</option>
                                                            <option data-countryCode="GM" <?php if($websetting->country=="GM"){ echo "selected";}?> value="GM">Gambia (+220)</option>
                                                            <option data-countryCode="GE" <?php if($websetting->country=="GE"){ echo "selected";}?> value="GE">Georgia (+7880)</option>
                                                            <option data-countryCode="DE" <?php if($websetting->country=="DE"){ echo "selected";}?> value="DE">Germany (+49)</option>
                                                            <option data-countryCode="GH" <?php if($websetting->country=="GH"){ echo "selected";}?> value="GH">Ghana (+233)</option>
                                                            <option data-countryCode="GI" <?php if($websetting->country=="GI"){ echo "selected";}?> value="GI">Gibraltar (+350)</option>
                                                            <option data-countryCode="GR" <?php if($websetting->country=="GR"){ echo "selected";}?> value="GR">Greece (+30)</option>
                                                            <option data-countryCode="GL" <?php if($websetting->country=="GL"){ echo "selected";}?> value="GL">Greenland (+299)</option>
                                                            <option data-countryCode="GD" <?php if($websetting->country=="GD"){ echo "selected";}?> value="GD">Grenada (+1473)</option>
                                                            <option data-countryCode="GP" <?php if($websetting->country=="GP"){ echo "selected";}?> value="GP">Guadeloupe (+590)</option>
                                                            <option data-countryCode="GU" <?php if($websetting->country=="GU"){ echo "selected";}?> value="GU">Guam (+671)</option>
                                                            <option data-countryCode="GT" <?php if($websetting->country=="GT"){ echo "selected";}?> value="GT">Guatemala (+502)</option>
                                                            <option data-countryCode="GN" <?php if($websetting->country=="GN"){ echo "selected";}?> value="GN">Guinea (+224)</option>
                                                            <option data-countryCode="GW" <?php if($websetting->country=="GW"){ echo "selected";}?> value="GW">Guinea - Bissau (+245)</option>
                                                            <option data-countryCode="GY" <?php if($websetting->country=="GY"){ echo "selected";}?> value="GY">Guyana (+592)</option>
                                                            <option data-countryCode="HT" <?php if($websetting->country=="HT"){ echo "selected";}?> value="HT">Haiti (+509)</option>
                                                            <option data-countryCode="HN" <?php if($websetting->country=="HN"){ echo "selected";}?> value="HN">Honduras (+504)</option>
                                                            <option data-countryCode="HK" <?php if($websetting->country=="HK"){ echo "selected";}?> value="HK">Hong Kong (+852)</option>
                                                            <option data-countryCode="HU" <?php if($websetting->country=="HU"){ echo "selected";}?> value="HU">Hungary (+36)</option>
                                                            <option data-countryCode="IS" <?php if($websetting->country=="IS"){ echo "selected";}?> value="IS">Iceland (+354)</option>
                                                            <option data-countryCode="IN" <?php if($websetting->country=="IN"){ echo "selected";}?> value="IN">India (+91)</option>
                                                            <option data-countryCode="ID" <?php if($websetting->country=="ID"){ echo "selected";}?> value="ID">Indonesia (+62)</option>
                                                            <option data-countryCode="IR" <?php if($websetting->country=="IR"){ echo "selected";}?> value="IR">Iran (+98)</option>
                                                            <option data-countryCode="IQ" <?php if($websetting->country=="IQ"){ echo "selected";}?> value="IQ">Iraq (+964)</option>
                                                            <option data-countryCode="IE" <?php if($websetting->country=="IE"){ echo "selected";}?> value="IE">Ireland (+353)</option>
                                                            <option data-countryCode="IL" <?php if($websetting->country=="IL"){ echo "selected";}?> value="IL">Israel (+972)</option>
                                                            <option data-countryCode="IT" <?php if($websetting->country=="IT"){ echo "selected";}?> value="IT">Italy (+39)</option>
                                                            <option data-countryCode="JM" <?php if($websetting->country=="JM"){ echo "selected";}?> value="JM">Jamaica (+1876)</option>
                                                            <option data-countryCode="JP" <?php if($websetting->country=="JP"){ echo "selected";}?> value="JP">Japan (+81)</option>
                                                            <option data-countryCode="JO" <?php if($websetting->country=="JO"){ echo "selected";}?> value="JO">Jordan (+962)</option>
                                                            <option data-countryCode="KZ" <?php if($websetting->country=="KZ"){ echo "selected";}?> value="KZ">Kazakhstan (+7)</option>
                                                            <option data-countryCode="KE" <?php if($websetting->country=="KE"){ echo "selected";}?> value="KE">Kenya (+254)</option>
                                                            <option data-countryCode="KI" <?php if($websetting->country=="KI"){ echo "selected";}?> value="KI">Kiribati (+686)</option>
                                                            <option data-countryCode="KP" <?php if($websetting->country=="KP"){ echo "selected";}?> value="KP">Korea North (+850)</option>
                                                            <option data-countryCode="KR" <?php if($websetting->country=="KR"){ echo "selected";}?> value="KR">Korea South (+82)</option>
                                                            <option data-countryCode="KW" <?php if($websetting->country=="KW"){ echo "selected";}?> value="KW">Kuwait (+965)</option>
                                                            <option data-countryCode="KG" <?php if($websetting->country=="KG"){ echo "selected";}?> value="KG">Kyrgyzstan (+996)</option>
                                                            <option data-countryCode="LA" <?php if($websetting->country=="LA"){ echo "selected";}?> value="LA">Laos (+856)</option>
                                                            <option data-countryCode="LV" <?php if($websetting->country=="LV"){ echo "selected";}?> value="LV">Latvia (+371)</option>
                                                            <option data-countryCode="LB" <?php if($websetting->country=="LB"){ echo "selected";}?> value="LB">Lebanon (+961)</option>
                                                            <option data-countryCode="LS" <?php if($websetting->country=="LS"){ echo "selected";}?> value="LS">Lesotho (+266)</option>
                                                            <option data-countryCode="LR" <?php if($websetting->country=="LR"){ echo "selected";}?> value="LR">Liberia (+231)</option>
                                                            <option data-countryCode="LY" <?php if($websetting->country=="LY"){ echo "selected";}?> value="LY">Libya (+218)</option>
                                                            <option data-countryCode="LI" <?php if($websetting->country=="LI"){ echo "selected";}?> value="LI">Liechtenstein (+417)</option>
                                                            <option data-countryCode="LT" <?php if($websetting->country=="LT"){ echo "selected";}?> value="LT">Lithuania (+370)</option>
                                                            <option data-countryCode="LU" <?php if($websetting->country=="LU"){ echo "selected";}?> value="LU">Luxembourg (+352)</option>
                                                            <option data-countryCode="MO" <?php if($websetting->country=="MO"){ echo "selected";}?> value="MO">Macao (+853)</option>
                                                            <option data-countryCode="MK" <?php if($websetting->country=="MK"){ echo "selected";}?> value="MK">Macedonia (+389)</option>
                                                            <option data-countryCode="MG" <?php if($websetting->country=="MG"){ echo "selected";}?> value="MG">Madagascar (+261)</option>
                                                            <option data-countryCode="MW" <?php if($websetting->country=="MW"){ echo "selected";}?> value="MW">Malawi (+265)</option>
                                                            <option data-countryCode="MY" <?php if($websetting->country=="MY"){ echo "selected";}?> value="MY">Malaysia (+60)</option>
                                                            <option data-countryCode="MV" <?php if($websetting->country=="MV"){ echo "selected";}?> value="MV">Maldives (+960)</option>
                                                            <option data-countryCode="ML" <?php if($websetting->country=="ML"){ echo "selected";}?> value="ML">Mali (+223)</option>
                                                            <option data-countryCode="MT" <?php if($websetting->country=="MT"){ echo "selected";}?> value="MT">Malta (+356)</option>
                                                            <option data-countryCode="MH" <?php if($websetting->country=="MH"){ echo "selected";}?> value="MH">Marshall Islands (+692)</option>
                                                            <option data-countryCode="MQ" <?php if($websetting->country=="MQ"){ echo "selected";}?> value="MQ">Martinique (+596)</option>
                                                            <option data-countryCode="MR" <?php if($websetting->country=="MR"){ echo "selected";}?> value="MR">Mauritania (+222)</option>
                                                            <option data-countryCode="YT" <?php if($websetting->country=="YT"){ echo "selected";}?> value="YT">Mayotte (+269)</option>
                                                            <option data-countryCode="MX" <?php if($websetting->country=="MX"){ echo "selected";}?> value="MX">Mexico (+52)</option>
                                                            <option data-countryCode="FM" <?php if($websetting->country=="FM"){ echo "selected";}?> value="FM">Micronesia (+691)</option>
                                                            <option data-countryCode="MD" <?php if($websetting->country=="MD"){ echo "selected";}?> value="MD">Moldova (+373)</option>
                                                            <option data-countryCode="MC" <?php if($websetting->country=="MC"){ echo "selected";}?> value="MC">Monaco (+377)</option>
                                                            <option data-countryCode="MN" <?php if($websetting->country=="MN"){ echo "selected";}?> value="MN">Mongolia (+976)</option>
                                                            <option data-countryCode="MS" <?php if($websetting->country=="MS"){ echo "selected";}?> value="MS">Montserrat (+1664)</option>
                                                            <option data-countryCode="MA" <?php if($websetting->country=="MA"){ echo "selected";}?> value="MA">Morocco (+212)</option>
                                                            <option data-countryCode="MZ" <?php if($websetting->country=="MZ"){ echo "selected";}?> value="MZ">Mozambique (+258)</option>
                                                            <option data-countryCode="MN" <?php if($websetting->country=="MN"){ echo "selected";}?> value="MN">Myanmar (+95)</option>
                                                            <option data-countryCode="NA" <?php if($websetting->country=="NA"){ echo "selected";}?> value="NA">Namibia (+264)</option>
                                                            <option data-countryCode="NR" <?php if($websetting->country=="NR"){ echo "selected";}?> value="NR">Nauru (+674)</option>
                                                            <option data-countryCode="NP" <?php if($websetting->country=="NP"){ echo "selected";}?> value="NP">Nepal (+977)</option>
                                                            <option data-countryCode="NL" <?php if($websetting->country=="NL"){ echo "selected";}?> value="NL">Netherlands (+31)</option>
                                                            <option data-countryCode="NC" <?php if($websetting->country=="NC"){ echo "selected";}?> value="NC">New Caledonia (+687)</option>
                                                            <option data-countryCode="NZ" <?php if($websetting->country=="NZ"){ echo "selected";}?> value="NZ">New Zealand (+64)</option>
                                                            <option data-countryCode="NI" <?php if($websetting->country=="NI"){ echo "selected";}?> value="NI">Nicaragua (+505)</option>
                                                            <option data-countryCode="NE" <?php if($websetting->country=="NE"){ echo "selected";}?> value="NE">Niger (+227)</option>
                                                            <option data-countryCode="NG" <?php if($websetting->country=="NG"){ echo "selected";}?> value="NG">Nigeria (+234)</option>
                                                            <option data-countryCode="NU" <?php if($websetting->country=="NU"){ echo "selected";}?> value="NU">Niue (+683)</option>
                                                            <option data-countryCode="NF" <?php if($websetting->country=="NF"){ echo "selected";}?> value="NF">Norfolk Islands (+672)</option>
                                                            <option data-countryCode="NP" <?php if($websetting->country=="NP"){ echo "selected";}?> value="NP">Northern Marianas (+670)</option>
                                                            <option data-countryCode="NO" <?php if($websetting->country=="NO"){ echo "selected";}?> value="NO">Norway (+47)</option>
                                                            <option data-countryCode="OM" <?php if($websetting->country=="OM"){ echo "selected";}?> value="OM">Oman (+968)</option>
                                                            <option data-countryCode="PW" <?php if($websetting->country=="PW"){ echo "selected";}?> value="PW">Palau (+680)</option>
                                                            <option data-countryCode="PA" <?php if($websetting->country=="PA"){ echo "selected";}?> value="PA">Panama (+507)</option>
                                                            <option data-countryCode="PG" <?php if($websetting->country=="PG"){ echo "selected";}?> value="PG">Papua New Guinea (+675)</option>
                                                            <option data-countryCode="PY" <?php if($websetting->country=="PY"){ echo "selected";}?> value="PY">Paraguay (+595)</option>
                                                            <option data-countryCode="PE" <?php if($websetting->country=="PE"){ echo "selected";}?> value="PE">Peru (+51)</option>
                                                            <option data-countryCode="PH" <?php if($websetting->country=="PH"){ echo "selected";}?> value="PH">Philippines (+63)</option>
                                                            <option data-countryCode="PL" <?php if($websetting->country=="PL"){ echo "selected";}?> value="PL">Poland (+48)</option>
                                                            <option data-countryCode="PT" <?php if($websetting->country=="PT"){ echo "selected";}?> value="PT">Portugal (+351)</option>
                                                            <option data-countryCode="PR" <?php if($websetting->country=="PR"){ echo "selected";}?> value="PR">Puerto Rico (+1787)</option>
                                                            <option data-countryCode="QA" <?php if($websetting->country=="QA"){ echo "selected";}?> value="QA">Qatar (+974)</option>
                                                            <option data-countryCode="RE" <?php if($websetting->country=="RE"){ echo "selected";}?> value="RE">Reunion (+262)</option>
                                                            <option data-countryCode="RO" <?php if($websetting->country=="RO"){ echo "selected";}?> value="RO">Romania (+40)</option>
                                                            <option data-countryCode="RU" <?php if($websetting->country=="RU"){ echo "selected";}?> value="RU">Russia (+7)</option>
                                                            <option data-countryCode="RW" <?php if($websetting->country=="RW"){ echo "selected";}?> value="RW">Rwanda (+250)</option>
                                                            <option data-countryCode="SM" <?php if($websetting->country=="SM"){ echo "selected";}?> value="SM">San Marino (+378)</option>
                                                            <option data-countryCode="ST" <?php if($websetting->country=="ST"){ echo "selected";}?> value="ST">Sao Tome &amp; Principe (+239)</option>
                                                            <option data-countryCode="SA" <?php if($websetting->country=="SA"){ echo "selected";}?> value="SA">Saudi Arabia (+966)</option>
                                                            <option data-countryCode="SN" <?php if($websetting->country=="SN"){ echo "selected";}?> value="SN">Senegal (+221)</option>
                                                            <option data-countryCode="CS" <?php if($websetting->country=="CS"){ echo "selected";}?> value="CS">Serbia (+381)</option>
                                                            <option data-countryCode="SC" <?php if($websetting->country=="SC"){ echo "selected";}?> value="SC">Seychelles (+248)</option>
                                                            <option data-countryCode="SL" <?php if($websetting->country=="SL"){ echo "selected";}?> value="SL">Sierra Leone (+232)</option>
                                                            <option data-countryCode="SG" <?php if($websetting->country=="SG"){ echo "selected";}?> value="SG">Singapore (+65)</option>
                                                            <option data-countryCode="SK" <?php if($websetting->country=="SK"){ echo "selected";}?> value="SK">Slovak Republic (+421)</option>
                                                            <option data-countryCode="SI" <?php if($websetting->country=="SI"){ echo "selected";}?> value="SI">Slovenia (+386)</option>
                                                            <option data-countryCode="SB" <?php if($websetting->country=="SB"){ echo "selected";}?> value="SB">Solomon Islands (+677)</option>
                                                            <option data-countryCode="SO" <?php if($websetting->country=="SO"){ echo "selected";}?> value="SO">Somalia (+252)</option>
                                                            <option data-countryCode="ZA" <?php if($websetting->country=="ZA"){ echo "selected";}?> value="ZA">South Africa (+27)</option>
                                                            <option data-countryCode="ES" <?php if($websetting->country=="ES"){ echo "selected";}?> value="ES">Spain (+34)</option>
                                                            <option data-countryCode="LK" <?php if($websetting->country=="LK"){ echo "selected";}?> value="LK">Sri Lanka (+94)</option>
                                                            <option data-countryCode="SH" <?php if($websetting->country=="SH"){ echo "selected";}?> value="SH">St. Helena (+290)</option>
                                                            <option data-countryCode="KN" <?php if($websetting->country=="KN"){ echo "selected";}?> value="KN">St. Kitts (+1869)</option>
                                                            <option data-countryCode="SC" <?php if($websetting->country=="SC"){ echo "selected";}?> value="SC">St. Lucia (+1758)</option>
                                                            <option data-countryCode="SD" <?php if($websetting->country=="SD"){ echo "selected";}?> value="SD">Sudan (+249)</option>
                                                            <option data-countryCode="SR" <?php if($websetting->country=="SR"){ echo "selected";}?> value="SR">Suriname (+597)</option>
                                                            <option data-countryCode="SZ" <?php if($websetting->country=="SZ"){ echo "selected";}?> value="SZ">Swaziland (+268)</option>
                                                            <option data-countryCode="SE" <?php if($websetting->country=="SE"){ echo "selected";}?> value="SE">Sweden (+46)</option>
                                                            <option data-countryCode="CH" <?php if($websetting->country=="CH"){ echo "selected";}?> value="CH">Switzerland (+41)</option>
                                                            <option data-countryCode="SI" <?php if($websetting->country=="SI"){ echo "selected";}?> value="SI">Syria (+963)</option>
                                                            <option data-countryCode="TW" <?php if($websetting->country=="TW"){ echo "selected";}?> value="TW">Taiwan (+886)</option>
                                                            <option data-countryCode="TJ" <?php if($websetting->country=="TJ"){ echo "selected";}?> value="TJ">Tajikstan (+7)</option>
                                                            <option data-countryCode="TH" <?php if($websetting->country=="TH"){ echo "selected";}?> value="TH">Thailand (+66)</option>
                                                            <option data-countryCode="TG" <?php if($websetting->country=="TG"){ echo "selected";}?> value="TG">Togo (+228)</option>
                                                            <option data-countryCode="TO" <?php if($websetting->country=="TO"){ echo "selected";}?> value="TO">Tonga (+676)</option>
                                                            <option data-countryCode="TT" <?php if($websetting->country=="TT"){ echo "selected";}?> value="TT">Trinidad &amp; Tobago (+1868)</option>
                                                            <option data-countryCode="TN" <?php if($websetting->country=="TN"){ echo "selected";}?> value="TN">Tunisia (+216)</option>
                                                            <option data-countryCode="TR" <?php if($websetting->country=="TR"){ echo "selected";}?> value="TR">Turkey (+90)</option>
                                                            <option data-countryCode="TM" <?php if($websetting->country=="TM"){ echo "selected";}?> value="TM">Turkmenistan (+7)</option>
                                                            <option data-countryCode="TM" <?php if($websetting->country=="TM"){ echo "selected";}?> value="TM">Turkmenistan (+993)</option>
                                                            <option data-countryCode="TC" <?php if($websetting->country=="TC"){ echo "selected";}?> value="TC">Turks &amp; Caicos Islands (+1649)</option>
                                                            <option data-countryCode="TV" <?php if($websetting->country=="TV"){ echo "selected";}?> value="TV">Tuvalu (+688)</option>
                                                            <option data-countryCode="UG" <?php if($websetting->country=="UG"){ echo "selected";}?> value="UG">Uganda (+256)</option>
                                                            <option data-countryCode="UA" <?php if($websetting->country=="UA"){ echo "selected";}?> value="UA">Ukraine (+380)</option>
                                                            <option data-countryCode="AE" <?php if($websetting->country=="AE"){ echo "selected";}?> value="AE">United Arab Emirates (+971)</option>
                                                            <option data-countryCode="UY" <?php if($websetting->country=="UY"){ echo "selected";}?> value="UY">Uruguay (+598)</option>
                                                            <option data-countryCode="UZ" <?php if($websetting->country=="UZ"){ echo "selected";}?> value="UZ">Uzbekistan (+7)</option>
                                                            <option data-countryCode="VU" <?php if($websetting->country=="VU"){ echo "selected";}?> value="VU">Vanuatu (+678)</option>
                                                            <option data-countryCode="VA" <?php if($websetting->country=="VA"){ echo "selected";}?> value="VA">Vatican City (+379)</option>
                                                            <option data-countryCode="VE" <?php if($websetting->country=="VE"){ echo "selected";}?> value="VE">Venezuela (+58)</option>
                                                            <option data-countryCode="VN" <?php if($websetting->country=="VN"){ echo "selected";}?> value="VN">Vietnam (+84)</option>
                                                            <option data-countryCode="VG" <?php if($websetting->country=="VG"){ echo "selected";}?> value="VG">Virgin Islands - British (+1284)</option>
                                                            <option data-countryCode="VI" <?php if($websetting->country=="VI"){ echo "selected";}?> value="VI">Virgin Islands - US (+1340)</option>
                                                            <option data-countryCode="WF" <?php if($websetting->country=="WF"){ echo "selected";}?> value="WF">Wallis &amp; Futuna (+681)</option>
                                                            <option data-countryCode="YE" <?php if($websetting->country=="YE"){ echo "selected";}?> value="YE">Yemen (North)(+969)</option>
                                                            <option data-countryCode="YE" <?php if($websetting->country=="YE"){ echo "selected";}?> value="YE">Yemen (South)(+967)</option>
                                                            <option data-countryCode="ZM" <?php if($websetting->country=="ZM"){ echo "selected";}?> value="ZM">Zambia (+260)</option>
                                                            <option data-countryCode="ZW" <?php if($websetting->country=="ZW"){ echo "selected";}?> value="ZW">Zimbabwe (+263)</option>
                                                        </optgroup>
												</select>
                        </div>
                    </div>
                    <!-- if setting logo is already uploaded -->
                    <?php if(!empty($websetting->logo)) {  ?>
                    <div class="form-group row">
                        <label for="logoPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($websetting->logo) ?>" alt="Picture" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="logo" class="col-xs-3 col-form-label"><?php echo display('logo') ?></label>
                        <div class="col-xs-9">
                            <input type="file" name="logo" id="logo">
                            <input type="hidden" name="old_logo" value="<?php echo $websetting->logo ?>">
                        </div>
                    </div>
                    <?php if(!empty($websetting->logo_footer)) {  ?>
                    <div class="form-group row">
                        <label for="logoPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($websetting->logo_footer) ?>" alt="Picture" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="logofooter" class="col-xs-3 col-form-label"><?php echo display('footer_logo') ?></label>
                        <div class="col-xs-9">
                            <input type="file" name="logofooter" id="logofooter">
                            <input type="hidden" name="old_footerlogo" value="<?php echo $websetting->logo_footer ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone2" class="col-xs-3 col-form-label"><?php echo display('webdisable') ?></label>
                        <div class="col-sm-9 customesl">
                            <select name="websiteonoff" class="form-control">
                                <option value=""><?php echo display('select_option') ?></option>
                                <option value="1" <?php if($websetting->web_onoff==1){ echo "selected";}?>><?php echo display('webon') ?></option>
                                <option value="0" <?php if($websetting->web_onoff==0){ echo "selected";}?>><?php echo display('weboff') ?></option>
                              </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="membershipenable" class="col-xs-3 col-form-label"><?php echo display('membershipenable') ?></label>
                        <div class="col-sm-9 customesl">
                            <select name="membershipenable" class="form-control">
                                <option value=""><?php echo display('select_option') ?></option>
                                <option value="1" <?php if($websetting->ismembership==1){ echo "selected";}?>><?php echo display('active') ?></option>
                                <option value="0" <?php if($websetting->ismembership==0){ echo "selected";}?>><?php echo display('inactive') ?></option>
                              </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mapenable" class="col-xs-3 col-form-label"><?php echo display('ismapenable') ?></label>
                        <div class="col-sm-2 customesl">
                            <select name="mapenable" class="form-control">
                                <option value=""><?php echo display('select_option') ?></option>
                                <option value="1" <?php if($websetting->ismapenable==1){ echo "selected";}?>><?php echo display('yes') ?></option>
                                <option value="0" <?php if($websetting->ismapenable==0){ echo "selected";}?>><?php echo display('no') ?></option>
                              </select>
                        </div>
                        <label for="mapkey" class="col-xs-2 col-form-label"><?php echo display('mapapikey') ?></label>
                        <div class="col-xs-5">
                      		<input name="mapkey" class="form-control" type="text" value="<?php echo $websetting->mapapikey; ?>" id="mapkey">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="quick_checkout" class="col-xs-3 col-form-label"><?php echo display('quick_checkout') ?></label>
                        <div class="col-sm-9 customesl">
                            <select name="quick_checkout" class="form-control">
                                <option value=""><?php echo display('select_option') ?></option>
                                <option value="1" <?php if($websetting->quick_checkout==1){ echo "selected";}?>><?php echo display('active') ?></option>
                                <option value="0" <?php if($websetting->quick_checkout==0){ echo "selected";}?>><?php echo display('inactive') ?></option>
                              </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-xs-3 col-form-label"><?php echo display('address') ?></label>
                        <div class="col-xs-9">                            
                            <textarea name="address" id="address" class="form-control tinymce2"  placeholder="<?php echo display('address') ?>"  rows="4"><?php echo $websetting->address ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="power_text" class="col-xs-3 col-form-label"><?php echo display('powered_by') ?></label>
                        <div class="col-xs-9">
                            <textarea name="power_text" class="form-control"  placeholder="Powered By Text" maxlength="140" rows="3"><?php echo $websetting->powerbytxt ?></textarea>
                        </div>
                    </div> 
                    
                    <?php 	$scan = scandir('application/modules/');
							$qrm="";
							foreach($scan as $file) {
							   if($file=="qrapp"){
								   if (file_exists(APPPATH.'modules/'.$file.'/assets/data/env')){
								   $qrm=1;
								   }
								   }
							}
							if($qrm==1){
							?>
                    
                    <!--<div class="form-group row">
                    	<label for="qrbgcolor" class="col-xs-3 col-form-label">QR Header Color</label>
                    	<div class="col-xs-3">
                      		<input name="headercolor" class="form-control" type="color" value="<?php echo $websetting->backgroundcolorqr ?>" id="headercolor">
                        </div>
                        <label for="qrfontcolor" class="col-xs-3 col-form-label">QR Header Font Color</label>
                        <div class="col-xs-3">
                      		<input name="headerfontcolor" class="form-control" type="color" value="<?php echo $websetting->qrheaderfontcolor ?>" id="headerfontcolor">
                        </div>
                    </div>-->
                    
                    <?php } ?>
                    
                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

