
<link href="<?php echo base_url('application/modules/dashboard/assest/css/new_dashboard.css'); ?>" rel="stylesheet"
    type="text/css" />

<!-- Select Dropdown Style -->
<!--  -->
<style>
.bg_pending {
    background-color: rgb(255, 244, 244);
}

.bg_processing {
    background-color: rgb(237, 241, 252);
}

.bg_prepared {
    background-color: rgb(244, 252, 244);
}

.bg_default {
    background-color: #ffffff;
}
</style>
<!--  -->

<!-- New Dashboard Design -->
<section class="kitchen_dashboard_container">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="btn_category_wrapper">
            <button type="button" class="btn_kitchen_category_all" data-filter="all">All Token (30)</button>
            <button type="button" class="btn_kitchen_category_pending" data-filter="Pending">Pending Token (10)</button>
            <button type="button" class="btn_kitchen_category_processing" data-filter="Processing">Processing Token
                (2)</button>
            <button type="button" class="btn_kitchen_category_prepared" data-filter="Prepared">Prepared Token
                (20)</button>
        </div>

        <div class="d-flex align-items-center">
            <!-- style 1 -->

            <!-- <div>
                <select class="kitchen_type">
                    <option value="" selected>Kitchen Type</option>
                    <option value="">Kitchen Type-1</option>
                    <option value="">Kitchen Type-2</option>
                </select>
            </div> -->

            <!-- style 2 -->
            <div class="custom_select" style="width:200px;">
                <select id="kitchenTypeFilter">
                    <option value="0">Kitchen Type</option>
                    <option value="Takeaway">Takeaway</option>
                    <option value="Dine-In">Dine-In</option>
                </select>

            </div>


            <div>
                <button type="button" class="btn_page_refresh"><i class="fas fa-redo"></i> Refresh</button>
            </div>
        </div>
    </div>
</section>



<section class="kitchen_card_wrapper">
    <!-- card -1 -->
    <?php foreach ($kitchen_card as $category => $orders) { ?>
    <?php        
        $background_color = '';
        switch ($category) {
            case 'Pending':
                $background_color = 'bg_pending';
                break;
            case 'Processing':
                $background_color = 'bg_processing'; 
                break;
            case 'Prepared':
                $background_color = 'bg_prepared'; 
                break;
            default:
                $background_color = 'bg_default';
        }
    ?>

    <?php foreach ($orders as $order) { ?>
    <div class="kitchen_card" data-category="<?php echo $category ?>">
        <div>
            <!-- Card Header -->
            <div class="card_header border_bottom <?php echo $background_color ?>">
                <div class="">
                    <p class="text_xs fw_600">Token No:</p>
                    <h2 class="header_xl token_no"><?php echo $order['token_no'] ?></h2>
                </div>
                <div>

                    <p class="text_xs fw_600">Table:</p>
                    <!-- <h2
                        class="header_xl <?= ($category === 'Pending') ? 'text-danger' : (($category === 'Processing') ? 'text-primary' : 'text-success') ?>">
                        <?= $order['table_no'] ?>
                    </h2> -->
                    <h2 class="header_xl table_no">
                        <?= $order['table_no'] ?>
                    </h2>
                </div>
                <div>
                    <p class="text_xs fw_600">Order No:</p>
                    <h2 class="header_xl order_no"><?php echo $order['order_no'] ?></h2>
                </div>
            </div>
            <div class="card_header border_bottom <?php echo $background_color ?>">
                <div>
                    <p class="text_xs fw_600">Type:</p>
                    <h2 class="header_md"><?php echo $order['type'] ?></h2>
                </div>
                <div>
                    <p class="text_xs fw_600">Waiter:</p>
                    <h2 class="header_md"><?php echo $order['waiter'] ?></h2>
                </div>
                <div>
                    <p class="text_xs fw_600">Customer:</p>
                    <h2 class="header_md"><?php echo $order['customer'] ?></h2>
                </div>
            </div>

            <!-- Card Body -->
            <div class="kitchen_card_body">
                <?php foreach ($order['items'] as $item_key => $item) { ?>
                <div class="item_list">
                    <!-- Item -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="<?= $item_key ?>" name="<?= $item_key ?>">
                            <label for="<?= $item_key ?>" class="header_lg"><?php echo $item['item_name'] ?></label>


                            <!-- <input type="checkbox" id="item-<?= $key ?>-<?= $item_key ?>"
                                name="item-<?= $key ?>-<?= $item_key ?>" order="<?php echo $item['item_name'] ?>">
                            <label for="item-<?= $key ?>-<?= $item_key ?>"
                                class="header_lg"><?php echo $item['item_name'] ?></label> -->
                        </div>
                        <p class="header_lg">1x</p>
                    </div>
                    <!-- Addons -->
                    <?php if (!empty($item['addons'])) { ?>
                    <div class="addons">
                        <strong>Addons:</strong>
                        <ul class="addons_list">
                            <?php foreach ($item['addons'] as $addon) { ?>
                            <li><?php echo $addon ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <!-- Notes -->
                    <?php if (!empty($item['note'])) { ?>
                    <div>
                        <strong>Note:</strong> <?php echo $item['note'] ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="kitchen_card_footer d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <input type="checkbox" id="<?= $item_key ?>" name="<?= $item_key ?>" value="All">
                <label for="<?= $item_key ?>" class="header_lg">All</label>
            </div>


            <div>
                <?= ($category === 'Pending') ? '<button type="button" class="btn btn-danger btn_reject">Reject</button> <button type="button" class="btn btn-success btn_accept">Accept</button>' : (($category === 'Processing')  ? '<button type="button" class="btn btn-primary btn_processing">Processing</button>' : '') ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php } ?>


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

<!-- custom-select -->
<script>
var x, i, j, l, ll, selElmnt, a, b, c;

x = document.getElementsByClassName("custom_select");
l = x.length;
for (i = 0; i < l; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    ll = selElmnt.length;   
    a = document.createElement("DIV");
    a.setAttribute("class", "select_selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    
    b = document.createElement("DIV");
    b.setAttribute("class", "select_items select-hide");
    for (j = 1; j < ll; j++) {
        
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {            
            var y, i, k, s, h, sl, yl;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            sl = s.length;
            h = this.parentNode.previousSibling;
            for (i = 0; i < sl; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    yl = y.length;
                    for (k = 0; k < yl; k++) {
                        y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            h.click();
        });
        b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
       
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}

function closeAllSelect(elmnt) {   
    var x, y, i, xl, yl, arrNo = [];
    x = document.getElementsByClassName("select_items");
    y = document.getElementsByClassName("select_selected");
    xl = x.length;
    yl = y.length;
    for (i = 0; i < yl; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active");
        }
    }
    for (i = 0; i < xl; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
        }
    }
}

document.addEventListener("click", closeAllSelect);
</script>


<!-- card script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".btn_category_wrapper button");
    const cards = document.querySelectorAll(".kitchen_card");

    buttons.forEach((button) => {
        button.addEventListener("click", function() {
            const filter = this.getAttribute("data-filter");

            cards.forEach((card) => {
                if (filter === "all" || card.getAttribute("data-category") === filter) {
                    card.style.display = "block"; // Show card
                } else {
                    card.style.display = "none"; // Hide card
                }
            });
        });
    });
});
</script>