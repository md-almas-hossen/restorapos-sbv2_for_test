<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccReport extends CI_Model {


    public function ingredientreportrow($start_date, $end_date, $pid, $pty = null, $stock = null)
	{
		$myArray = array();
		$cond = "";

		if (empty($stock)) {
			$cond = "where ing.type=1";
		}
		if ($stock == 1) {
			$cond = "where stock>0 AND ing.type=1";
		}
		if ($stock == 2) {
			$cond = "where stock<1 OR stock IS NULL AND ing.type=1";
		}
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		if (empty($pid)) {
			$rowquery = "SELECT
					ing.*,
					unt.uom_short_code
					FROM
					ingredients ing
					LEFT JOIN(
						SELECT
							al.indredientid,
							al.Prev_openqty,
							al.pur_qty,
							al.prod_qty,
							al.rece_qty,
							al.return_qty,
							al.damage_qty,
							al.expire_qty,
							al.stock,
							al.pur_avg_rate,
							al.pur_rate,
							al.purfiforate
				
						FROM
							(
								SELECT
									t.indredientid,
									SUM(prev_pur_qty) + SUM(openqty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
									SUM(pur_qty) pur_qty,
									SUM(prod_qty) prod_qty,
				
									SUM(rece_qty) rece_qty,
				
									SUM(return_qty) return_qty,
				
									SUM(damage_qty) damage_qty,
				
									SUM(expire_qty) expire_qty,
				
									SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) 
									- SUM(prev_expire_qty) + SUM(pur_qty) + SUM(rece_qty) + SUM(openqty) - SUM(prod_qty) - SUM(damage_qty) - SUM(expire_qty) AS stock,
				
									MAX(pur_avg_rate) pur_avg_rate,
				
									MAX(purliforate.pur_rate) pur_rate,
									
									MAX(purfiforate.pur_rate) purfiforate
		
								FROM
									(
										SELECT
											indredientid,
											SUM(pur_qty) pur_qty,
											SUM(prod_qty) AS prod_qty,
											SUM(rece_qty) AS rece_qty,
											SUM(openqty) AS openqty,
											SUM(damage_qty) AS damage_qty,
											SUM(expire_qty) AS expire_qty,
											SUM(return_qty) AS return_qty,
											SUM(prev_pur_qty) AS prev_pur_qty,
											SUM(prev_prod_qty) AS prev_prod_qty,
											SUM(prev_rece_qty) AS prev_rece_qty,
											SUM(prev_openqty) AS prev_openqty,
											SUM(prev_damage_qty) AS prev_damage_qty,
											SUM(prev_expire_qty) AS prev_expire_qty,
											SUM(prev_return_qty) AS prev_return_qty
				
											
										FROM
											(
												SELECT
												indredientid,
												SUM(pur_qty) pur_qty,
												SUM(prod_qty) AS prod_qty,
												SUM(rece_qty) AS rece_qty,
												SUM(openqty) AS openqty,
												SUM(damage_qty) AS damage_qty,
												SUM(expire_qty) AS expire_qty,
												SUM(return_qty) AS return_qty,
												SUM(prev_pur_qty) AS prev_pur_qty,
												SUM(prev_prod_qty) AS prev_prod_qty,
												SUM(prev_rece_qty) AS prev_rece_qty,
												SUM(prev_openqty) AS prev_openqty,
												SUM(prev_damage_qty) AS prev_damage_qty,
												SUM(prev_expire_qty) AS prev_expire_qty,
												SUM(prev_return_qty) AS prev_return_qty
				
												FROM
												
													(
														SELECT
															itemid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															SUM(`openstock`) AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate < '$start_date'
														GROUP BY
															itemid
		
														UNION ALL
														SELECT
															indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															SUM(`quantity`) AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate < '$start_date'
														GROUP BY
															indredientid
														UNION ALL
														SELECT
															ingredientid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															SUM(itemquantity * d.qty) AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															production p
															LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
														WHERE
															p.saveddate < '$start_date'
														GROUP BY
															ingredientid
														UNION ALL
														SELECT
															productid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															SUM(`received_quantity`) AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) < '$start_date'
														GROUP BY
															productid
														UNION ALL
														SELECT
															pid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															SUM(`damage_qty`) AS prev_damage_qty,
															SUM(`expire_qty`) AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_expire_or_damagefoodentry
														WHERE
															dtype = 2
															AND expireordamage < '$start_date'
														GROUP BY
															pid
												)op 
				
												GROUP BY indredientid
		
		
		
												UNION ALL
		
		
		
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													SUM(`qty`) AS prev_return_qty
												FROM
													purchase_return_details
												WHERE
													return_date < '$start_date'
												GROUP BY
													product_id
												UNION ALL
												SELECT
													itemid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													SUM(`openstock`) AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_openingstock
												WHERE
													itemtype = 0
													AND entrydate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													itemid
												UNION ALL
		
												SELECT
													indredientid,
													SUM(`quantity`) AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													`purchase_details`
												WHERE
													typeid = 1
													AND purchasedate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													indredientid
												UNION ALL
												SELECT
													ingredientid indredientid,
													0 AS pur_qty,
													SUM(itemquantity * d.qty) AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													production p
													LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
												WHERE
													p.saveddate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													ingredientid
												UNION ALL
												SELECT
													productid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													SUM(`received_quantity`) AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													po_details_tbl
												WHERE
													producttype = 1
													AND DATE(created_date) BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													productid
												UNION ALL
												SELECT
													pid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													SUM(`damage_qty`) AS damage_qty,
													SUM(`expire_qty`) AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_expire_or_damagefoodentry
												WHERE
													dtype = 2
													AND expireordamage BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													pid
												UNION ALL
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													SUM(`qty`) AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													purchase_return_details
												WHERE
													return_date BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													product_id
											) osk
										GROUP BY
											indredientid 
									) t
		
									LEFT JOIN(
										
												SELECT
													DISTINCT indredientid,
													purchasedate,
													SUM(purchaseamt) / SUM(pur_qty) AS pur_avg_rate
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															SUM(price * quantity) AS purchaseamt,
															SUM(quantity) AS pur_qty
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														GROUP BY
															indredientid,
															purchasedate
														UNION ALL
														SELECT
															productid,
															created_date,
															SUM(price * received_quantity) AS purchaseamt,
															SUM(received_quantity) AS pur_qty
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														GROUP BY
															productid,
															created_date
														UNION ALL
														SELECT
															itemid,
															entrydate,
															SUM(unitprice * openstock) AS purchaseamt,
															SUM(openstock) AS pur_qty
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														GROUP BY
															itemid,
															entrydate
														
													) puravg
												
													GROUP BY  indredientid
									) pavg ON t.indredientid = pavg.indredientid
		
		
									LEFT JOIN(
		
		
		
		
		
										WITH CombinedData AS (
		
												SELECT
													indredientid,
													purchasedate,
													price,
													ROW_NUMBER() OVER (PARTITION BY indredientid ORDER BY purchasedate DESC) AS rnk
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															price
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														UNION
														ALL
														SELECT
															productid AS indredientid,
															created_date AS purchasedate,
															price
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														UNION
														ALL
														SELECT
															itemid,
															entrydate,
															unitprice
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														
													) pur
												WHERE
													price > 0
		
													AND purchasedate IN(
													SELECT
														DISTINCT purchasedatepurdate
													FROM
														(
															SELECT
																MAX(purchasedate) purchasedatepurdate
															FROM
																`purchase_details`
															WHERE
																typeid = 1
																AND purchasedate <= '$end_date'
															GROUP BY
																purchasedate
															UNION ALL
															SELECT
																MAX(created_date) purchasedatepurdate
															FROM
																po_details_tbl
															WHERE
																producttype = 1
																AND DATE(created_date) <= '$end_date'
															GROUP BY
																created_date
															UNION ALL
															SELECT
																MAX(entrydate) purchasedatepurdate
															FROM
																tbl_openingstock
															WHERE
																itemtype = 0
																AND entrydate <= '$end_date'
															GROUP BY
																entrydate
															
														) md
											)
										)
										SELECT
											indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											CombinedData
										WHERE
											rnk = 1
										ORDER BY
											purchasedate DESC
		
		
		
		
										
									) purliforate ON t.indredientid = purliforate.indredientid
		
		
									LEFT JOIN(
		
										WITH CombinedData AS (
												SELECT
													indredientid,
													purchasedate,
													price,
													ROW_NUMBER() OVER (PARTITION BY indredientid ORDER BY purchasedate) AS rnk
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															price
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														UNION
														ALL
														SELECT
															productid AS indredientid,
															created_date AS purchasedate,
															price
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														UNION ALL
														SELECT
															itemid,
															entrydate,
															unitprice
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														
													) pur
												WHERE
													price > 0
		
		
													AND purchasedate IN(
		
														SELECT
															DISTINCT purchasedatepurdate
														FROM
															(
																SELECT
																	MIN(purchasedate) purchasedatepurdate
																FROM
																	`purchase_details`
																WHERE
																	typeid = 1
																	AND purchasedate <= '$end_date'
																GROUP BY
																	purchasedate
																UNION ALL
																SELECT
																	MIN(created_date) purchasedatepurdate
																FROM
																	po_details_tbl
																WHERE
																	producttype = 1
																	AND DATE(created_date) <= '$end_date'
																GROUP BY
																	created_date
																UNION ALL
																SELECT
																	MIN(entrydate) purchasedatepurdate
																FROM
																	tbl_openingstock
																WHERE
																	itemtype = 0
																	AND entrydate <= '$end_date'
																GROUP BY
																	entrydate
																
															) md
													)
										)
		
										SELECT
											indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											CombinedData
										WHERE
											rnk = 1
											
											ORDER BY
											purchasedate ASC
		
		
		
		
		
									) purfiforate ON t.indredientid = purfiforate.indredientid
		
		
		
								GROUP BY
									t.indredientid
							) al
					) ing ON id = indredientid
					LEFT JOIN unit_of_measurement unt ON unt.id = ing.uom_id {$cond}";

			$rowquery = $this->db->query($rowquery);

			//echo $this->db->last_query();
			// exit;
		} else {
			 $rowquery = "SELECT
					ing.*,
					unt.uom_short_code
					FROM
					ingredients ing
					LEFT JOIN(
						SELECT
							al.indredientid,
							al.Prev_openqty,
							al.pur_qty,
							al.prod_qty,
							al.rece_qty,
							al.return_qty,
							al.damage_qty,
							al.expire_qty,
							al.stock,
							al.pur_avg_rate,
							al.pur_rate,
							al.purfiforate
				
						FROM
							(
								SELECT
									t.indredientid,
									SUM(prev_pur_qty) + SUM(openqty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
				
									SUM(pur_qty) pur_qty,
				
									SUM(prod_qty) prod_qty,
				
									SUM(rece_qty) rece_qty,
				
									SUM(return_qty) return_qty,
				
									SUM(damage_qty) damage_qty,
				
									SUM(expire_qty) expire_qty,
				
									SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) 
									- SUM(prev_expire_qty) + SUM(pur_qty) + SUM(rece_qty) + SUM(openqty) - SUM(prod_qty) - SUM(damage_qty) - SUM(expire_qty) AS stock,
				
									MAX(pur_avg_rate) pur_avg_rate,
				
									MAX(purliforate.pur_rate) pur_rate,
									
									MAX(purfiforate.pur_rate) purfiforate
		
				
								FROM
									(
										SELECT
											indredientid,
											SUM(pur_qty) pur_qty,
											SUM(prod_qty) AS prod_qty,
											SUM(rece_qty) AS rece_qty,
											SUM(openqty) AS openqty,
											SUM(damage_qty) AS damage_qty,
											SUM(expire_qty) AS expire_qty,
											SUM(return_qty) AS return_qty,
											SUM(prev_pur_qty) AS prev_pur_qty,
											SUM(prev_prod_qty) AS prev_prod_qty,
											SUM(prev_rece_qty) AS prev_rece_qty,
											SUM(prev_openqty) AS prev_openqty,
											SUM(prev_damage_qty) AS prev_damage_qty,
											SUM(prev_expire_qty) AS prev_expire_qty,
											SUM(prev_return_qty) AS prev_return_qty
				
				
										FROM
											(
												SELECT
												indredientid,
												SUM(pur_qty) pur_qty,
												SUM(prod_qty) AS prod_qty,
												SUM(rece_qty) AS rece_qty,
												SUM(openqty) AS openqty,
												SUM(damage_qty) AS damage_qty,
												SUM(expire_qty) AS expire_qty,
												SUM(return_qty) AS return_qty,
												SUM(prev_pur_qty) AS prev_pur_qty,
												SUM(prev_prod_qty) AS prev_prod_qty,
												SUM(prev_rece_qty) AS prev_rece_qty,
												SUM(prev_openqty) AS prev_openqty,
												SUM(prev_damage_qty) AS prev_damage_qty,
												SUM(prev_expire_qty) AS prev_expire_qty,
												SUM(prev_return_qty) AS prev_return_qty
				
												FROM
												
													(
														SELECT
															itemid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															SUM(`openstock`) AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_openingstock
		
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate < '$start_date'
		
														GROUP BY
															itemid
		
														UNION ALL
		
														SELECT
															indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															SUM(`quantity`) AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															`purchase_details`
		
														WHERE indredientid = '$pid'
														AND	typeid = 1
														AND purchasedate < '$start_date'
		
														GROUP BY
															indredientid
														UNION ALL
														SELECT
															ingredientid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															SUM(itemquantity * d.qty) AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															production p
															LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
														WHERE ingredientid = '$pid'
														AND p.saveddate < '$start_date'
														GROUP BY
															ingredientid
														UNION ALL
														SELECT
															productid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															SUM(`received_quantity`) AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															po_details_tbl
		
														WHERE productid = '$pid'
														AND	producttype = 1
														AND DATE(created_date) < '$start_date'
														GROUP BY
															productid
														UNION ALL
														SELECT
															pid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															SUM(`damage_qty`) AS prev_damage_qty,
															SUM(`expire_qty`) AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_expire_or_damagefoodentry
		
														WHERE pid = '$pid'
														AND dtype = 2
														AND expireordamage < '$start_date'
														
														GROUP BY
															pid
												)op 			
												GROUP BY indredientid	
												UNION ALL	
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													SUM(`qty`) AS prev_return_qty
												FROM
													purchase_return_details
		
												WHERE product_id = '$pid'
												AND	return_date BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													product_id
												UNION ALL
												SELECT
													itemid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													SUM(`openstock`) AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND	itemtype = 0
												AND entrydate BETWEEN '$start_date'
												AND '$end_date'
												
												GROUP BY
													itemid	
												UNION ALL											
												SELECT
													indredientid,
													SUM(`quantity`) AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													`purchase_details`
												WHERE typeid = 1
												AND indredientid = '$pid'
													AND purchasedate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													indredientid
												UNION ALL
												SELECT
													ingredientid indredientid,
													0 AS pur_qty,
													SUM(itemquantity * d.qty) AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													production p
													LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
												WHERE ingredientid = '$pid'
												AND	p.saveddate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													ingredientid
												UNION ALL
												SELECT
													productid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													SUM(`received_quantity`) AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													po_details_tbl
		
												WHERE productid = '$pid'
												AND	producttype = 1
												AND DATE(created_date) BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													productid
												UNION ALL
												SELECT
													pid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													SUM(`damage_qty`) AS damage_qty,
													SUM(`expire_qty`) AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_expire_or_damagefoodentry
		
												WHERE pid = '$pid'
												AND	dtype = 2
												AND expireordamage BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													pid
												UNION ALL
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													SUM(`qty`) AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													purchase_return_details
												WHERE product_id = '$pid'
												AND	return_date BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													product_id
											) osk
										GROUP BY
											indredientid 
									) t
		
		
		
									LEFT JOIN(
										
												SELECT
													DISTINCT indredientid,
													purchasedate,
													SUM(purchaseamt) / SUM(pur_qty) AS pur_avg_rate
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															SUM(price * quantity) AS purchaseamt,
															SUM(quantity) AS pur_qty
														FROM
															`purchase_details`
		
														WHERE indredientid = '$pid'
														AND	typeid = 1
														AND purchasedate <= '$end_date'
		
														GROUP BY
															indredientid,
															purchasedate
														UNION ALL
														SELECT
															productid,
															created_date,
															SUM(price * received_quantity) AS purchaseamt,
															SUM(received_quantity) AS pur_qty
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND	producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															productid,
															created_date
		
														UNION ALL
		
														SELECT
															itemid,
															entrydate,
															SUM(unitprice * openstock) AS purchaseamt,
															SUM(openstock) AS pur_qty
														FROM
															tbl_openingstock
														WHERE itemid = '$pid'
															AND itemtype = 0
															AND entrydate <= '$end_date'
														GROUP BY
															itemid,
															entrydate
														
														
													) puravg
												
													GROUP BY  indredientid
									) pavg ON t.indredientid = pavg.indredientid
		
		
									LEFT JOIN(
										SELECT
											DISTINCT indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											(
												SELECT
													indredientid,
													purchasedate,
													price
												FROM
													`purchase_details`
		
												WHERE indredientid = '$pid'
												AND	typeid = 1
												AND purchasedate <= '$end_date'
												
												UNION ALL
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
		
												WHERE productid = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '$end_date'
		
												UNION ALL
		
												SELECT
													itemid AS indredientid,
													entrydate AS purchasedate,
													unitprice AS price
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND itemtype = 0
												AND entrydate <= '$end_date'
		
												
		
												
											) pur
										WHERE
											price > 0
											AND purchasedate IN(
												SELECT
													DISTINCT purchasedatepurdate
												FROM
													(
														SELECT
															MAX(purchasedate) purchasedatepurdate
														FROM
															`purchase_details`
														
														WHERE indredientid = '$pid'
														AND typeid = 1
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MAX(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														UNION ALL
		
														SELECT
															MAX(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
		
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$end_date'
		
														GROUP BY
															entrydate
														
													) md
											)
											ORDER BY purchasedate DESC
											LIMIT 1
									) purliforate ON t.indredientid = purliforate.indredientid
		
		
									LEFT JOIN(
										SELECT
											DISTINCT indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											(
												SELECT
													indredientid,
													purchasedate,
													price
												FROM
													`purchase_details`
												
												WHERE indredientid = '$pid'
												AND typeid = 1
												AND purchasedate <= '$end_date'
												
												UNION ALL
												
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
												
												WHERE productid = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '$end_date'
												
												UNION ALL
												
												SELECT
													itemid,
													entrydate,
													unitprice
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND	itemtype = 0
												AND entrydate <= '$end_date'
		
											) pur
		
										WHERE
											price > 0
											AND purchasedate IN(
												SELECT
													DISTINCT purchasedatepurdate
												FROM
													(
														SELECT
															MIN(purchasedate) purchasedatepurdate
														FROM
															`purchase_details`
														
														WHERE indredientid = '$pid'
														AND typeid = 1
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MIN(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														
														UNION ALL
														
														SELECT
															MIN(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
														
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$end_date'
														
														GROUP BY
															entrydate
		
														
													) md
											)
											ORDER BY purchasedate ASC
											LIMIT 1
									) purfiforate ON t.indredientid = purfiforate.indredientid
		
		
		
		
								GROUP BY
									t.indredientid
							) al
					) ing ON id = indredientid
					LEFT JOIN unit_of_measurement unt ON unt.id = ing.uom_id
					WHERE ing.id='$pid' AND ing.type=1";
			$rowquery = $this->db->query($rowquery);
			//echo $this->db->last_query();
		}

		$producreport = $rowquery->result();

		$i = 0;

		if ($this->db->table_exists('assign_inventory')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->get()
				->result();

			foreach ($kitchen_data as $item1) {
				foreach ($producreport as $key => $item2) {
					if ($item1->product_id == $item2->indredientid) {
						$producreport[$key]->assigned_product = $item1->assigned_product;
					}
				}
			}
		}

		if ($this->db->table_exists('kitchen_stock_new')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->get()
				->result();

			foreach ($kitchen_data as $item1) {
				foreach ($producreport as $key => $item2) {
					if ($item1->product_id == $item2->indredientid) {
						$producreport[$key]->assigned_product = $item1->assigned_product;
					}
				}
			}
		}

		foreach ($producreport as $result) {

			$i++;
			$addinfo = $this->db->select("SUM(adjustquantity) as totaladd")->from('adjustment_details')->where('indredientid', $result->id)->where('adjust_type', 'added')->get()->row();
			$reduceinfo = $this->db->select("SUM(adjustquantity) as totalreduc")->from('adjustment_details')->where('indredientid', $result->id)->where('adjust_type', 'reduce')->get()->row();
			
			$reddemconsump = "product_id='$result->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=1";
			$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totaldamage,SUM(expired_qty) as totalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsump);
			$queryreddem = $this->db->get();
			$redeeminfo = $queryreddem->row();

			//echo $this->db->last_query();

			if (!empty($redeeminfo)) {
				$totalredused = $redeeminfo->totalused;
				$totalredamage = $redeeminfo->totaldamage;
				$totalredexpire = $redeeminfo->totalexp;
			}

			$totalredused = (!empty($totalredused) ? $totalredused : 0);
			$totalredamage = (!empty($totalredamage) ? $totalredamage : 0);
			$totalredexpire = (!empty($totalredexpire) ? $totalredexpire : 0);

			$reddemconsumprev = "product_id='$result->id' AND date < '$start_date' AND product_type=1";
			$this->db->select("SUM(used_qty) as prevtotalused,SUM(wastage_qty) as prevtotaldamage,SUM(expired_qty) as prevtotalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsumprev);
			$queryreddemprev = $this->db->get();
			$redeeminfoprev = $queryreddemprev->row();

			if (!empty($redeeminfoprev)) {
				$prevtotalredused = $redeeminfoprev->prevtotalused;
				$prevtotalredamage = $redeeminfoprev->prevtotaldamage;
				$prevtotalredexpire = $redeeminfoprev->prevtotalexp;
			}

			$prevtotalredused = (!empty($prevtotalredused) ? $prevtotalredused : 0);
			$prevtotalredamage = (!empty($prevtotalredamage) ? $prevtotalredamage : 0);
			$prevtotalredexpire = (!empty($prevtotalredexpire) ? $prevtotalredexpire : 0);

			if (empty($addinfo) && empty($reduceinfo)) {
				$adjuststock = 0;
			} else {
				$adjuststock = $addinfo->totaladd - $reduceinfo->totalreduc;
			}

			if ($settinginfo->stockvaluationmethod == 1) {
				$price = $result->purfiforate;
			}
			if ($settinginfo->stockvaluationmethod == 2) {
				$price = $result->pur_rate;
			}
			if ($settinginfo->stockvaluationmethod == 3) {
				$price = $result->pur_avg_rate;
			}


			$finalopenstock=$result->Prev_openqty-($prevtotalredused+$prevtotalredamage+$prevtotalredexpire);
      		$finalstock = $result->stock - ($totalredused+$totalredexpire+$totalredamage+$prevtotalredused+$prevtotalredamage+$prevtotalredexpire) + ($adjuststock);
			$myArray[$i]['type'] = $result->type;
			$myArray[$i]['IngID'] = $result->id;
			$myArray[$i]['ProductName'] = $result->ingredient_name;
			$myArray[$i]['price'] = $price;

			$myArray[$i]['openqty'] = $finalopenstock . ' ' . $result->uom_short_code;
			$myArray[$i]['In_Qnty'] = $result->pur_qty + $result->rece_qty . ' ' . $result->uom_short_code;

			$myArray[$i]['return_Qnty'] = $result->return_qty . ' ' . $result->uom_short_code;
			$myArray[$i]['Out_Qnty'] = $result->prod_qty + $totalredused . ' ' . $result->uom_short_code;
			$myArray[$i]['expireqty'] = $result->expire_qty + $totalredexpire . ' ' . $result->uom_short_code;
			$myArray[$i]['damageqty'] = $result->damage_qty + $totalredamage . ' ' . $result->uom_short_code;
			$myArray[$i]['adjusted'] = $adjuststock . ' ' . $result->uom_short_code;

			if ($this->db->table_exists('kitchen_stock_new')) {

				$myArray[$i]['closingqty'] = $finalstock . ' ' . $result->uom_short_code;
				$myArray[$i]['stockvaluation'] = $price * ($finalstock);
			} else {

				$myArray[$i]['closingqty'] = $finalstock  . ' ' . $result->uom_short_code;
				$myArray[$i]['stockvaluation'] = $price * $finalstock;
			}
		}

		return $myArray;
	}





    public function openproductready($end_date, $pid = null)
	{
		// purchase
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		$dateRange = "indredientid='$pid' AND purchasedate < '$end_date'";
		$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
		$this->db->from('purchase_details');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('indredientid');
		$this->db->order_by('purchasedate', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();

		// purchase return
		$dateRangereturn = "product_id='$pid' AND return_date < '$end_date'";
		$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
		$this->db->from('purchase_return_details');
		$this->db->where($dateRangereturn, NULL, FALSE);
		$this->db->group_by('product_id');
		$this->db->order_by('return_date', 'desc');
		$query = $this->db->get();
		$producreportreturn = $query->row();
		// sell
		$salcon = "a.menu_id='$pid' AND b.order_date < '$end_date' AND b.order_status=4";
		$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
		$this->db->from('order_menu a');
		$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
		$this->db->where($salcon);
		$this->db->order_by('b.order_date', 'desc');
		$query = $this->db->get();
		$salereport = $query->row();


		if (empty($salereport->totalsaleqty)) {
			$outqty = 0;
		} else {
			$outqty = $salereport->totalsaleqty;
		}

		// opening stock
		$opencond = "itemid=$pid AND entrydate <'$end_date'";
		$openstock = $this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();




		// damage or expire
		$totalexpire = 0;
		$totaldamage = 0;

		$expcond = "pid='$pid' AND vid='$vid' AND expireordamage < '$end_date' AND dtype=1";
		$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
		$this->db->from('tbl_expire_or_damagefoodentry');
		$this->db->where($expcond);
		$queryexdam = $this->db->get();
		$damgeexpinfo = $queryexdam->row();


		// damage or expire
		$prevtotalused = 0;
		$prevtotaldamage = 0;
		$prevtotalexpire = 0;

		$reddemconsump = "product_id='$pid' AND date < '$end_date' AND product_type=2";
		$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totalreddamage,SUM(expired_qty) as totalredexp");
		$this->db->from('tbl_reedem_details');
		$this->db->where($reddemconsump);
		$queryreddem = $this->db->get();
		$redeeminfo = $queryreddem->row();


		// adjustment (plus)
		$adjust_plus = $this->db->select('sum(adjustquantity) as totalqty')
			->from('adjustment_details')
			->where('adjust_type', 'added')
			->where('indredientid', $pid)
			->where('adjusteddate <', $end_date)
			->group_by('indredientid')
			->order_by('adjusteddate', 'desc')
			->get()
			->row();


		// adjustment (minus)
		$adjust_minus = $this->db->select('sum(adjustquantity) as totalqty')
			->from('adjustment_details')->where('adjust_type', 'reduce')
			->where('indredientid', $pid)
			->where('adjusteddate <', $end_date)
			->group_by('indredientid')
			->order_by('adjusteddate', 'desc')
			->get()
			->row();

		// kitchen assign
		$kitchen_assign = $this->db->select('sum(stock) as totalqty')
			->from('kitchen_stock_new')
			->where('type', 0)
			->where('product_id', $pid)
			->where('date <', $end_date)
			->group_by('product_id')
			->order_by('date', 'desc')
			->get()
			->row();
			$kitchen_assign->totalqty=0;

		// po details
		$po_details = $this->db->select('sum(received_quantity) as stock_in')
			->from('po_details_tbl')
			->where('productid', $pid)
			->where('created_date <', $end_date . '%')
			->group_by('productid')
			->order_by('created_date', 'desc')
			->get()
			->row();


		if (!empty($damgeexpinfo)) {
			$totalexpire = $damgeexpinfo->totalexpire;
			$totaldamage = $damgeexpinfo->totaldamage;
		}

		if (!empty($redeeminfo)) {
			 $prevtotalused = $redeeminfo->totalused;
			$prevtotaldamage = $redeeminfo->totalreddamage;
			$prevtotalexpire = $redeeminfo->totalredexp;
		}
		
		return $openqty = ($producreport->totalqty + $openstock->openstock + $adjust_plus->totalqty + $po_details->stock_in) - ($outqty + $totalexpire + $totaldamage + $prevtotalused + $prevtotaldamage + $prevtotalexpire + $producreportreturn->totalretqty + $adjust_minus->totalqty + $kitchen_assign->totalqty);
		return $openqty . '_' . $getprice->costprice;
	}

    public function productreportitem($start_date, $end_date, $pid = null,$stockcheck= null)
	{
      

		$myarray = array();
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
	
		$firstcond = "item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";
		

		$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName");
		$this->db->from('ingredients');
		$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$alliteminfo = $query->result();

   
		
		$endopendate = $start_date;
		$i = 0;

        

		foreach ($alliteminfo as $result) {
            
			$totalopenqty = $this->openproductready($endopendate, $result->id);

			// purchase
			$dateRange = "indredientid='$result->id' AND purchasedate BETWEEN '$start_date' AND '$end_date' AND typeid=2";
			$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
			$this->db->from('purchase_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('indredientid');
			$this->db->order_by('purchasedate', 'desc');
			$query = $this->db->get();
			$producreport = $query->row();


			// purchase return 
			$dateRange = "product_id='$result->id' AND return_date BETWEEN '$start_date' AND '$end_date'";
			$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
			$this->db->from('purchase_return_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('product_id');
			$this->db->order_by('return_date', 'desc');
			$query = $this->db->get();
			$productretreport = $query->row();

			// po details
			$poDateRange = "productid='$result->id' AND created_date BETWEEN '$start_date%' AND '$end_date%'";
			$po_details = $this->db->select('sum(received_quantity) as stock_in')
				->from('po_details_tbl')
				->where('productid', $pid)
				->where($poDateRange, NULL, FALSE)
				->group_by('productid')
				->order_by('created_date', 'desc')
				->get()
				->row();

			$condbydate = "purchasedate Between '$start_date' AND '$end_date'";
			if ($settinginfo->stockvaluationmethod == 1) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Asc')->get()->row();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'ASC')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'ASC')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'ASC')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}
			if ($settinginfo->stockvaluationmethod == 2) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
				//echo $this->db->last_query();
			}
			if ($settinginfo->stockvaluationmethod == 3) {
				$getprice2 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}

			$totalexpire = 0;
			$totaldamage = 0;


			// sell
			$salcon = "a.menu_id='$result->ProductsID' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($salcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();
            
			$prevsalcon = "a.menu_id='$result->ProductsID' AND b.order_date < '$start_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($prevsalcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$prevsalereport = $query->row();



			// opening stock
			$opencond = "itemid=$result->id AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
			$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
			
			// damage or expire
			$expcond = "pid='$result->id' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo = $queryexdam->row();

            //redeem consumption

			$reddemconsump = "product_id='$result->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=2";
			$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totaldamage,SUM(expired_qty) as totalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsump);
			$queryreddem = $this->db->get();
			$redeeminfo = $queryreddem->row();
			//echo $this->db->last_query();



			// new

			// adjustment (plus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'added');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql=$this->db->get();
			//echo $this->db->last_query();
			$adjust_plus =$getsql->row();
			


			// adjustment (minus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'reduce');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql2=$this->db->get();
			$adjust_minus =$getsql2->row();
			//print_r($adjust_minus);
		

			// kitchen assign
			$kitchen_assign = $this->db->select('sum(stock) as totalqty')
				->from('kitchen_stock_new')
				->where('type', 0)
				->where('product_id', $result->id)
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->group_by('product_id')
				->order_by('date', 'desc')
				->get()
				->row();
				$kitchen_assign->totalqty=0;
			// new


            

			//echo $this->db->last_query();
			if (!empty($damgeexpinfo)) {
				$totalexpire = $damgeexpinfo->totalexpire;
				$totaldamage = $damgeexpinfo->totaldamage;
			}
			if (!empty($redeeminfo)) {
				$totalredused = $redeeminfo->totalused;
				$totalredamage = $redeeminfo->totaldamage;
				$totalredexpire = $redeeminfo->totalexp;
			}
			//echo $this->db->last_query();
			if (empty($salereport->totalsaleqty)) {
				$outqty = 0;
			} else {
				$outqty = $salereport->totalsaleqty;
			}

			if (empty($prevsalereport->totalsaleqty)) {
				$prevoutqty = 0;
			} else {
				$prevoutqty = $prevsalereport->totalsaleqty;
			}

			$totalin = (!empty($producreport->totalqty) ? $producreport->totalqty : 0);
			$totalreturn = (!empty($productretreport->totalretqty) ? $productretreport->totalretqty : 0);

			$totalexpire = (!empty($totalexpire) ? $totalexpire : 0);
			$totaldamage = (!empty($totaldamage) ? $totaldamage : 0);
			
			 $totalredused = (!empty($totalredused) ? $totalredused : 0);
			 $totalredamage = (!empty($totalredamage) ? $totalredamage : 0);
			 $totalredexpire = (!empty($totalredexpire) ? $totalredexpire : 0);
            
			if (empty($adjust_plus->totalqty)) {
				$adjustplus = 0;
			} else {
				$adjustplus = $adjust_plus->totalqty;
			}

			if (empty($adjust_minus->totalqty)) {
				$adjustnumus = 0;
			} else {
				$adjustnumus = $adjust_minus->totalqty;
			}


            $adjustedstock=$adjustplus - $adjustnumus;
			$closingqty = ($totalopenqty + $totalin + $openstock->openstock + $adjustplus + $po_details->stock_in) - ($outqty + $totalexpire + $totalredused + $totalredexpire + $totalredamage + $totaldamage + $totalreturn + $adjustnumus + $kitchen_assign->totalqty+$prevoutqty);
			
			if($stockcheck==1 && $closingqty>0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if($stockcheck==2 && $closingqty==0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if(empty($stockcheck)){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
			$myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			


			
		}
		return $myarray;
	}


    public  function get_general_ledger(){

        $this->db->select('*');
        $this->db->from('acc_coas');
        $this->db->where('head_level',4);
        $this->db->where('is_active',1);
        $this->db->order_by('account_name', 'asc');
        $query = $this->db->get();
        return $query->result();


    }
    public function general_led_report_headname($cmbCode){
        $this->db->select('*');
        $this->db->from('acc_coas');
        $this->db->where('id',$cmbCode);
        $query = $this->db->get();
        return $query->row();
    }
    public function getPredefined($predefined_name){
        $this->db->select('ps.acc_coa_id as CPLcode');
        $this->db->from('acc_predefined p');
        $this->db->join('acc_predefined_seeting ps', 'ps.predefined_id = p.id', 'left');
        $this->db->where('p.predefined_name',$predefined_name);
        $query = $this->db->get();
        return $query->row();
    }
    function getFinancialYears()
    {
        $this->db->select('*');
        $this->db->from('acc_financialyear')->where('is_active', 1)->or_where('is_active', 2);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    public function getSubType(){
		$this->db->select('a.*');
		 $this->db->from('acc_subtype a'); //acc_subcode
		 $this->db->order_by('a.id', 'desc');
         $this->db->where_not_in('a.id',[1]);
		 $query = $this->db->get();
		 if ($query->num_rows() > 0) {
			 return $query->result();    
		 }
		 return false;
	 }

     public function getCoaFromSubtype($subtype) {
        // Fetch the acc_coas data based on the given conditions
        $this->db->select('*');
        $this->db->from('acc_coas');
        $this->db->where('head_level', 4);
        $this->db->where('is_active', 1);
        $this->db->where('subtype_id', $subtype);
        $this->db->where('is_subtype', 1);
        $query = $this->db->get();
        return $query->result(); // Return result as an array of objects
    }

    public function getsubcode($subtypeid) {
        // Fetch the acc_subcode data based on the given conditions
        $this->db->select('*');
        $this->db->from('acc_subcode');
        $this->db->where('subTypeID', $subtypeid);
        $query = $this->db->get();
        return $query->result(); // Return result as an array of objects
    }
}
?>