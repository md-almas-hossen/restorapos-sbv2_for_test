<?php
$webinfo = $this->webinfo;
$qrtheme = $this->settingqr;
$storeinfo = $this->settinginfo;
$currency = $this->storecurrency;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
?>

<?php

$totalqty = 0;
if (!empty($this->cart->contents())) {
    $totalqty = count($this->cart->contents());
};

$calvat = 0;
$discount = 0;
$itemtotal = 0;
$pvat = 0;
$totalamount = 0;
$subtotal = 0;
if ($cart = $this->cart->contents()) {
    $totalamount = 0;
    $subtotal = 0;
    $pvat = 0;
    $i = 0;
    foreach ($cart as $item) {
        $itemprice = $item['price'] * $item['qty'];
        $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
        $vatcalc = $itemprice * $iteminfo->productvat / 100;
        $pvat = $pvat + $vatcalc;
        if ($iteminfo->OffersRate > 0) {
            $discal = $itemprice * $iteminfo->OffersRate / 100;
            $discount = $discal + $discount;
        } else {
            $discount = $discount;
        }
        if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
            $nittotal = $item['addontpr'] + $item['alltoppingprice'];
            $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
        } else {
            $nittotal = 0;
            $itemprice = $itemprice;
        }
        $totalamount = $totalamount + $nittotal;
        $subtotal = $subtotal + $item['price'] * $item['qty'];
        $i++;
    }
}

if (!empty($this->cart->contents())) {
    $itemtotal = $totalamount + $subtotal;
    if ($this->settinginfo->vat > 0) {
        $calvat = $itemtotal * $this->settinginfo->vat / 100;
    } else {
        $calvat = $pvat;
    }

    $totalqty = 0;
    $totalamount = 0;
    $pvat = 0;
    $discount = 0;
    $grandtotal = 0;
    if ($this->cart->contents() > 0) {
        $totalqty = count($this->cart->contents());
        $itemprice = 0;
        $pvat = 0;
        $discount = 0;
        foreach ($this->cart->contents() as $item) {
            $itemprice = $item['price'] * $item['qty'];
            $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
            $vatcalc = $itemprice * $iteminfo->productvat / 100;
            $pvat = $pvat + $vatcalc;
            if ($iteminfo->OffersRate > 0) {
                $discal = $itemprice * $iteminfo->OffersRate / 100;
                $discount = $discal + $discount;
            } else {
                $discount = $discount;
            }
            if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
                $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
            } else {
                $itemprice = $itemprice;
            }
            $totalamount = $itemprice + $totalamount;
        }

        if ($this->settinginfo->vat > 0) {
            $calvat = $totalamount * $this->settinginfo->vat / 100;
        } else {
            $calvat = $pvat;
        }
        if ($this->settinginfo->service_chargeType == 1) {
            $servicecharge = $totalamount * $this->settinginfo->servicecharge / 100;
        } else {
            $servicecharge = $this->settinginfo->servicecharge;
        }
        $coupon = 0;
        if (!empty($this->session->userdata('couponcode'))) {
            $coupon = $this->session->userdata('couponprice');
        }

        $isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
        if (!empty($isvatinclusive)) {
            $grandtotal = $itemtotal + $servicecharge - ($discount + $coupon);
        } else {
            $grandtotal = $itemtotal + $calvat + $servicecharge - ($discount + $coupon);
        }
    }
}

$multiplletax = array();
if ($this->cart->contents() > 0) {
    foreach ($this->cart->contents() as $item) {
        $itemprice = $item['price'] * $item['qty'];
        $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
        if (!empty($taxinfos)) {
            $tx = 0;
            if ($iteminfo->OffersRate > 0) {
                $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
            }
            $itemvalprice =  ($itemprice - $mypdiscountprice);
            foreach ($taxinfos as $taxinfo) {
                $fildname = 'tax' . $tx;
                if (!empty($iteminfo->$fildname)) {
                    $vatcalc = $itemvalprice * $iteminfo->$fildname / 100;
                    $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                } else {
                    $vatcalc = $itemvalprice * $taxinfo['default_value'] / 100;
                    $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                }

                $pvat = $pvat + $vatcalc;
                $vatcalc = 0;
                $tx++;
            }
        } else {
            $vatcalc = $itemprice * $iteminfo->productvat / 100;
            $pvat = $pvat + $vatcalc;
        }
    }
}
$multiplletaxvalue = htmlentities(serialize($multiplletax));
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo $qrtheme->theme; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo isset($seoinfo) ? $seoinfo->description : ''; ?>">
    <meta name="keywords" content="<?php echo isset($seoinfo) ? $seoinfo->keywords : ''; ?>">

    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url((!empty($this->settinginfo->favicon) ? $this->settinginfo->favicon : 'application/views/themes/' . $acthemename . '/assets_web/images/favicon.png')) ?>">
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/jquery-3.3.1.min.js"></script>
    <!--====== Plugins CSS Files =======-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/app.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/pos-topping.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
</head>

<body class="<?php echo $qrtheme->theme; ?>1">
    <div class="content-wrap">
        <div class="top-header py-4 px-3 d-flex align-items-center" style="background:<?php if (!empty($qrtheme->backgroundcolorqr)) echo $qrtheme->backgroundcolorqr; ?>;">
            <div class="">
                <button type="button" class="btn btn-primary btn-menu p-0 d-flex align-items-center justify-content-center me-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 13H8M1 1H17H1ZM1 7H17H1Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <a href="<?php echo base_url(); ?>qr-menu" class="logo-wrap">
                <img src="<?php echo base_url(!empty($webinfo->logo) ? $webinfo->logo : 'dummyimage/168x65.jpg'); ?>" alt="">
            </a>
        </div>

        <div id="responsemsg"></div>

        <form method="post" action="<?php echo base_url('hungry/placeorderqr'); ?>" onsubmit="return checkValidCheckout(this);">
            <input type="hidden" name="csrf_test_name" value="">

            <input name="vat" id="vat" type="hidden" value="<?php echo $calvat; ?>" />
            <input name="invoice_discount" id="invoice_discount" type="hidden" value="<?php echo $discount + $coupon; ?>" />
            <input name="service_charge" id="servicecharge" type="hidden" value="<?php echo $servicecharge; ?>" />
            <input name="orggrandTotal" id="orggrandTotal" type="hidden" value="<?php echo $totalamount; ?>" />
            <input type="hidden" readonly class="form-control-plaintext text-right" id="table" value="<?php echo $this->session->userdata('tableid'); ?>">
            <input name="multiplletaxvalue" id="multiplletaxvalue" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />
            <input type="hidden" id="grandtotal" name="grandtotal" value="<?php echo $totalamount + $calvat + $servicecharge - ($discount + $coupon); ?>">
            <input type="hidden" id="shippingtype" name="shippingtype" value="3" />
            <input type="hidden" name="customertype" value="2" />
            <input type="hidden" name="psid" id="psid">

            <div class="p-3 bg-light">
                <div class="text-center mb-4">
                    <ul class="bg-white border-0 d-inline-flex nav nav-tabs p-2 rounded-3" id="myTab" role="tablist">
                        <?php
                        if (!empty($shipping_methods)) {
                            foreach ($shipping_methods as $smk => $shipping_method) {
                        ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-2 border-0 <?php echo !$smk ? 'active' : ''; ?>" id="tab-<?php echo $smk; ?>" data-bs-toggle="tab" data-bs-target="#tab-pane-<?php echo $smk; ?>" type="button" role="tab" aria-controls="tab-pane-<?php echo $smk; ?>" aria-selected="true">
                                        <?php echo $shipping_method->shipping_method; ?>
                                    </button>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>

                <div class="text-left mb-4">
                    <div class="mb-3">
                        <label for="orderNotes" class="form-label mb-1 text-dark fw-medium"><?php echo display('ordnote') ?></label>
                        <input type="text" class="form-control" id="orderNotes" name="ordernote" placeholder="<?php echo display('ordnote') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="customerName" class="form-label mb-1 text-dark fw-medium"><?php echo display('customer_name') ?></label>
                        <input type="text" class="form-control" id="customerName" name="customerName" placeholder="<?php echo display('customer_name') ?>" value="<?php echo $customerinfo ? $customerinfo->customer_name : ''; ?>" required>
                        <input type="hidden" id="customerEmail" name="customerEmail">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label mb-1 text-dark fw-medium"><?php echo display('phone') ?></label>
                        <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="phone" name="phone" placeholder="+880" value="<?php echo $customerinfo ? $customerinfo->customer_phone : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <?php if ($this->session->flashdata('message')) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $this->session->flashdata('message') ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>

                        <?php if ($this->session->flashdata('exception')) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $this->session->flashdata('exception') ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>

                        <?php if (validation_errors()) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo validation_errors() ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="tab-content" id="myTabContent" style="padding-bottom: 50px;">

                    <?php
                    if (!empty($shipping_methods)) {
                        foreach ($shipping_methods as $smk => $shipping_method) {
                            $payment_methods = explode(',', $shipping_method->payment_method);
                            $payment_method_names = explode(',', $shipping_method->payment_method_names);
                    ?>
                            <div class="tab-pane fade <?php echo !$smk ? 'show active' : '' ?>" id="tab-pane-<?php echo $smk; ?>" role="tabpanel" tabindex="0">
                                <?php if (preg_match('/deliva|ery/i', $shipping_method->shipping_method)) : ?>
                                    <div class="mb-3">
                                        <label for="customerAddress" class="form-label mb-1 text-dark fw-medium"><?php echo display('address') ?></label>
                                        <textarea class="form-control" id="customerAddress" name="address" placeholder="<?php echo display('address') ?>"><?php echo $customerinfo ? $customerinfo->customer_address : ''; ?></textarea>
                                    </div>
                                <?php endif ?>

                                <h6 class="fw-semibold mb-3">Payment Type</h6>

                                <?php foreach ($payment_method_names as $pmk => $payment_method_name) { ?>
                                    <div class="payment-radio form-check pl-0 rounded bg-white border mb-2">
                                        <input type="radio" name="card_type" id="<?php echo 'pm_sm_' . $smk . $pmk; ?>" data-parent="#payment" value="<?php echo trim($payment_methods[$pmk]); ?>" class="form-check-input appcheckoutpay">

                                        <label class="form-check-label d-flex align-items-center" for="<?php echo 'pm_sm_' . $smk . $pmk; ?>">
                                            <span class="checkbox-img me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                                </svg>
                                            </span>
                                            <span class="text-dark fs-17 fw-medium"><?php echo $payment_method_name ?></span>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>

            <button class="cart-total d-flex align-items-center justify-content-between bg-primary text-white position-fixed rounded-3 border-0" type="submit">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="ms-1 fw-medium">Submit Order</span>
                </div>
            </button>
        </form>
    </div>

    <!-- Start offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <!-- <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5> -->
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo base_url() . 'app-terms'; ?>"><?php echo display('terms_condition') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url() . 'app-refund-policty'; ?>"><?php echo display('refundp') ?></a>
                </li>
                <?php
                if ($this->session->userdata('CusUserID') != "") { ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url() . 'apporedrlist'; ?>"><?php echo display('morderlist') ?></a></li>
                <?php } ?>

            </ul>
        </div>
    </div>

    <!-- /.End offcanvas -->

    <!--====== SCRIPTS JS ======-->
    <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/read-more.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/appcustom.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/returnpolicyqr.js"></script>

    <script type="text/javascript">
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/messenger.Extensions.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'Messenger'));
    </script>

    <script type="text/javascript">
        window.extAsyncInit = function() {
            MessengerExtensions.getContext(
                '<?php echo $app_id; ?>',
                function(thread_context) {
                    // success
                    // console.log('get context success :>> ', thread_context);
                    var psid = thread_context.psid,
                        base_url = '<?php echo base_url(); ?>';

                    $('#psid').val(psid);
                    // $('#responsemsg').append(JSON.stringify(thread_context));

                    // get profile info
                    fetch(base_url + 'meta/messenger/get_user_profile/' + psid)
                        .then(response => response.json())
                        .then((response) => {
                            // $('#responsemsg').append(JSON.stringify(response));

                            if (response.success) {
                                $('#phone').val(response.data.customer_phone);
                                $('#customerName').val(response.data.name);
                                $('#customerEmail').val(response.data.email);
                                $('#customerAddress').val(response.data.customer_address);
                                return true;
                            }
                        });
                },
                function(err) {
                    // error
                    console.error(err);
                    // $('#responsemsg').append('Get context failed !');
                    // $('#responsemsg').append(JSON.stringify(err));
                }
            );
        };
    </script>
</body>

</html>