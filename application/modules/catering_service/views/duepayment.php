<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    



<div>
    <p>Total Amount: <?php echo $order->totalamount;?></p>
    <p>Paid Amount: <?php echo $order->customerpaid;?></p>
    <p>Due Amount: <?php echo $order->totalamount - $order->customerpaid;?></p>
    <a href="<?php echo base_url().'catering_service/cateringservice/makeDuePayment/'.$order_id?>">Make Payment</a>
</div>




</body>
</html>