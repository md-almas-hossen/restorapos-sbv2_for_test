
<link href="<?php echo base_url('application/modules/dashboard/assest/css/new_dashboard.css'); ?>" rel="stylesheet"
    type="text/css" />

<style>
.bg_ready {
    background: linear-gradient(to bottom, #eafff5, #ffffff);
}
.dark-mode .bg_ready {
    background: linear-gradient(to bottom,rgb(56, 56, 56),rgb(20, 20, 20));
}

.bg_processing {
    background: linear-gradient(to bottom, #FFF1F5, #ffffff);
}

.bg_pending {
    background: linear-gradient(to bottom, #FFF7EC, #ffffff);
}

.bg_default {
    background-color: #ffffff;
}
</style>

<!-- New Dashboard Design -->
<section class="counter_waiting_display">
    <!-- counter card 1 -->
    <?php foreach ($counter_card as $cardType => $cardData): ?>
    <?php        
        $background_color = '';
        switch ($cardType) {
            case 'Ready':
                $background_color = 'bg_ready';
                break;
            case 'Processing':
                $background_color = 'bg_processing'; 
                break;
            case 'Pending':
                $background_color = 'bg_pending'; 
                break;
            default:
                $background_color = 'bg_default';
        }
    ?>
    <div class="counter_card">
        <!-- Card Header -->
        <div class="counter_card_header" style="background-image: url('<?= $cardData[0]['heading_bg']; ?>');">
            <h3><?= $cardData[0]['title']; ?></h3>
        </div>

        <!-- Card Body -->
        <div class="counter_card_body <?= ($background_color) ?>">
            <?php foreach ($cardData[0]['items'] as $item): ?>
            <!-- Counter Item -->
            <div class="counter_item">
                <table>
                    <tr class="border_bottom">
                        <td>
                            <figure>
                                <img src="<?= $item['table_img']; ?>" alt="Table Image">
                            </figure>
                        </td>
                        <td class="padding_left">
                            <p class="fw_600">Table No:</p>
                            <p class="header_xl"><?= $item['table_no']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <p class="fw_600">Order No:</p>
                            <p class="header_xl"><?= $item['order_no']; ?></p>
                        </td>
                        <td class="padding_left" style="width: 50%;">
                            <p class="fw_600">Running Time:</p>
                            <p class="header_xl"><?= $item['runtime']; ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>


</section>






<!-- Script & Script Link -->

<input name="monthname" id="monthname" type="hidden" value="<?php echo $monthname;?>" />
<input name="alldays" id="alldays" type="hidden" value="<?php echo $alldays;?>" />
<input name="dailylysaleamount" id="dailylysaleamount" type="hidden" value="<?php echo $dailysaleamount;?>" />
<input name="dailypurchase" id="dailypurchase" type="hidden" value="<?php echo $dailypurchaseamount;?>" />

<input name="weekname" id="weekname" type="hidden" value="<?php echo @$allweekname;?>" />
<input name="weekylysaleamount" id="weekylysaleamount" type="hidden" value="<?php echo @$weeklysaleamount;?>" />
<input name="customertypesale" id="customertypesale" type="hidden" value="<?php echo $customertypewisesales;?>" />

<input name="piesaledata" id="piesaledata" type="hidden" value="<?php echo $piedatasales;?>" />

<input name="monthlysaleamount" id="monthlysaleamount" type="hidden" value="<?php echo $monthlysaleamount;?>" />
<input name="monthlysaleorder" id="monthlysaleorder" type="hidden" value="<?php echo $monthlysaleorder;?>" />
<input name="hourlyordernum" id="hourlyordernum" type="hidden" value="<?php echo $hourlyordernum;?>" />
<input name="hourlyorderval" id="hourlyorderval" type="hidden" value="<?php echo $hourlyorderval;?>" />
<input name="hourltimeslot" id="hourltimeslot" type="hidden" value="<?php echo $hourltimeslot;?>" />
<input name="waiter" id="waiter" type="hidden" value="<?php echo $waiter;?>" />
<input name="waiterordervalue" id="waiterordervalue" type="hidden" value="<?php echo $waiterordervalue;?>" />

<input name="incomes" id="incomes" type="hidden" value="<?php echo $incomes;?>" />
<input name="expenses" id="expenses" type="hidden" value="<?php echo $expenses;?>" />

<?php if(isset($_GET['status'])){?>
<input name="registerclose" id="registerclose" type="hidden" value="<?php echo $_GET['status'];?>" />
<?php } ?>

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/possetting.js?v=1.2'); ?>"
    type="text/javascript"></script>
