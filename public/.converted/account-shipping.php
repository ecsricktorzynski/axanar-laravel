<?php

use Ares\Database\Db;
use Illuminate\Support\Facades\Auth;

$confirmLoggedIn = true;
$msg = null;
$icon = "fa fa-info-circle";

require_once __DIR__ . '/resources/views/layouts/header.php';

if ($_POST && array_key_exists("submit", $_POST)) {
    $userInfo = getUserAccountInfo();
    $userId = getUserId();
    $firstName = Db::quote($_POST['contact-firstname'] ?? '');
    $lastName = Db::quote($_POST['contact-lastname'] ?? '');
    $address1 = Db::quote($_POST['contact-address1'] ?? '');
    $address2 = Db::quote($_POST['contact-address2'] ?? '');
    $city = Db::quote($_POST['contact-city'] ?? '');
    $state = Db::quote($_POST['contact-state'] ?? '');
    $zip = Db::quote($_POST['contact-zip'] ?? '');
    $country = Db::quote($_POST['contact-country'] ?? '');
    $gender = Db::quote($_POST['contact-gender'] ?? '');
    $shirtSize = Db::quote($_POST['contact-shirtsize'] ?? '');
    $altName = Db::quote($_POST['contact-altname'] ?? '');
    if ($altName == "' '") {
        $altName = "''";
    }

    $sql = "";
    if (is_array($userInfo) && count($userInfo) > 0) {
        $sql = "UPDATE Addresses
            SET
            address_1 = $address1,
            address_2 = $address2,
            city = $city,
            country = $country,
            state = $state,
            zip = $zip,
            firstName = $firstName,
            lastName = $lastName,
            gender = $gender,
            shirtSize = $shirtSize,
            altName = $altName
            WHERE user_id = $userId;";
    } else {
        $sql = "INSERT INTO Addresses
            (user_id,
            address_1,
            address_2,
            city,
            country,
            state,
            zip,
            firstName,
            lastName,
            gender,
            shirtSize,
            altName)
            VALUES
            ($userId,
            $address1,
            $address2,
            $city,
            $country,
            $state,
            $zip,
            $firstName,
            $lastName,
            $gender,
            $shirtSize,
            $altName);
           ";
    }
    Db::query($sql);
    $msg = "Your information has been successfully updated";
    $icon = "fa fa-info-circle";
    updateUserAlert(getUserId(), 1);
}

$userInfo = getUserAccountInfo();
if ($userInfo == false || $userInfo == null) {
    $userInfo = [];
}

//var_dump($userInfo);

function printValueFromArray($arr, $key)
{
    if (is_array($arr) && count($arr) > 0) {
        $row = $arr[0];
        if (array_key_exists($key, $row)) {
            echo $row[$key];
        }
    }
    echo "";
}

function getValueFromArray($arr, $key)
{
    if (is_array($arr) && count($arr) > 0) {
        $row = $arr[0];
        if (array_key_exists($key, $row)) {
            return $row[$key];
        }
    }
    return "";
}

$address = "";

$altName = getValueFromArray($userInfo, "altName");
if ($altName == null || $altName == "" || $altName == " ") {
    $altName = getValueFromArray($userInfo, "firstName") . " " . getValueFromArray($userInfo, "lastName");
}
if (getValueFromArray($userInfo, "address_1") != "") {
    $address .= getValueFromArray($userInfo, "address_1") . "<br>";
}
if (getValueFromArray($userInfo, "address_2") != "") {
    $address .= getValueFromArray($userInfo, "address_2") . "<br>";
}
if (getValueFromArray($userInfo, "city") != "") {
    $address .= getValueFromArray($userInfo, "city") . "";
}
if (getValueFromArray($userInfo, "state") != "") {
    $address .= ", " . getValueFromArray($userInfo, "state");
}
if (getValueFromArray($userInfo, "zip") != "") {
    $address .= " " . getValueFromArray($userInfo, "zip");
}
$address .= "<br>";
if (getValueFromArray($userInfo, "country") != "") {
    $address .= getValueFromArray($userInfo, "country") . "<br>";
}
?>
<script>
  $(function() {
    $('#contact-shirtsize').val('<?php printValueFromArray($userInfo, "shirtSize"); ?>');
    $('#contact-gender').val('<?php printValueFromArray($userInfo, "gender"); ?>');
    $('#contact-country').val('<?php printValueFromArray($userInfo, "country"); ?>');
  });
</script>

<div id="page-container">

    <?php
    $mediaTitle = 'Shipping & Perk Personalization';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>

    <section class="site-content site-section">
        <div class="container">

            <?php showMessages($msg, "UPDATE SUCCESSFUL", $icon, false); ?>

            <div class="row">
                <div class="col-sm-4">
                    <h4 class="page-header"><strong>SHIPPING ADDRESS</strong></h4>
                    <strong><?php
                        if (getValueFromArray($userInfo, "firstName") != "") {
                            echo getValueFromArray($userInfo, "firstName");
                        }
                        if (getValueFromArray($userInfo, "lastName") != "") {
                            echo " " . getValueFromArray($userInfo, "lastName");
                        } ?>
                    </strong>
                    <address>
                        <?php echo $address; ?>
                        <br>
                    </address>
                </div>
                <div class="col-sm-4">
                    <h4 class="page-header"><strong>PERK PERSONALIZATION</strong></h4>
                    <address>&nbsp;<i class="fa fa-transgender"></i> &nbsp;<strong>T-Shirt Gender:</strong>&nbsp;&nbsp;<?php printValueFromArray($userInfo, "gender"); ?><br><i
                                class="fa fa-line-chart"></i> &nbsp;<strong>T-Shirt Size:</strong> &nbsp;<?php printValueFromArray($userInfo, "shirtSize"); ?><br></address>
                    <address>&nbsp;<i class="fa fa-list-ul"></i> &nbsp;<strong>Donor Listing As:</strong>&nbsp;<?php echo $altName; ?></address>
                </div>
                <div class="col-sm-4">
                    <h4 class="page-header"><strong>ACCOUNT SETTINGS</strong></h4>
                    <address><i class="fa fa-envelope-o"></i> &nbsp; <strong>Email:</strong>&nbsp;&nbsp;<?php printValueFromArray($userInfo, "email"); ?>
                        <br/><i class="fa fa-key"></i> &nbsp; <strong>Password:</strong> &nbsp;&lt;encrypted&gt;
                    </address>
                </div>
            </div>

            <form id="contact-info" name="contact-info" action="account-shipping.php" method="post">
                <div class="col-sm-6">
                    <h4 class="page-header"><i class="fa fa-gear"></i> &nbsp;<strong>UPDATE SHIPPING ADDRESS</strong></h4>
                    <div class="form-group">
                        <label for="contact-firstname">First Name</label>
                        <input type="text"
                               id="contact-firstname"
                               name="contact-firstname"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "firstName"); ?>"
                               placeholder="Enter First Name">
                    </div>
                    <div class="form-group">
                        <label for="contact-lastname">Last Name</label>
                        <input type="text"
                               id="contact-lastname"
                               name="contact-lastname"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "lastName"); ?>"
                               placeholder="Enter Last Name">
                    </div>
                    <div class="form-group">
                        <label for="contact-address1">Address Line 1</label>
                        <input type="text"
                               id="contact-address1"
                               name="contact-address1"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "address_1"); ?>"
                               placeholder="Enter Address Line 1">
                    </div>
                    <div class="form-group">
                        <label for="contact-address2">Address Line 2</label>
                        <input type="text"
                               id="contact-address2"
                               name="contact-address2"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "address_2"); ?>"
                               placeholder="Enter Address Line 2 or Ignore if Not Applicable">
                    </div>
                    <div class="form-group">
                        <label for="contact-city">City</label>
                        <input type="text"
                               id="contact-city"
                               name="contact-city"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "city"); ?>"
                               placeholder="Enter City">
                    </div>
                    <div class="form-group">
                        <label for="contact-state">State / Province</label>
                        <input type="text"
                               id="contact-state"
                               name="contact-state"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "state"); ?>"
                               placeholder="Enter State / Province">
                    </div>
                    <div class="form-group">
                        <label for="contact-zip">ZIP / Postal Code</label>
                        <input type="text"
                               id="contact-zip"
                               name="contact-zip"
                               class="form-control"
                               value="<?php printValueFromArray($userInfo, "zip"); ?>"
                               placeholder="Enter ZIP / Postal Code">
                    </div>
                    <div class="form-group">
                        <label for="contact-country">Country</label>
                        <select id="contact-country" name="contact-country" class="form-control" size="1">
                            <option value="" disabled selected>Please Select Your Country...</option>
                            <option value="US">United States</option>
                            <option value='AF'>Afghanistan</option>
                            <option value='AX'>Aland Islands</option>
                            <option value='AL'>Albania</option>
                            <option value='DZ'>Algeria</option>
                            <option value='AS'>American Samoa</option>
                            <option value='AD'>Andorra</option>
                            <option value='AO'>Angola</option>
                            <option value='AI'>Anguilla</option>
                            <option value='AQ'>Antarctica</option>
                            <option value='AG'>Antigua and Barbuda</option>
                            <option value='AR'>Argentina</option>
                            <option value='AM'>Armenia</option>
                            <option value='AW'>Aruba</option>
                            <option value='AU'>Australia</option>
                            <option value='AT'>Austria</option>
                            <option value='AZ'>Azerbaijan</option>
                            <option value='BS'>Bahamas</option>
                            <option value='BH'>Bahrain</option>
                            <option value='BD'>Bangladesh</option>
                            <option value='BB'>Barbados</option>
                            <option value='BY'>Belarus</option>
                            <option value='BE'>Belgium</option>
                            <option value='BZ'>Belize</option>
                            <option value='BJ'>Benin</option>
                            <option value='BM'>Bermuda</option>
                            <option value='BT'>Bhutan</option>
                            <option value='BO'>Bolivia, Plurinational State of</option>
                            <option value='BQ'>Bonaire, Sint Eustatius and Saba</option>
                            <option value='BA'>Bosnia and Herzegovina</option>
                            <option value='BW'>Botswana</option>
                            <option value='BV'>Bouvet Island</option>
                            <option value='BR'>Brazil</option>
                            <option value='IO'>British Indian Ocean Territory</option>
                            <option value='BN'>Brunei Darussalam</option>
                            <option value='BG'>Bulgaria</option>
                            <option value='BF'>Burkina Faso</option>
                            <option value='BI'>Burundi</option>
                            <option value='KH'>Cambodia</option>
                            <option value='CM'>Cameroon</option>
                            <option value='CA'>Canada</option>
                            <option value='CV'>Cape Verde</option>
                            <option value='KY'>Cayman Islands</option>
                            <option value='CF'>Central African Republic</option>
                            <option value='TD'>Chad</option>
                            <option value='CL'>Chile</option>
                            <option value='CN'>China</option>
                            <option value='CX'>Christmas Island</option>
                            <option value='CC'>Cocos (Keeling) Islands</option>
                            <option value='CO'>Colombia</option>
                            <option value='KM'>Comoros</option>
                            <option value='CG'>Congo</option>
                            <option value='CD'>Congo, the Democratic Republic of the</option>
                            <option value='CK'>Cook Islands</option>
                            <option value='CR'>Costa Rica</option>
                            <option value='CI'>Cote d'Ivoire</option>
                            <option value='HR'>Croatia</option>
                            <option value='CU'>Cuba</option>
                            <option value='CW'>Curacao</option>
                            <option value='CY'>Cyprus</option>
                            <option value='CZ'>Czech Republic</option>
                            <option value='DK'>Denmark</option>
                            <option value='DJ'>Djibouti</option>
                            <option value='DM'>Dominica</option>
                            <option value='DO'>Dominican Republic</option>
                            <option value='EC'>Ecuador</option>
                            <option value='EG'>Egypt</option>
                            <option value='SV'>El Salvador</option>
                            <option value='GQ'>Equatorial Guinea</option>
                            <option value='ER'>Eritrea</option>
                            <option value='EE'>Estonia</option>
                            <option value='ET'>Ethiopia</option>
                            <option value='FK'>Falkland Islands (Malvinas)</option>
                            <option value='FO'>Faroe Islands</option>
                            <option value='FJ'>Fiji</option>
                            <option value='FI'>Finland</option>
                            <option value='FR'>France</option>
                            <option value='GF'>French Guiana</option>
                            <option value='PF'>French Polynesia</option>
                            <option value='TF'>French Southern Territories</option>
                            <option value='GA'>Gabon</option>
                            <option value='GM'>Gambia</option>
                            <option value='GE'>Georgia</option>
                            <option value='DE'>Germany</option>
                            <option value='GH'>Ghana</option>
                            <option value='GI'>Gibraltar</option>
                            <option value='GR'>Greece</option>
                            <option value='GL'>Greenland</option>
                            <option value='GD'>Grenada</option>
                            <option value='GP'>Guadeloupe</option>
                            <option value='GU'>Guam</option>
                            <option value='GT'>Guatemala</option>
                            <option value='GG'>Guernsey</option>
                            <option value='GN'>Guinea</option>
                            <option value='GW'>Guinea-Bissau</option>
                            <option value='GY'>Guyana</option>
                            <option value='HT'>Haiti</option>
                            <option value='HM'>Heard Island and McDonald Islands</option>
                            <option value='VA'>Holy See (Vatican City State)</option>
                            <option value='HN'>Honduras</option>
                            <option value='HK'>Hong Kong</option>
                            <option value='HU'>Hungary</option>
                            <option value='IS'>Iceland</option>
                            <option value='IN'>India</option>
                            <option value='ID'>Indonesia</option>
                            <option value='IR'>Iran, Islamic Republic of</option>
                            <option value='IQ'>Iraq</option>
                            <option value='IE'>Ireland</option>
                            <option value='IM'>Isle of Man</option>
                            <option value='IL'>Israel</option>
                            <option value='IT'>Italy</option>
                            <option value='JM'>Jamaica</option>
                            <option value='JP'>Japan</option>
                            <option value='JE'>Jersey</option>
                            <option value='JO'>Jordan</option>
                            <option value='KZ'>Kazakhstan</option>
                            <option value='KE'>Kenya</option>
                            <option value='KI'>Kiribati</option>
                            <option value='KP'>Korea, Democratic People's Republic of</option>
                            <option value='KR'>Korea, Republic of</option>
                            <option value='KW'>Kuwait</option>
                            <option value='KG'>Kyrgyzstan</option>
                            <option value='LA'>Lao People's Democratic Republic</option>
                            <option value='LV'>Latvia</option>
                            <option value='LB'>Lebanon</option>
                            <option value='LS'>Lesotho</option>
                            <option value='LR'>Liberia</option>
                            <option value='LY'>Libya</option>
                            <option value='LI'>Liechtenstein</option>
                            <option value='LT'>Lithuania</option>
                            <option value='LU'>Luxembourg</option>
                            <option value='MO'>Macao</option>
                            <option value='MK'>Macedonia, the former Yugoslav Republic of</option>
                            <option value='MG'>Madagascar</option>
                            <option value='MW'>Malawi</option>
                            <option value='MY'>Malaysia</option>
                            <option value='MV'>Maldives</option>
                            <option value='ML'>Mali</option>
                            <option value='MT'>Malta</option>
                            <option value='MH'>Marshall Islands</option>
                            <option value='MQ'>Martinique</option>
                            <option value='MR'>Mauritania</option>
                            <option value='MU'>Mauritius</option>
                            <option value='YT'>Mayotte</option>
                            <option value='MX'>Mexico</option>
                            <option value='FM'>Micronesia, Federated States of</option>
                            <option value='MD'>Moldova, Republic of</option>
                            <option value='MC'>Monaco</option>
                            <option value='MN'>Mongolia</option>
                            <option value='ME'>Montenegro</option>
                            <option value='MS'>Montserrat</option>
                            <option value='MA'>Morocco</option>
                            <option value='MZ'>Mozambique</option>
                            <option value='MM'>Myanmar</option>
                            <option value='NA'>Namibia</option>
                            <option value='NR'>Nauru</option>
                            <option value='NP'>Nepal</option>
                            <option value='NL'>Netherlands</option>
                            <option value='NC'>New Caledonia</option>
                            <option value='NZ'>New Zealand</option>
                            <option value='NI'>Nicaragua</option>
                            <option value='NE'>Niger</option>
                            <option value='NG'>Nigeria</option>
                            <option value='NU'>Niue</option>
                            <option value='NF'>Norfolk Island</option>
                            <option value='MP'>Northern Mariana Islands</option>
                            <option value='NO'>Norway</option>
                            <option value='OM'>Oman</option>
                            <option value='PK'>Pakistan</option>
                            <option value='PW'>Palau</option>
                            <option value='PS'>Palestinian Territory, Occupied</option>
                            <option value='PA'>Panama</option>
                            <option value='PG'>Papua New Guinea</option>
                            <option value='PY'>Paraguay</option>
                            <option value='PE'>Peru</option>
                            <option value='PH'>Philippines</option>
                            <option value='PN'>Pitcairn</option>
                            <option value='PL'>Poland</option>
                            <option value='PT'>Portugal</option>
                            <option value='PR'>Puerto Rico</option>
                            <option value='QA'>Qatar</option>
                            <option value='RE'>Reunion</option>
                            <option value='RO'>Romania</option>
                            <option value='RU'>Russian Federation</option>
                            <option value='RW'>Rwanda</option>
                            <option value='BL'>Saint Barthelemy</option>
                            <option value='SH'>Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value='KN'>Saint Kitts and Nevis</option>
                            <option value='LC'>Saint Lucia</option>
                            <option value='MF'>Saint Martin (French part)</option>
                            <option value='PM'>Saint Pierre and Miquelon</option>
                            <option value='VC'>Saint Vincent and the Grenadines</option>
                            <option value='WS'>Samoa</option>
                            <option value='SM'>San Marino</option>
                            <option value='ST'>Sao Tome and Principe</option>
                            <option value='SA'>Saudi Arabia</option>
                            <option value='SN'>Senegal</option>
                            <option value='RS'>Serbia</option>
                            <option value='SC'>Seychelles</option>
                            <option value='SL'>Sierra Leone</option>
                            <option value='SG'>Singapore</option>
                            <option value='SX'>Sint Maarten (Dutch part)</option>
                            <option value='SK'>Slovakia</option>
                            <option value='SI'>Slovenia</option>
                            <option value='SB'>Solomon Islands</option>
                            <option value='SO'>Somalia</option>
                            <option value='ZA'>South Africa</option>
                            <option value='GS'>South Georgia and the South Sandwich Islands</option>
                            <option value='SS'>South Sudan</option>
                            <option value='ES'>Spain</option>
                            <option value='LK'>Sri Lanka</option>
                            <option value='SD'>Sudan</option>
                            <option value='SR'>Suriname</option>
                            <option value='SJ'>Svalbard and Jan Mayen</option>
                            <option value='SZ'>Swaziland</option>
                            <option value='SE'>Sweden</option>
                            <option value='CH'>Switzerland</option>
                            <option value='SY'>Syrian Arab Republic</option>
                            <option value='TW'>Taiwan, Province of China</option>
                            <option value='TJ'>Tajikistan</option>
                            <option value='TZ'>Tanzania, United Republic of</option>
                            <option value='TH'>Thailand</option>
                            <option value='TL'>Timor-Leste</option>
                            <option value='TG'>Togo</option>
                            <option value='TK'>Tokelau</option>
                            <option value='TO'>Tonga</option>
                            <option value='TT'>Trinidad and Tobago</option>
                            <option value='TN'>Tunisia</option>
                            <option value='TR'>Turkey</option>
                            <option value='TM'>Turkmenistan</option>
                            <option value='TC'>Turks and Caicos Islands</option>
                            <option value='TV'>Tuvalu</option>
                            <option value='UG'>Uganda</option>
                            <option value='UA'>Ukraine</option>
                            <option value='AE'>United Arab Emirates</option>
                            <option value='GB'>United Kingdom</option>
                            <option value='US'>United States</option>
                            <option value='UM'>United States Minor Outlying Islands</option>
                            <option value='UY'>Uruguay</option>
                            <option value='UZ'>Uzbekistan</option>
                            <option value='VU'>Vanuatu</option>
                            <option value='VE'>Venezuela, Bolivarian Republic of</option>
                            <option value='VN'>Viet Nam</option>
                            <option value='VG'>Virgin Islands, British</option>
                            <option value='VI'>Virgin Islands, U.S.</option>
                            <option value='WF'>Wallis and Futuna</option>
                            <option value='EH'>Western Sahara</option>
                            <option value='YE'>Yemen</option>
                            <option value='ZM'>Zambia</option>
                            <option value='ZW'>Zimbabwe</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4 class="page-header"><i class="fa fa-gear"></i> &nbsp;<strong>UPDATE PERK PERSONALIZATION</strong></h4>
                    <p>Please note that <?= config('versionName') ?> is requesting the following personalization information from every donor, and it does not
                        necessarily mean that your specific donation package includes a perk such as a t-shirt, video disc, or other preference that is being requested. We're doing
                        this to help us gauge sizing statistics for future perk offerings and which quantities of each size would be needed from the chosen manufacturer. This
                        information will not be shared with anyone outside of <?= config('versionName') ?> or <?= config('versionName') ?> proper -- internal use only, guaranteed. If you have a question
                        as to whether your donation package includes such items then please visit your account dashboard and review your specific donation details.</p>
                    <div class="form-group">
                        <label for="contact-gender">Gender</label>
                        <select id="contact-gender" name="contact-gender" class="form-control" size="1">
                            <option value="" disabled selected>Please Select Your Gender...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact-shirtsize">T-Shirt Size</label>
                        <select id="contact-shirtsize" name="contact-shirtsize" class="form-control" size="1">
                            <option value="" disabled selected>Please Select Your T-Shirt Size...</option>
                            <option value="Adult XS">Adult XS</option>
                            <option value="Adult S">Adult S</option>
                            <option value="Adult M">Adult M</option>
                            <option value="Adult L">Adult L</option>
                            <option value="Adult XL">Adult XL</option>
                            <option value="Adult 2XL">Adult 2XL</option>
                            <option value="Adult 3XL">Adult 3XL</option>
                        </select>
                    </div>
                    <h4 class="page-header"><i class="fa fa-list-ul"></i> &nbsp;<strong>DONOR LIST ALTERNATE NAME</strong></h4>
                    <div class="form-group">
                        <label for="contact-altname">Donor List Alternate Listing</label>
                        <p>On the Donors Listing on the Axanar.com website, as a donor, your name will appear as First Name + Last Name (e.g.
                            "Bill Watters"), if you would prefer to have it appear as something else, or a nickname, please enter it below (max of 40 characters).</p>
                        <input type="text" id="contact-altname" name="contact-altname" class="form-control" value="<?php echo $altName; ?>" placeholder="<?php echo $altName; ?>">
                    </div>
                </div>

                <div class="col-sm-12 text-center">
                    <hr>
                    <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit Changes"></div>

            </form>

        </div>

        <br/><br/>


    </section>


    <footer class="site-footer site-section" style="background-color:rgba(37, 37, 37, 0.0);">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading" style="font-family:'SerpentineStd-Light' !important;">Visit Axanar</h4>
                    <ul class="footer-nav list-inline">
                        <li><a href="http://www.axanar.com" target="_blank" class="site-logo"><i class="gi gi-globe" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.facebook.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-facebook" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.twitter.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-twitter" style="font-size:1em;"></i></a></li>
                        <li><a href="https://instagram.com/startrekaxanar" target="_blank" class="site-logo"><i class="si si-instagram" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.pinterest.com/startrekaxanar" target="_blank" class="site-logo"><i class="si si-pinterest" style="font-size:1em;"></i></a></li>
                        <li><a href="https://plus.google.com/+StarTrekAxanarOfficial" target="_blank" class="site-logo"><i class="si si-google_plus" style="font-size:1em;"></i></a>
                        </li>
                        <li><a href="https://www.youtube.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-youtube" style="font-size:1em;"></i></a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading"></h4>
                    <ul class="footer-nav list-inline"></ul>
                </div>
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading" style="font-family:'SerpentineStd-Light' !important;letter-spacing: 2px;">Powered by <a href="http://axanar.com"
                                                                                                                                       target="_blank"
                                                                                                                                       style="color:#ffffff;text-decoration:none;"><strong
                                    style="color:#ffffaa;font-family:'SerpentineStd-Medium' !important;">ARES</strong>.digital</a></h4>
                    <ul class="footer-nav list-inline" style="font-family:'SerpentineStd-Light' !important;letter-spacing: 1px;">&nbsp;Copyright &copy;2015-2016 - All rights
                        reserved.
                    </ul>
                </div>
            </div>
        </div>
    </footer>


</div>

<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>
