

SELECT t.indredientid,sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty) as Prev_openqty,  sum(pur_qty) pur_qty, sum(prod_qty) prod_qty, sum(rece_qty) rece_qty, sum(return_qty) return_qty, sum(damage_qty) damage_qty, sum(expire_qty) expire_qty,
sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty)+ sum(pur_qty) + sum(rece_qty) + sum(openqty) - sum(prod_qty) - sum(damage_qty) as stock
from (  
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,sum(`openstock`) as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate <'2023-08-19' 
    Group by itemid 
    union all    
    SELECT indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty,sum(`quantity`) as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <'2023-08-19' 
    Group by indredientid 
    union all
    SELECT ingredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, sum(itemquantity*d.qty) as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where created_date <'2023-08-19' 
    Group by ingredientid 
    union all
    SELECT productid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, 0 as prev_prod_qty, sum(`delivered_quantity`) as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND created_date <'2023-08-19' 
    Group by productid 
    union all
    SELECT pid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, sum(`damage_qty`) as prev_damage_qty, sum(`expire_qty`) as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage <'2023-08-19' 
    Group by pid  
    union all
    SELECT product_id,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty,sum(`qty`) as prev_return_qty
    FROM purchase_return_details  
    where return_date <'2023-08-19' 
    Group by product_id 
     Union All
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty,sum(`openstock`) as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by itemid 
    union all    
    SELECT indredientid,sum(`quantity`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by indredientid 
    union all
    SELECT ingredientid, 0  as pur_qty, sum(itemquantity*d.qty) as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code   
    where created_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by ingredientid 
    union all
    SELECT productid, 0  as pur_qty, 0 as prod_qty, sum(`delivered_quantity`) as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND created_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by productid 
    union all
    SELECT pid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, sum(`damage_qty`) as damage_qty,sum(`expire_qty`) as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty,  0 as prev_expire_qty,0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by pid  
    union all
    SELECT product_id,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,sum(`qty`) as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM purchase_return_details
    where return_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by product_id  
) t
group by t.indredientid;



//New 

SELECT ing.*,unt.uom_short_code FROM ingredients ing left join(
SELECT indredientid,Prev_openqty,pur_qty,prod_qty,rece_qty,return_qty,damage_qty,expire_qty,stock,pur_avg_rate,pur_rate,purfiforate
from (
SELECT t.indredientid,sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty) as Prev_openqty,  sum(pur_qty) pur_qty, sum(prod_qty) prod_qty, sum(rece_qty) rece_qty, sum(return_qty) return_qty, sum(damage_qty) damage_qty, sum(expire_qty) expire_qty,
sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty)+ sum(pur_qty) + sum(rece_qty) + sum(openqty) - sum(prod_qty) - sum(damage_qty) as stock , max(pur_avg_rate) pur_avg_rate, max(purliforate.pur_rate) pur_rate, max(purfiforate.pur_rate) purfiforate
from (  
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,sum(`openstock`) as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate <'2023-08-19' 
    Group by itemid 
    union all    
    SELECT indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty,sum(`quantity`) as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <'2023-08-19' 
    Group by indredientid 
    union all
    SELECT ingredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, sum(itemquantity*d.qty) as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where created_date <'2023-08-19' 
    Group by ingredientid 
    union all
    SELECT productid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, 0 as prev_prod_qty, sum(`delivered_quantity`) as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND created_date <'2023-08-19' 
    Group by productid 
    union all
    SELECT pid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, sum(`damage_qty`) as prev_damage_qty, sum(`expire_qty`) as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage <'2023-08-19' 
    Group by pid  
    union all
    SELECT product_id,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty,sum(`qty`) as prev_return_qty
    FROM purchase_return_details  
    where return_date <'2023-08-19' 
    Group by product_id 
     Union All
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty,sum(`openstock`) as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by itemid 
    union all    
    SELECT indredientid,sum(`quantity`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by indredientid 
    union all
    SELECT ingredientid, 0  as pur_qty, sum(itemquantity*d.qty) as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code   
    where created_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by ingredientid 
    union all
    SELECT productid, 0  as pur_qty, 0 as prod_qty, sum(`delivered_quantity`) as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND created_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by productid 
    union all
    SELECT pid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, sum(`damage_qty`) as damage_qty,sum(`expire_qty`) as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty,  0 as prev_expire_qty,0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by pid  
    union all
    SELECT product_id,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,sum(`qty`) as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM purchase_return_details
    where return_date BETWEEN '2023-08-19' AND '2023-08-22' 
    Group by product_id  
) t
    left join (
    select distinct indredientid,purchasedate, sum(purchaseamt) / sum(pur_qty) as pur_avg_rate
    from (
        SELECT indredientid,purchasedate, sum(price*quantity) as purchaseamt, sum(quantity) as pur_qty
        FROM `purchase_details`
        where typeid = 1 AND purchasedate <'2023-08-22'
        Group by indredientid ,purchasedate
        union all
        SELECT productid,created_date, sum(price*delivered_quantity) as purchaseamt, sum(delivered_quantity) as pur_qty
        FROM po_details_tbl
        where producttype = 1 AND created_date <'2023-08-22'
        Group by productid ,created_date
         union all
       SELECT itemid,entrydate, sum(unitprice*openstock) as purchaseamt, sum(openstock) as pur_qty
       FROM tbl_openingstock
       where itemtype = 1 AND entrydate <'2023-08-22' 
         Group by itemid ,entrydate
	) puravg  
    group by indredientid,purchasedate
    having sum(purchaseamt) / sum(pur_qty)>0
) pavg on t.indredientid = pavg.indredientid
left join (

select distinct  indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <'2023-08-22'    
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
    where producttype = 1 AND created_date <'2023-08-22' 
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND entrydate <'2023-08-22' 
) pur  
    where price>0 and purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT max(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where typeid = 1 AND purchasedate <'2023-08-22'
            Group by purchasedate
            union all
            SELECT max(created_date) purchasedatepurdate
            FROM po_details_tbl
            where producttype = 1 AND created_date <'2023-08-22'
            Group by created_date
            union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND entrydate <'2023-08-22'
            Group by entrydate
         ) md 
     )

) purliforate on t.indredientid = purliforate.indredientid

left join (

select distinct indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <'2023-08-22'    
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
    where producttype = 1 AND created_date <'2023-08-22'  
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND entrydate <'2023-08-22'          
    
) pur  
    where 
    price>0 and 
    purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT min(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where typeid = 1 AND purchasedate <'2023-08-22'
            Group by purchasedate
            union all
            SELECT min(created_date) purchasedatepurdate
            FROM po_details_tbl
            where producttype = 1 AND created_date <'2023-08-22'
            Group by created_date
             union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND entrydate <'2023-08-22'
            Group by entrydate
         ) md 
     ) 
 

) purfiforate on t.indredientid = purfiforate.indredientid
group by t.indredientid
) al 
    ) ing on id=indredientid
	left join unit_of_measurement unt on unt.id=ing.uom_id;