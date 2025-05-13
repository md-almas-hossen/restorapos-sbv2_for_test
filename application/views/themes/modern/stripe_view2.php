<?php
$webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename; ?>
<link href="<?php echo base_url('application/views/themes/' . $acthemename . '/stripe_assets/checkout.css') ?>" rel="stylesheet" type="text/css" />
<script src="https://js.stripe.com/v3/"></script>
<script src="<?php echo base_url() ?>hungry/stripeordinfo/<?php echo $orderid;?>/<?php echo $page;?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/stripe_assets/checkout.js" defer></script>
<!-- Contact Area -->
<section class="sec_pad">
    <div class="container-xxl">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-container stripe">
                    <form id="payment-form">
                        <div id="payment-element">
                            <!--Stripe.js injects the Payment Element-->
                        </div>
                        <button id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay now</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>