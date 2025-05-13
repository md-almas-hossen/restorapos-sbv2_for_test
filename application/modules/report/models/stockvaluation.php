<?php //stock Report for report module

$demosql="select l.itemid, f.ProductName,
sum(open_qty) open_qty,
sum(prod_qty) prod_qty ,
sum(sale_qty) sale_qty,
sum(expire_qty) expire_qty,
sum(damage_qty) damage_qty,
sum(open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) as stock_qty, 
sum(if(price is null,0,price) * if((open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) < 0 , 0 , (open_qty + prod_qty  - sale_qty - expire_qty - damage_qty))) as stockvalue
From (
    select itemid, 
    sum(open_qty) open_qty,
    sum(prod_qty) prod_qty,     
    sum(sale_qty) sale_qty,
    sum(expire_qty) expire_qty, 
    sum(damage_qty) damage_qty   
    From (
    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate` < '2023-04-01'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` < '2023-04-01'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date < '2023-04-01'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `createdate` < '2023-04-01'
    Group by `pid`

    union ALL

    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate`  between '2023-04-01' and '2023-04-29'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` between '2023-04-01' and '2023-04-29'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date between '2023-04-01' and '2023-04-29'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `createdate` between '2023-04-01' and '2023-04-29'
    Group by `pid`
    ) t
    group by itemid
) l
left join ( 
    SELECT  i.itemid, avg(d.`price`) price
	FROM `purchase_details` d 
	left join ingredients i on d.`indredientid` = i.id 
	WHERE itemid is not null
	and `purchasedate` < '2023-04-29'
	group by i.itemid
) p on l.itemid = p.itemid
left join item_foods f on l.itemid = f.ProductsID
Group by l.itemid";


//balance sheet valuation

$sql="select sum(if(price is null,0,price) * if((open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) < 0 , 0 , (open_qty + prod_qty  - sale_qty - expire_qty - damage_qty))) as stockvalue
From (
    select itemid, 
    sum(open_qty) open_qty,
    sum(prod_qty) prod_qty,     
    sum(sale_qty) sale_qty,
    sum(expire_qty) expire_qty, 
    sum(damage_qty) damage_qty   
    From (
    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate` < '2023-04-01'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` < '2023-04-01'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date < '2023-04-01'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `createdate` < '2023-04-01'
    Group by `pid`

    union ALL

    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate`  between '2023-04-01' and '2023-04-29'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` between '2023-04-01' and '2023-04-29'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date between '2023-04-01' and '2023-04-29'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `createdate` between '2023-04-01' and '2023-04-29'
    Group by `pid`
    ) t
    group by itemid
) l
left join ( 
    SELECT  i.itemid, avg(d.`price`) price
	FROM `purchase_details` d 
	left join ingredients i on d.`indredientid` = i.id 
	WHERE itemid is not null
	and `purchasedate` < '2023-04-29'
	group by i.itemid
) p on l.itemid = p.itemid";



$purchase="select sum(if(price is null,0,price) * if((open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) < 0 , 0 , (open_qty + prod_qty  - sale_qty - expire_qty - damage_qty))) as stockvalue
From (
    select itemid, 
    sum(open_qty) open_qty,
    sum( pur_qty)  pur_qty,
    sum(prod_qty) prod_qty,     
    sum(sale_qty) sale_qty,
    sum(expire_qty) expire_qty, 
    sum(damage_qty) damage_qty   
    From (
    SELECT itemid, sum(openstock) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM tbl_openingstock
    WHERE entrydate < '2023-04-01'
    and itemtype = 0
    Group by itemid
    union all
    SELECT indredientid, 0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, sum(quantity) pur_qty
    FROM purchase_details p 
    WHERE typeid = 1
    and purchasedate < '2023-04-01'
    group by indredientid
    union all
    SELECT ingredientid, 0 open_qty, sum(p.itemquantity * d.qty) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM production_details d 
    left join production p on d.receipe_code = p.receipe_code 
    WHERE created_date < '2023-04-01'
    group by ingredientid
    union ALL
    SELECT menu_id itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(menuqty) sale_qty ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM order_menu s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date < '2023-04-01'
    group by menu_id
    union all
    SELECT pid Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(expire_qty) expire_qty, sum(damage_qty) damage_qty, 0  pur_qty
    FROM tbl_expire_or_damagefoodentry 
    WHERE createdate < '2023-04-01'
    Group by pid

    union ALL

    SELECT itemid, sum(openstock) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM tbl_openingstock
    WHERE entrydate  between '2023-04-01' and '2023-04-29'
    and itemtype = 1
    Group by itemid
    Union ALL
    SELECT indredientid, 0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, sum(quantity) pur_qty
    FROM purchase_details p 
    WHERE typeid = 1
    and purchasedate between '2023-04-01' and '2023-04-29'
    group by indredientid
    union all
    SELECT ingredientid, 0 open_qty, sum(p.itemquantity * d.qty) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM production_details d 
    left join production p on d.receipe_code = p.receipe_code 
    WHERE created_date between '2023-04-01' and '2023-04-29'
    group by ingredientid
    union ALL
    SELECT menu_id itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(menuqty) sale_qty ,0 expire_qty, 0 damage_qty, 0  pur_qty
    FROM order_menu s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date between '2023-04-01' and '2023-04-29'
    group by menu_id
    union all
    SELECT pid Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(expire_qty) expire_qty, sum(damage_qty) damage_qty, 0  pur_qty
    FROM tbl_expire_or_damagefoodentry 
    WHERE createdate between '2023-04-01' and '2023-04-29'
    Group by pid
    ) t
    group by itemid
) l
left join ( 
    SELECT p.indredientid itemid, p.price
    FROM purchase_details p 
    left join (
        	 SELECT indredientid, max(purchasedate) purchasedate
             FROM purchase_details            
            where purchasedate between '2023-04-01' and '2023-05-29'
            group by indredientid,purchasedate
    	)  mp on p.indredientid = mp.indredientid and p.purchasedate = mp.purchasedate
    WHERE p.typeid = 1
    and p.purchasedate between '2023-04-01' and '2023-05-29'    
    group by p.indredientid
) p on l.itemid = p.itemid

";





