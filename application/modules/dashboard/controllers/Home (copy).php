<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MX_Controller
{


	private $head;
	private $instance;

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array(
			'home_model'
		));
		$this->db->query('SET SESSION sql_mode = ""');
		if (!$this->session->userdata('isLogIn'))
			redirect('login');
	}
	public function changeformat($num)
	{
		if ($num > 1000) {
			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('k', 'm', 'b', 't');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];
			return $x_display;
		}
		return $num;
	}
	public function index()
	{

        $data['dashboard_quick_menu'] = [
            "point_of_sale" => [
				'menu_url' => base_url('ordermanage/order/pos_invoice'),
                'img'      => base_url('assets/img/new_dashboard/pos.webp'),
                'title'    => display('point_of_sale') 
            ],
            "kitchen_dislpay" => [
				'menu_url' => base_url('ordermanage/order/allkitchen'),
                'img'      => base_url('assets/img/new_dashboard/kds.webp'),
                'title'    => display('kitchen_dislpay') 
            ],
            "waitingdisplay" => [
				'menu_url' => base_url('ordermanage/order/counterboard'),
                'img'      => base_url('assets/img/new_dashboard/waiting-display.webp'),
                'title'    => display('waitingdisplay') 
            ],
            "purchase" => [
				'menu_url' => base_url('purchase/purchase/index'),
                'img'      => base_url('assets/img/new_dashboard/purchases.webp'),
                'title'    => display('purchase')
            ],
            "food_list" => [
				'menu_url' => base_url('itemmanage/foodlist/'),
                'img'      => base_url('assets/img/new_dashboard/food-list.webp'),
                'title'    => display('food_list')
            ],
            "reservation" => [
				'menu_url' => base_url('reservation/reservation/reservation_dashboard'),
                'img'      => base_url('assets/img/new_dashboard/reservation.webp'),
                'title'    => display('reservation')
            ],
            "report_dashboard" => [
				'menu_url' => base_url('dashboard/home/report_dashboard'),
                'img'      => base_url('assets/img/new_dashboard/report-dashboard.webp'),
                'title'    => display('report_dashboard')
            ],
            "sell_report" => [
				'menu_url' => base_url('report/reports/sellrpt'),
                'img'      => base_url('assets/img/new_dashboard/sales-report.webp'),
                'title'    => display('sell_report')
            ],
            "purchases_report" => [
				'menu_url' => base_url('report/reports/index'),
                'img'      => base_url('assets/img/new_dashboard/Purchases-Report.webp'),
                'title'    => display('purchases_report')
            ],
            "stock_report" => [
				'menu_url' => base_url('report/reports/ingredientwise'),
                'img'      => base_url('assets/img/new_dashboard/Stock-Report.webp'),
                'title'    => display('stock_report')
            ],
            "accounts" => [
				'menu_url' => base_url('accounts/AccReportController/financial_report'),
                'img'      => base_url('assets/img/new_dashboard/Accounts.webp'),
                'title'    => display('accounts')
            ],
            "human_resources" => [
				'menu_url' => base_url('hrm/Employees/manageemployee'),
                'img'      => base_url('assets/img/new_dashboard/Human-Resources.webp'),
                'title'    => display('human_resources')
            ],
            "productions" => [
				'menu_url' => base_url('production/production/index'),
                'img'      => base_url('assets/img/new_dashboard/Productions.webp'),
                'title'    => display('productions')
            ],
            "role_permission" => [
				'menu_url' => base_url('dashboard/role/role_list'),
                'img'      => base_url('assets/img/new_dashboard/Role-Permission.webp'),
                'title'    => display('role_permission')
            ],
            "settings" => [
				'menu_url' => base_url('setting/setting/index'),
                'img'      => base_url('assets/img/new_dashboard/Settings.webp'),
                'title'    => display('settings')
            ],

        ]; 
   

		if ($this->permission->method('dashboard', 'read')->access() == FALSE) {
			if (isset($_GET['status'])) {
				redirect("dashboard/home/profile?status=done");
			} else {
				redirect("dashboard/home/profile");
			}
		}




		$data['title']    = display('home');
		#page path 
		$data['module'] = "dashboard";
		$data['page']   = "home/dashboard2";		
		$ctoday = date('Y-m-d');
		$ptoday = date('Y-m-d', strtotime('-1 days'));
		
		$data['settinginfo'] = $settinginfo = $this->db->select('*')->from('setting')->get()->row();
		$currencyinfo = $this->db->select('currencyname,curr_icon')->from('currency')->where('currencyid', $settinginfo->currency)->get()->row();
		$data["currencyicon"] = $currencyinfo->curr_icon;
		// $ordernum = $this->home_model->countorder();
		// $data["totalorder"]  = $this->changeformat($ordernum);
		// $todayorder = $this->home_model->todayorder();
		// $data["todayorder"]  = $this->changeformat($todayorder);
		// $todayorderprice = $this->home_model->todayamount($ctoday);
		// $todayreturnprice = $this->home_model->todayreturnamount($ctoday);
		// $allorderprice = $this->home_model->todayamount($allday = null);
		// $preorderprice = $this->home_model->todayamount($ptoday);
		// $data["allamount"]  = round($allorderprice->amount);
		// $data["previousdayamount"]  = round($preorderprice->amount);
		// if ($todayorderprice->amount < 1000) {
		// 	if ($todayorderprice->amount > 0) {
		// 		$data["todayamount"]  = round($todayorderprice->amount);
		// 	} else {
		// 		$data["todayamount"]  = "0";
		// 	}
		// } else {
		// 	//$data["todayamount"]  =  $this->changeformat($todayorderprice->amount);
		// 	$data["todayamount"]  = round($todayorderprice->amount);
		// }
		// $data["returnamnt"]  = round($todayreturnprice->amount);
		// $customer = $this->home_model->totalcustomer();
		// $data["totalcustomer"]  = $this->changeformat($customer);
		// $ordersumtypewise = $this->home_model->ordersum();

		// $data["customertypewisesales"] = $ordersumtypewise;

		// $piestart = date('Y-m-01');
		// $pieend = date('Y-m-d');
		// $ordersumbydynein = $this->home_model->ordersumbytype(1, $piestart, $pieend);
		// $ordersumbyonline = $this->home_model->ordersumbytype(2, $piestart, $pieend);
		// $ordersumbythirdparty = $this->home_model->ordersumbytype(3, $piestart, $pieend);
		// $ordersumbytakeway = $this->home_model->ordersumbytype(4, $piestart, $pieend);
		// $ordersumbyqr = $this->home_model->ordersumbytype(99, $piestart, $pieend);

		// $piedata = $ordersumbydynein . ',' . $ordersumbyonline . ',' . $ordersumbytakeway . ',' . $ordersumbythirdparty . ',' . $ordersumbyqr;
		// $data["piedatasales"] = $piedata;
		$months = '';
		$day = '';
		$lastday = date("t");
		// $weekdaystart = date('d', strtotime('-7 days'));
		// $todaydate = date('d');
		// $weekday = '';
		// $weekdaysales = '';
		$salesamountdaily = '';
		$purchasedaily = '';
		$salesamount = '';
		// $salesamountonline = '';
		// $totalorderonline = '';
		// $salesamountoffline = '';
		// $totalorderoffline = '';
		$totalorder = '';
		// $allincome = '';
		// $allexpense = '';
		$year = date('Y');
		$numbery = date('y');
		$prevyear = $numbery - 1;
		$prevyearformat = $year - 1;
		// $syear = '';
		// $syearformat = '';
		for ($k = 1; $k < 13; $k++) {
			$month = date('m', strtotime("+$k month"));
			$gety = date('y', strtotime("+$k month"));
			if ($gety == $numbery) {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			} else {
				$syear = $numbery;
				$syearformat = $year;
			}
			$monthly = $this->home_model->monthlysaleamount($syearformat, $month);
			$odernum = $this->home_model->monthlysaleorder($syearformat, $month);

			$salesamount .= $monthly . ', ';
			$totalorder .= $odernum . ', ';

			// $salesamountonline .= $monthlyonline . ', ';
			// $totalorderonline .= $odernumonline . ', ';
			// $salesamountoffline .= $monthlyoffline . ', ';
			// $totalorderoffline .= $odernumoffline . ', ';

			$months .=  date('F-' . $syear, strtotime("+$k month")) . ',';
		}
		for ($d = 1; $d <= $lastday; $d++) {
			$getday = date('Y-m-' . $d);
			$day .= $d . '-' . date('M') . ",";
			$perdaysales = $this->home_model->dailysales($getday);
			$salesamountdaily .= number_format($perdaysales, 2, '.', '') . ', ';

			$perdaypurchase = $this->home_model->purchasedata($getday);
			$purchasedaily .= number_format($perdaypurchase, 2, '.', '') . ', ';

			// ACCM
			// $allinc = $this->home_model->get_head_summeryprofitloss(3, 'Income', $getday, $getday, 0);
			// $allincome .= round($allinc[0]['gtotal']) . ', ';
			// $expn = $this->home_model->get_head_summeryprofitloss(4, 'Expense', $getday, $getday, 0);
			// $allexpense .= round($expn[0]['gtotal']) . ', ';
		}
		$dailysales = trim($salesamountdaily, ',');
		$dailypurchase = trim($purchasedaily, ',');
		$alldays = trim($day, ',');
		$data["dailysaleamount"] = trim($dailysales, ',');
		$data["dailypurchaseamount"] = trim($dailypurchase, ',');
		$data["alldays"] = trim($alldays, ',');

		// $data["monthlysaleamount"] = trim($salesamount, ',');
		// $data["monthlysaleorder"] = trim($totalorder, ',');
		// $lastmonthsdate = date('Y-m-d', strtotime('first day of last month'));
		// $lastmonthedate = date('Y-m-d', strtotime('last day of last month'));
		// $weeklydate = date('Y-m-d', strtotime('-7 days'));
		// $prevweeklydate = date('Y-m-d', strtotime('-15 days'));
		/* ACCM
		$data['todayexpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-d'), date('Y-m-d'), 0);
		$data['predayexpenses'] = $this->home_model->get_head_summery(4, 'Expense', $ptoday, $ptoday, 0);
		$data['weeklyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $weeklydate, date('Y-m-d'), 0);
		$data['prevweeklyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $prevweeklydate, $weeklydate, 0);
		$data['monthlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-01'), date('Y-m-d'), 0);
		$data['prevmonthlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $lastmonthsdate, $lastmonthedate, 0);
		$data['yearlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-01-01'), date('Y-m-d'), 0);
		$data['prevyearlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')), 0);
        */
		// $data['comtodayordnum'] = $this->home_model->countordercomparision(date('Y-m-d'), date('Y-m-d'));
		// $data['comprevordnum'] = $this->home_model->countordercomparision($ptoday, $ptoday);

		// $data['comweekordnum'] = $this->home_model->countordercomparision($weeklydate, date('Y-m-d'));
		// $data['comweekordval'] = $this->home_model->saleamountcomparision($weeklydate, date('Y-m-d'));
		// $data['comprevweekordnum'] = $this->home_model->countordercomparision($prevweeklydate, $weeklydate);
		// $data['comprevweekordval'] = $this->home_model->saleamountcomparision($prevweeklydate, $weeklydate);

		// $data['commonthordnum'] = $this->home_model->countordercomparision(date('Y-m-01'), date('Y-m-d'));
		// $data['commonthordval'] = $this->home_model->saleamountcomparision(date('Y-m-01'), date('Y-m-d'));
		// $data['comprevmonthordnum'] = $this->home_model->countordercomparision($lastmonthsdate, $lastmonthedate);
		// $data['comprevmonthordval'] = $this->home_model->saleamountcomparision($lastmonthsdate, $lastmonthedate);

		// $data['comyearordnum'] = $this->home_model->countordercomparision(date('Y-01-01'), date('Y-m-d'));
		// $data['comyearordval'] = $this->home_model->saleamountcomparision(date('Y-01-01'), date('Y-m-d'));
		// $data['comprevyearordnum'] = $this->home_model->countordercomparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));
		// $data['comprevyearordval'] = $this->home_model->saleamountcomparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));

		// $data['todaypurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-d'), date('Y-m-d'));
		// $data['prevpurchase'] = $this->home_model->purchasedatacoparision($ptoday, $ptoday);
		// $data['weekpurchase'] = $this->home_model->purchasedatacoparision($weeklydate, date('Y-m-d'));
		// $data['prevweekpurchase'] = $this->home_model->purchasedatacoparision($prevweeklydate, $weeklydate);
		// $data['monthpurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-01'), date('Y-m-d'));
		// $data['prevmonthpurchase'] = $this->home_model->purchasedatacoparision($lastmonthsdate, $lastmonthedate);
		// $data['yearpurchase'] = $this->home_model->purchasedatacoparision(date('Y-01-01'), date('Y-m-d'));
		// $data['prevyearpurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));

		// $gettime = "SELECT MIN(order_time) as mintime,MAX(order_time)as maxtime FROM customer_order WHERE order_date='" . date('Y-m-d') . "'";
		// $timesql = $this->db->query($gettime);
		// $minmax = $timesql->row();
		//echo $this->db->last_query();

		// $minvalue = explode(':', $minmax->mintime);
		// $maxvalue = explode(':', $minmax->maxtime);

		// $mintime = (int)$minvalue[0];
		// $maxtime = (int)$maxvalue[0] + 1;
		// $minmaxinterval = (int)$maxtime - (int)$mintime;
		// $totalhousperday = $minmaxinterval * 3600;

		// $timeordernum = '';
		// $timeorderval = '';
		// $timeslot = '';
		// $time = mktime($mintime, 0, 0, 0, 0);
		// $k = 0;
		// for ($i = 0; $i < $totalhousperday; $i += 3600) {  // 1800 = half hour, 86400 = one day
		// 	$timestart = sprintf('%1$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3600));
		// 	$timeend = sprintf('%2$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3599));
		// 	$counthourlyorder = $this->home_model->hourlyordernum($timestart, $timeend, date('Y-m-d'));
		// 	$counthourlyorderval = $this->home_model->hourlyordeval($timestart, $timeend, date('Y-m-d'));
		// 	$timeordernum .= $counthourlyorder . ',';
		// 	$timeorderval .= $counthourlyorderval . ',';
		// 	$timeinterval = $mintime + $k . ":00";
		// 	$timeslot .= $timeinterval . ',';
		// 	$k++;
		// }
		// $data['hourlyordernum'] = numbershow(trim($timeordernum, ','), $settinginfo->showdecimal);
		// $data['hourlyorderval'] = trim($timeorderval, ',');
		// $data['hourltimeslot'] = trim($timeslot, ',');

		// $data['incomes'] = $allincome;
		// $data['expenses'] = $allexpense;

		// $waiterlist = $this->home_model->waiterlist();
		// $waitername = '';
		// $waiterordervalue = '';
		// foreach ($waiterlist as $waiter) {
		// 	$waitername .= $waiter->first_name . ',';
		// 	$waiterorder = $this->home_model->waiterorder($waiter->emp_his_id, date('Y-m-01'), date('Y-m-d'));
		// 	if (empty($waiterorder)) {
		// 		$waiterorder = 0;
		// 	}
		// 	$waiterordervalue .= $waiterorder . ',';
		// }
		// $data['waiter'] = trim($waitername, ',');
		// $data['waiterordervalue'] = trim($waiterordervalue, ',');




		// $sql = "SELECT order_menu.`menu_id`,order_menu.`price`,order_menu.`varientid`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,item_foods.small_thumb,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` desc limit 10";
		// $query = $this->db->query($sql);
		// $topsell = $query->result();
		// $data["topsellerday"] = $topsell;

		// $sql2 = "SELECT order_menu.`menu_id`,order_menu.`price`,order_menu.`varientid`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,item_foods.small_thumb,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` asc limit 10";
		// $query2 = $this->db->query($sql2);
		// $slowsell = $query2->result();
		// $data["slowselling"] = $slowsell;

		$data["monthname"] = trim($months, ',');
		
		echo Modules::run('template/layout', $data);
	}

	public function allkitchen2()
	{
		$data['kitchen_card'] = [
			'Pending' => [
				[
					'token_no' => "TS6543",
					'table_no' => "T012",
					'order_no' => "SL0456",
					'type' => "Takeaway",
					'waiter' => "Shek Jasim Uddin",
					'customer' => "Abdullha Umaier",
					'items' => [
						[
							'item_name' => "Chicken Wings (Regular)",
							'addons' => ['Extra Cheese', 'Salad'],
							'note' => "",
						],
						[
							'item_name' => "Grilled Sandwich",
							// 'addons' => [],
							'addons' => ['Extra Cheese', 'Tomato Ketchup'],
							'note' => "No Onions",
							// 'note' => "",
						],
						
						[
							'item_name' => "Chicken Wings (Regular)",
							'addons' => ['Extra Cheese', 'Salad'],
							'note' => "",
						],
						[
							'item_name' => "Grilled Sandwich",
							'addons' => [],
							// 'addons' => ['Extra Cheese', 'Tomato Ketchup'],
							'note' => "No Onions",
							// 'note' => "",
						],
						[
							'item_name' => "Margarita Pizza",
							'addons' => ['Extra Cheese'],
							'note' => "Thin Crust",
						],
					],
				],
				[
					'token_no' => "TS6546",
					'table_no' => "T015",
					'order_no' => "SL0459",
					'type' => "Dine-In",
					'waiter' => "Fatima Noor",
					'customer' => "John Doe",
					'items' => [
						[
							'item_name' => "Margarita Pizza",
							'addons' => ['Extra Cheese'],
							'note' => "Thin Crust",
						],
						[
							'item_name' => "Garlic Bread",
							'addons' => ['Extra Butter'],
							'note' => "Crispy",
						],
					],
				],
				[
					'token_no' => "TS6547",
					'table_no' => "T016",
					'order_no' => "SL0460",
					'type' => "Takeaway",
					'waiter' => "Ahmed Khan",
					'customer' => "Jane Smith",
					'items' => [
						[
							'item_name' => "Beef Burger",
							'addons' => ['Extra Patty', 'Cheese', 'Fries'],
							'note' => "Medium Rare",
						],
						[
							'item_name' => "Chocolate Shake",
							'addons' => [],
							'note' => "Less Sugar",
						],
						[
							'item_name' => "Beef Burger",
							'addons' => ['Extra Patty', 'Cheese', 'Fries'],
							'note' => "Medium Rare",
						],
						[
							'item_name' => "Chocolate Shake",
							'addons' => [],
							'note' => "Less Sugar",
						],
						[
							'item_name' => "Beef Burger",
							'addons' => ['Extra Patty', 'Cheese', 'Fries'],
							'note' => "Medium Rare",
						],
						[
							'item_name' => "Chocolate Shake",
							'addons' => [],
							'note' => "Less Sugar",
						],
						[
							'item_name' => "Chicken Wings (Regular)",
							'addons' => ['Extra Cheese', 'Salad'],
							'note' => "",
						],
					],
				],
    		],

			'Processing' => [
				[
					'token_no' => "TS6544",
					'table_no' => "T013",
					'order_no' => "SL0457",
					'type' => "Dine-In",
					'waiter' => "Mariam Khan",
					'customer' => "Zayn Malik",
					'items' => [
						[
							'item_name' => "BBQ Chicken Pizza",
							'addons' => ['Extra Cheese', 'Olives'],
							'note' => "Well Done",
						],
						[
							'item_name' => "Caesar Salad",
							'addons' => ['Croutons', 'Extra Dressing'],
							'note' => "No Bacon",
						],
					],
				],
				[
					'token_no' => "TS6544",
					'table_no' => "T013",
					'order_no' => "SL0457",
					'type' => "Dine-In",
					'waiter' => "Mariam Khan",
					'customer' => "Zayn Malik",
					'items' => [
						[
							'item_name' => "BBQ Chicken Pizza",
							'addons' => ['Extra Cheese', 'Olives'],
							'note' => "Well Done",
						],
						[
							'item_name' => "Caesar Salad",
							'addons' => ['Croutons', ],
							'note' => "No Bacon",
						],
					],
				],
			],
			'Prepared' => [
				[
					'token_no' => "TS6545",
					'table_no' => "T014",
					'order_no' => "SL0458",
					'type' => "Takeaway",
					'waiter' => "Ali Reza",
					'customer' => "Emma Watson",
					'items' => [
						[
							'item_name' => "Spaghetti Bolognese",
							'addons' => ['Parmesan Cheese'],
							'note' => "Extra Sauce",
						],
					],
				],
			],
		];		

   

		$data['title']    = "Counter Dashboard";
		#page path 
		$data['module'] = "dashboard";
		$data['page']   = "home/allkitchen2";	

		echo Modules::run('template/layout', $data);
	}

	public function counterboard2()
	{

		$data['counter_card'] = [
			'Ready' => [
				[
					'type' => "Ready",
					'title' => "Ready",
					'heading_bg' => base_url('assets/img/counter_card/ready-head-bg.webp'),
					'items' => [
						[
							'table_img' => base_url('assets/img/counter_card/ready-table.webp'),
							'table_no' => "T012",
							'order_no' => "SL0456",
							'runtime' => "10M:15S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/ready-table.webp'),
							'table_no' => "T013",
							'order_no' => "SL0301",
							'runtime' => "15M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/ready-table.webp'),
							'table_no' => "T014",
							'order_no' => "SL0600",
							'runtime' => "20M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/ready-table.webp'),
							'table_no' => "T015",
							'order_no' => "SL0500",
							'runtime' => "20M:00S",
						],
					],
				],
			],
			'Processing' => [
				[
					'type' => "Processing",
					'title' => "Processing",
					'heading_bg' => base_url('assets/img/counter_card/processing-head-bg.webp'),
					'items' => [
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T012",
							'order_no' => "SL0456",
							'runtime' => "10M:15S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T013",
							'order_no' => "SL0301",
							'runtime' => "15M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T014",
							'order_no' => "SL0600",
							'runtime' => "20M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T015",
							'order_no' => "SL0500",
							'runtime' => "20M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T016",
							'order_no' => "SL0600",
							'runtime' => "20M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/processing-table.webp'),
							'table_no' => "T020",
							'order_no' => "SL0700",
							'runtime' => "20M:00S",
						],
					],
				],
			],
			'Pending' => [
				[
					'type' => "Pending",
					'title' => "Pending",
					'heading_bg' => base_url('assets/img/counter_card/pending-head-bg.webp'),
					'items' => [
						[
							'table_img' => base_url('assets/img/counter_card/pending-table.webp'),
							'table_no' => "T012",
							'order_no' => "SL0456",
							'runtime' => "10M:15S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/pending-table.webp'),
							'table_no' => "T013",
							'order_no' => "SL0301",
							'runtime' => "15M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/pending-table.webp'),
							'table_no' => "T014",
							'order_no' => "SL0600",
							'runtime' => "20M:00S",
						],
						[
							'table_img' => base_url('assets/img/counter_card/pending-table.webp'),
							'table_no' => "T015",
							'order_no' => "SL0500",
							'runtime' => "20M:00S",
						],
					],
				],
			],
		];
			
   
		
		$data['title']    = "Counter Dashboard";
		#page path 
		$data['module'] = "dashboard";
		$data['page']   = "home/counterboard2";	

		echo Modules::run('template/layout', $data);
	}


	public function report_dashboard()
	{
		if ($this->permission->method('dashboard', 'read')->access() == FALSE) {
			if (isset($_GET['status'])) {
				redirect("dashboard/home/profile?status=done");
			} else {
				redirect("dashboard/home/profile");
			}
		}




		$data['title']    = display('home');
		#page path 
		$data['module'] = "dashboard";
		$data['page']   = "home/home";

		
		$ctoday = date('Y-m-d');
		$ptoday = date('Y-m-d', strtotime('-1 days'));
		
		$data['settinginfo'] = $settinginfo = $this->db->select('*')->from('setting')->get()->row();
		$currencyinfo = $this->db->select('currencyname,curr_icon')->from('currency')->where('currencyid', $settinginfo->currency)->get()->row();
		$data["currencyicon"] = $currencyinfo->curr_icon;
		$ordernum = $this->home_model->countorder();
		$data["totalorder"]  = $this->changeformat($ordernum);
		$todayorder = $this->home_model->todayorder();
		$data["todayorder"]  = $this->changeformat($todayorder);
		$todayorderprice = $this->home_model->todayamount($ctoday);
		$todayreturnprice = $this->home_model->todayreturnamount($ctoday);
		$allorderprice = $this->home_model->todayamount($allday = null);
		$preorderprice = $this->home_model->todayamount($ptoday);
		$data["allamount"]  = round($allorderprice->amount);
		$data["previousdayamount"]  = round($preorderprice->amount);
		if ($todayorderprice->amount < 1000) {
			if ($todayorderprice->amount > 0) {
				$data["todayamount"]  = round($todayorderprice->amount);
			} else {
				$data["todayamount"]  = "0";
			}
		} else {
			//$data["todayamount"]  =  $this->changeformat($todayorderprice->amount);
			$data["todayamount"]  = round($todayorderprice->amount);
		}
		$data["returnamnt"]  = round($todayreturnprice->amount);
		$customer = $this->home_model->totalcustomer();
		$data["totalcustomer"]  = $this->changeformat($customer);
		$ordersumtypewise = $this->home_model->ordersum();

		$data["customertypewisesales"] = $ordersumtypewise;

		$piestart = date('Y-m-01');
		$pieend = date('Y-m-d');
		$ordersumbydynein = $this->home_model->ordersumbytype(1, $piestart, $pieend);
		$ordersumbyonline = $this->home_model->ordersumbytype(2, $piestart, $pieend);
		$ordersumbythirdparty = $this->home_model->ordersumbytype(3, $piestart, $pieend);
		$ordersumbytakeway = $this->home_model->ordersumbytype(4, $piestart, $pieend);
		$ordersumbyqr = $this->home_model->ordersumbytype(99, $piestart, $pieend);

		$piedata = $ordersumbydynein . ',' . $ordersumbyonline . ',' . $ordersumbytakeway . ',' . $ordersumbythirdparty . ',' . $ordersumbyqr;
		$data["piedatasales"] = $piedata;
		$months = '';
		$day = '';
		$lastday = date("t");
		$weekdaystart = date('d', strtotime('-7 days'));
		$todaydate = date('d');
		$weekday = '';
		$weekdaysales = '';
		$salesamountdaily = '';
		$purchasedaily = '';
		$salesamount = '';
		$salesamountonline = '';
		$totalorderonline = '';
		$salesamountoffline = '';
		$totalorderoffline = '';
		$totalorder = '';
		$allincome = '';
		$allexpense = '';
		$year = date('Y');
		$numbery = date('y');
		$prevyear = $numbery - 1;
		$prevyearformat = $year - 1;
		$syear = '';
		$syearformat = '';
		for ($k = 1; $k < 13; $k++) {
			$month = date('m', strtotime("+$k month"));
			$gety = date('y', strtotime("+$k month"));
			if ($gety == $numbery) {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			} else {
				$syear = $numbery;
				$syearformat = $year;
			}
			$monthly = $this->home_model->monthlysaleamount($syearformat, $month);
			$odernum = $this->home_model->monthlysaleorder($syearformat, $month);

			$salesamount .= $monthly . ', ';
			$totalorder .= $odernum . ', ';

			// $salesamountonline .= $monthlyonline . ', ';
			// $totalorderonline .= $odernumonline . ', ';
			// $salesamountoffline .= $monthlyoffline . ', ';
			// $totalorderoffline .= $odernumoffline . ', ';

			$months .=  date('F-' . $syear, strtotime("+$k month")) . ',';
		}
		for ($d = 1; $d <= $lastday; $d++) {
			$getday = date('Y-m-' . $d);
			$day .= $d . '-' . date('M') . ",";
			$perdaysales = $this->home_model->dailysales($getday);
			$salesamountdaily .= number_format($perdaysales, 2, '.', '') . ', ';

			$perdaypurchase = $this->home_model->purchasedata($getday);
			$purchasedaily .= number_format($perdaypurchase, 2, '.', '') . ', ';

			// ACCM
			// $allinc = $this->home_model->get_head_summeryprofitloss(3, 'Income', $getday, $getday, 0);
			// $allincome .= round($allinc[0]['gtotal']) . ', ';
			// $expn = $this->home_model->get_head_summeryprofitloss(4, 'Expense', $getday, $getday, 0);
			// $allexpense .= round($expn[0]['gtotal']) . ', ';
		}
		$dailysales = trim($salesamountdaily, ',');
		$dailypurchase = trim($purchasedaily, ',');
		$alldays = trim($day, ',');
		$data["dailysaleamount"] = trim($dailysales, ',');
		$data["dailypurchaseamount"] = trim($dailypurchase, ',');
		$data["alldays"] = trim($alldays, ',');

		$data["monthlysaleamount"] = trim($salesamount, ',');
		$data["monthlysaleorder"] = trim($totalorder, ',');
		$lastmonthsdate = date('Y-m-d', strtotime('first day of last month'));
		$lastmonthedate = date('Y-m-d', strtotime('last day of last month'));
		$weeklydate = date('Y-m-d', strtotime('-7 days'));
		$prevweeklydate = date('Y-m-d', strtotime('-15 days'));
		/* ACCM
		$data['todayexpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-d'), date('Y-m-d'), 0);
		$data['predayexpenses'] = $this->home_model->get_head_summery(4, 'Expense', $ptoday, $ptoday, 0);
		$data['weeklyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $weeklydate, date('Y-m-d'), 0);
		$data['prevweeklyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $prevweeklydate, $weeklydate, 0);
		$data['monthlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-01'), date('Y-m-d'), 0);
		$data['prevmonthlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', $lastmonthsdate, $lastmonthedate, 0);
		$data['yearlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-01-01'), date('Y-m-d'), 0);
		$data['prevyearlyxpenses'] = $this->home_model->get_head_summery(4, 'Expense', date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')), 0);
        */
		$data['comtodayordnum'] = $this->home_model->countordercomparision(date('Y-m-d'), date('Y-m-d'));
		$data['comprevordnum'] = $this->home_model->countordercomparision($ptoday, $ptoday);

		$data['comweekordnum'] = $this->home_model->countordercomparision($weeklydate, date('Y-m-d'));
		$data['comweekordval'] = $this->home_model->saleamountcomparision($weeklydate, date('Y-m-d'));
		$data['comprevweekordnum'] = $this->home_model->countordercomparision($prevweeklydate, $weeklydate);
		$data['comprevweekordval'] = $this->home_model->saleamountcomparision($prevweeklydate, $weeklydate);

		$data['commonthordnum'] = $this->home_model->countordercomparision(date('Y-m-01'), date('Y-m-d'));
		$data['commonthordval'] = $this->home_model->saleamountcomparision(date('Y-m-01'), date('Y-m-d'));
		$data['comprevmonthordnum'] = $this->home_model->countordercomparision($lastmonthsdate, $lastmonthedate);
		$data['comprevmonthordval'] = $this->home_model->saleamountcomparision($lastmonthsdate, $lastmonthedate);

		$data['comyearordnum'] = $this->home_model->countordercomparision(date('Y-01-01'), date('Y-m-d'));
		$data['comyearordval'] = $this->home_model->saleamountcomparision(date('Y-01-01'), date('Y-m-d'));
		$data['comprevyearordnum'] = $this->home_model->countordercomparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));
		$data['comprevyearordval'] = $this->home_model->saleamountcomparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));

		$data['todaypurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-d'), date('Y-m-d'));
		$data['prevpurchase'] = $this->home_model->purchasedatacoparision($ptoday, $ptoday);
		$data['weekpurchase'] = $this->home_model->purchasedatacoparision($weeklydate, date('Y-m-d'));
		$data['prevweekpurchase'] = $this->home_model->purchasedatacoparision($prevweeklydate, $weeklydate);
		$data['monthpurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-01'), date('Y-m-d'));
		$data['prevmonthpurchase'] = $this->home_model->purchasedatacoparision($lastmonthsdate, $lastmonthedate);
		$data['yearpurchase'] = $this->home_model->purchasedatacoparision(date('Y-01-01'), date('Y-m-d'));
		$data['prevyearpurchase'] = $this->home_model->purchasedatacoparision(date('Y-m-d', strtotime('first day of january last year')), date('Y-m-d', strtotime('first day of december last year')));

		$gettime = "SELECT MIN(order_time) as mintime,MAX(order_time)as maxtime FROM customer_order WHERE order_date='" . date('Y-m-d') . "'";
		$timesql = $this->db->query($gettime);
		$minmax = $timesql->row();
		//echo $this->db->last_query();

		$minvalue = explode(':', $minmax->mintime);
		$maxvalue = explode(':', $minmax->maxtime);

		$mintime = (int)$minvalue[0];
		$maxtime = (int)$maxvalue[0] + 1;
		$minmaxinterval = (int)$maxtime - (int)$mintime;
		$totalhousperday = $minmaxinterval * 3600;

		$timeordernum = '';
		$timeorderval = '';
		$timeslot = '';
		$time = mktime($mintime, 0, 0, 0, 0);
		$k = 0;
		for ($i = 0; $i < $totalhousperday; $i += 3600) {  // 1800 = half hour, 86400 = one day
			$timestart = sprintf('%1$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3600));
			$timeend = sprintf('%2$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3599));
			$counthourlyorder = $this->home_model->hourlyordernum($timestart, $timeend, date('Y-m-d'));
			$counthourlyorderval = $this->home_model->hourlyordeval($timestart, $timeend, date('Y-m-d'));
			$timeordernum .= $counthourlyorder . ',';
			$timeorderval .= $counthourlyorderval . ',';
			$timeinterval = $mintime + $k . ":00";
			$timeslot .= $timeinterval . ',';
			$k++;
		}
		$data['hourlyordernum'] = numbershow(trim($timeordernum, ','), $settinginfo->showdecimal);
		$data['hourlyorderval'] = trim($timeorderval, ',');
		$data['hourltimeslot'] = trim($timeslot, ',');

		$data['incomes'] = $allincome;
		$data['expenses'] = $allexpense;

		$waiterlist = $this->home_model->waiterlist();
		$waitername = '';
		$waiterordervalue = '';
		foreach ($waiterlist as $waiter) {
			$waitername .= $waiter->first_name . ',';
			$waiterorder = $this->home_model->waiterorder($waiter->emp_his_id, date('Y-m-01'), date('Y-m-d'));
			if (empty($waiterorder)) {
				$waiterorder = 0;
			}
			$waiterordervalue .= $waiterorder . ',';
		}
		$data['waiter'] = trim($waitername, ',');
		$data['waiterordervalue'] = trim($waiterordervalue, ',');




		$sql = "SELECT order_menu.`menu_id`,order_menu.`price`,order_menu.`varientid`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,item_foods.small_thumb,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` desc limit 10";
		$query = $this->db->query($sql);
		$topsell = $query->result();
		$data["topsellerday"] = $topsell;

		$sql2 = "SELECT order_menu.`menu_id`,order_menu.`price`,order_menu.`varientid`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,item_foods.small_thumb,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` asc limit 10";
		$query2 = $this->db->query($sql2);
		$slowsell = $query2->result();
		$data["slowselling"] = $slowsell;

		$data["monthname"] = trim($months, ',');
		
		echo Modules::run('template/layout', $data);
	}


	public function chartjs()
	{
		$allbasicinfo = "";
		$months = '';
		$day = '';
		$lastday = date("t");
		$weekdaystart = date('d', strtotime('-7 days'));
		$todaydate = date('d');
		$weekday = '';
		$weekdaysales = '';
		$salesamountdaily = '';
		$salesamount = '';
		$salesamountonline = '';
		$totalorderonline = '';
		$salesamountoffline = '';
		$totalorderoffline = '';
		$totalorder = '';
		$year = date('Y');
		$numbery = date('y');
		$prevyear = $numbery - 1;
		$prevyearformat = $year - 1;
		$syear = '';
		$syearformat = '';
		for ($k = 1; $k < 13; $k++) {
			$month = date('m', strtotime("+$k month"));
			$gety = date('y', strtotime("+$k month"));
			if ($gety == $numbery) {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			} else {
				$syear = $numbery;
				$syearformat = $year;
			}
			$monthly = $this->home_model->monthlysaleamount($syearformat, $month);
			$odernum = $this->home_model->monthlysaleorder($syearformat, $month);
			$monthlyonline = $this->home_model->onlinesaleamount($syearformat, $month);
			$odernumonline = $this->home_model->onlinesaleorder($syearformat, $month);
			$monthlyoffline = $this->home_model->offlinesaleamount($syearformat, $month);
			$odernumoffline = $this->home_model->offlinesaleorder($syearformat, $month);

			$salesamount .= $monthly . ', ';
			$totalorder .= $odernum . ', ';

			$salesamountonline .= $monthlyonline . ', ';
			$totalorderonline .= $odernumonline . ', ';
			$salesamountoffline .= $monthlyoffline . ', ';
			$totalorderoffline .= $odernumoffline . ', ';
			$months .=  date('F-' . $syear, strtotime("+$k month")) . ',';
		}

		for ($d = 1; $d <= $lastday; $d++) {
			$getday = date('Y-m-' . $d);
			$day .= $d . '-' . date('M') . ",";
			$perdaysales = $this->home_model->dailysales($getday);
			$salesamountdaily .= number_format($perdaysales, 2, '.', '') . ', ';
		}

		for ($w = $weekdaystart + 1; $w <= $todaydate; $w++) {
			$getwday = date('Y-m-' . $w);
			$weekday .= date('D', strtotime($getwday)) . ',';
			$perdaywsales = $this->home_model->dailysales($getwday);
			$weekdaysales .= number_format($perdaywsales, 2, '.', '') . ', ';
		}
		$weeklysales = trim($weekdaysales, ',');
		$weekdayname = trim($weekday, ',');

		$monthlysaleamount = trim($salesamount, ',');
		$monthlysaleorder = trim($totalorder, ',');
		$onlinesaleamount = trim($salesamountonline, ',');
		$onlinesaleorder = trim($totalorderonline, ',');
		$offlinesaleamount = trim($salesamountoffline, ',');
		$offlinesaleorder = trim($totalorderoffline, ',');
		$dailysales = trim($salesamountdaily, ',');
		$alldays = trim($day, ',');

		$monthname = trim($months, ',');

		$data['chartinfo'] = $allbasicinfo;
		header('Content-Type: text/javascript');
		echo ('window.chartinfo = {"weeknames":"' . trim($weekdayname, ', ') . '","weeklysaleamount":"' . trim($weeklysales, ', ') . '","alldays":"' . trim($alldays, ', ') . '","dailylysaleamount":"' . trim($dailysales, ', ') . '","monthlysaleamount":"' . trim($monthlysaleamount, ', ') . '",monthlysaleorder:"' . trim($monthlysaleorder, ', ') . '","onlinesaleamount":"' . trim($onlinesaleamount, ', ') . '","onlinesaleorder":"' . trim($onlinesaleorder, ', ') . '","offlinesaleamount":"' . trim($offlinesaleamount, ', ') . '","offlinesaleorder":"' . trim($offlinesaleorder, ', ') . '","monthname":' . "'" . trim($monthname, ', ') . "'" . '};');
		exit();
	}
	public function checkmonth()
	{
		$monyhyear = $this->input->post('monthyear');
		$getmonth = date('m', strtotime($monyhyear));
		$totalmonth = $getmonth + 1;
		$year = date('Y', strtotime($monyhyear));
		$months = '';
		$salesamount = '';
		$totalorder = '';
		$numbery = date('y', strtotime($monyhyear));
		$yformat = date('Y', strtotime($monyhyear));
		$year = date('Y');
		$numbery = date('y');

		$prevyear = $numbery - 1;
		$prevyearformat = $year - 1;
		$syear = '';
		$syearformat = '';
		$d = (int)$getmonth;
		for ($d = $totalmonth; $d < 13; $d++) {
			$month = date('m', strtotime("+$d month"));
			$gety = date('y', strtotime("+$d month"));
			if ($gety == $numbery) {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			} else {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			}
			$monthly = $this->home_model->monthlysaleamount($year, $month);
			$odernum = $this->home_model->monthlysaleorder($year, $month);
			$salesamount .= $monthly . ', ';
			$totalorder .= $odernum . ', ';
			$months .=  date('F-' . $syear, strtotime("+$d month")) . ',';
		}
		for ($k = 1; $k < $totalmonth; $k++) {
			$month = date('m', strtotime("+$k month"));
			$gety = date('y', strtotime("+$k month"));
			if ($gety == $numbery) {
				$syear = $prevyear;
				$syearformat = $prevyearformat;
			} else {
				$syear = $numbery;
				$syearformat = $yformat;
			}
			$monthly = $this->home_model->monthlysaleamount($syearformat, $month);
			$odernum = $this->home_model->monthlysaleorder($syearformat, $month);
			$salesamount .= $monthly . ', ';
			$totalorder .= $odernum . ', ';
			$months .=  date('F-' . $syear, strtotime("+$k month")) . ',';
		}
		$data["monthlysaleamount"] = trim($salesamount, ',');
		$data["monthlysaleorder"] = trim($totalorder, ',');
		$data["monthname"] = trim($months, ',');


		$data['module'] = "dashboard";
		$data['page']   = "home/searchchart";
		$this->load->view('dashboard/home/searchchart', $data);
	}

	public function checkrange()
	{
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$graphtype = $this->input->post('graphtype');
		$newfrDate = date("m", strtotime($fromdate));
		$newtoDate = date("m", strtotime($todate));


		$targetlabelstr = '';
		$salesamount = '';
		$purchaseamount = '';
		if ($graphtype == 'ordertype') {

			$ordersumbydynein = $this->home_model->ordersumbytype(1, $fromdate, $todate);
			$ordersumbyonline = $this->home_model->ordersumbytype(2, $fromdate, $todate);
			$ordersumbythirdparty = $this->home_model->ordersumbytype(3, $fromdate, $todate);
			$ordersumbytakeway = $this->home_model->ordersumbytype(4, $fromdate, $todate);
			$ordersumbyqr = $this->home_model->ordersumbytype(99, $fromdate, $todate);

			$salesamount = $ordersumbydynein . ',' . $ordersumbyonline . ',' . $ordersumbytakeway . ',' . $ordersumbythirdparty . ',' . $ordersumbyqr;
		}
		if ($graphtype == 'salesgph') {
			if ($newfrDate != $newtoDate) {

				$start    = new DateTime($fromdate);
				//var_dump($start);
				$start->modify('first day of this month');
				$end      = new DateTime($todate);
				$end->modify('first day of next month');
				$interval = DateInterval::createFromDateString('1 month');
				$period   = new DatePeriod($start, $interval, $end);

				foreach ($period as $dt) {
					$targetyear = $dt->format("Y");
					$targetmonth = $dt->format("m");
					$targetlabelstr .= $dt->format("M") . '-' . $dt->format("Y") . ',';
					$monthly = $this->home_model->monthlysaleamount($targetyear, $targetmonth);
					$salesamount .= $monthly . ', ';
				}
			} else {
				$begin = new DateTime($fromdate);
				$end   = new DateTime($todate);
				for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
					//echo $i->format("Y-m-d");
					$getday = $i->format("Y-m-d");
					$targetlabelstr .= $i->format("d") . '-' . $i->format("M") . ',';
					$perdaysales = $this->home_model->dailysales($getday);
					$salesamount .= number_format($perdaysales, 2, '.', '') . ', ';
				}
			}
		}
		if ($graphtype == 'purchasegph') {
			if ($newfrDate != $newtoDate) {
				$start    = new DateTime($fromdate);
				$start->modify('first day of this month');
				$end      = new DateTime($todate);
				$end->modify('first day of next month');
				$interval = DateInterval::createFromDateString('1 month');
				$period   = new DatePeriod($start, $interval, $end);


				foreach ($period as $dt) {
					$targetyear = $dt->format("Y");
					$targetmonth = $dt->format("m");
					$targetlabelstr .= $dt->format("M") . '-' . $dt->format("Y") . ',';
					$monthly = $this->home_model->purchasedatamonthly($targetyear, $targetmonth);
					$salesamount .= $monthly . ', ';
				}
			} else {
				$begin = new DateTime($fromdate);
				$end   = new DateTime($todate);
				for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
					//echo $i->format("Y-m-d");
					$getday = $i->format("Y-m-d");
					$targetlabelstr .= $i->format("d") . '-' . $i->format("M") . ',';
					$perdaysales = $this->home_model->purchasedata($getday);
					$salesamount .= number_format($perdaysales, 2, '.', '') . ', ';
				}
			}
		}

		if ($graphtype == 'waitersales') {
			$waiterlist = $this->home_model->waiterlist();
			foreach ($waiterlist as $waiter) {
				$targetlabelstr .= $waiter->first_name . ',';
				$waiterorder = $this->home_model->waiterorder($waiter->emp_his_id, $fromdate, $todate);
				if (empty($waiterorder)) {
					$waiterorder = 0;
				}
				$salesamount .= $waiterorder . ',';
			}
		}

		if ($graphtype == 'expincome') {
			if ($newfrDate != $newtoDate) {
				$start    = new DateTime($fromdate);
				$start->modify('first day of this month');
				$end      = new DateTime($todate);
				$end->modify('first day of next month');
				$interval = DateInterval::createFromDateString('1 month');
				$period   = new DatePeriod($start, $interval, $end);

				foreach ($period as $dt) {
					$targetstartdate = $dt->format("Y-m-01");
					$targetenddate = $dt->format("Y-m-t");
					$targetlabelstr .= $dt->format("M") . '-' . $dt->format("Y") . ',';

					$allinc = $this->home_model->get_head_summeryprofitloss(3, 'Income', $targetstartdate, $targetenddate, 0);
					$salesamount .= round($allinc[0]['gtotal']) . ', ';
					$expn = $this->home_model->get_head_summeryprofitloss(4, 'Expense', $targetstartdate, $targetenddate, 0);
					$purchaseamount .= round($expn[0]['gtotal']) . ', ';
				}
			} else {
				$begin = new DateTime($fromdate);
				$end   = new DateTime($todate);
				for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
					//echo $i->format("Y-m-d");
					$getday = $i->format("Y-m-d");
					$targetlabelstr .= $i->format("d") . '-' . $i->format("M") . ',';

					$allinc = $this->home_model->get_head_summeryprofitloss(3, 'Income', $getday, $getday, 0);
					$salesamount .= round($allinc[0]['gtotal']) . ', ';
					$expn = $this->home_model->get_head_summeryprofitloss(4, 'Expense', $getday, $getday, 0);
					$purchaseamount .= round($expn[0]['gtotal']) . ', ';
				}
			}
		}

		if ($graphtype == 'hourlyflow') {
			$gettime = "SELECT MIN(order_time) as mintime,MAX(order_time)as maxtime FROM customer_order WHERE order_date BETWEEN '" . $fromdate . "' AND '" . $todate . "'";
			$timesql = $this->db->query($gettime);
			$minmax = $timesql->row();

			$minvalue = explode(':', $minmax->mintime);
			$maxvalue = explode(':', $minmax->maxtime);

			$mintime = $minvalue[0];
			$maxtime = $maxvalue[0] + 1;
			$minmaxinterval = $maxtime - $mintime;
			$totalhousperday = $minmaxinterval * 3600;


			$timeslot = '';
			$time = mktime($mintime, 0, 0, 0, 0);
			$k = 0;
			for ($i = 0; $i < $totalhousperday; $i += 3600) {  // 1800 = half hour, 86400 = one day
				$timestart = sprintf('%1$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3600));
				$timeend = sprintf('%2$s',  date('H:i', $time + $i), date('H:i', $time + $i + 3599));
				$counthourlyorder = $this->home_model->hourlyordernum($timestart, $timeend, $fromdate);
				$counthourlyorderval = $this->home_model->hourlyordeval($timestart, $timeend, $fromdate);
				$purchaseamount .= $counthourlyorder . ',';
				$salesamount .= $counthourlyorderval . ',';
				$timeinterval = $mintime + $k . ":00";
				$targetlabelstr .= $timeinterval . ',';
				$k++;
			}
			/*echo $purchaseamount;
		echo "<br>";
		echo $salesamount;
		echo "<br>";
		echo $targetlabelstr;
		echo "<br>";*/
		}
		$data["purchaseamount"] = trim($purchaseamount, ',');
		$data["saleamount"] = trim($salesamount, ',');
		$data["labelstring"] = trim($targetlabelstr, ',');
		$data["graphtype"] = $graphtype;
		$data['module'] = "dashboard";
		$data['page']   = "home/getchart";
		$this->load->view('dashboard/home/getchart', $data);
	}

	public function profile()
	{
		$data['title']  = "Profile";
		$data['module'] = "dashboard";
		$data['page']   = "home/profile";
		$id = $this->session->userdata('id');
		$data['user']   = $this->home_model->profile($id);
		echo Modules::run('template/layout', $data);
	}

	public function setting()
	{
		$data['title']    = "Profile Setting";
		$id = $this->session->userdata('id');
		/*-----------------------------------*/
		$this->form_validation->set_rules('firstname', 'First Name', 'required|max_length[50]|alpha_numeric_spaces');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|max_length[50]|alpha_numeric_spaces');
		#------------------------#
		$this->form_validation->set_rules('email', 'Email Address', "required|valid_email|max_length[100]");

		#------------------------#
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]|md5');
		$this->form_validation->set_rules('about', 'About', 'max_length[1000]');
		/*-----------------------------------*/
		$config['upload_path']          = './assets/img/user/';
		$config['allowed_types']        = 'gif|jpg|png';

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			$data = $this->upload->data();
			$image = $config['upload_path'] . $data['file_name'];

			$config['image_library']  = 'gd2';
			$config['source_image']   = $image;
			$config['create_thumb']   = false;
			$config['maintain_ratio'] = TRUE;
			$config['width']          = 115;
			$config['height']         = 90;
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			$this->session->set_flashdata('message', "Image Upload Successfully!");
		}
		/*-----------------------------------*/
		$data['user'] = (object)$userData = array(
			'id' 		  => $this->input->post('id'),
			'firstname'   => $this->input->post('firstname', true),
			'lastname' 	  => $this->input->post('lastname', true),
			'email' 	  => $this->input->post('email', true),
			'password' 	  => md5($this->input->post('password')),
			'about' 	  => $this->input->post('about', true),
			'image'   	  => (!empty($image) ? $image : $this->input->post('old_image', true))
		);

		/*-----------------------------------*/
		if ($this->form_validation->run()) {

			if (empty($userData['image'])) {
				$this->session->set_flashdata('exception', $this->upload->display_errors());
			}

			if ($this->home_model->setting($userData)) {

				$this->session->set_userdata(array(
					'fullname'   => $this->input->post('firstname', true) . ' ' . $this->input->post('lastname', true),
					'email' 	  => $this->input->post('email', true),
					'image'   	  => (!empty($image) ? $image : $this->input->post('old_image'))
				));


				$this->session->set_flashdata('message', display('update_successfully'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("dashboard/home/setting");
		} else {
			$data['module'] = "dashboard";
			$data['page']   = "home/profile_setting";
			if (!empty($id))
				$data['user']   = $this->home_model->profile($id);
			echo Modules::run('template/layout', $data);
		}
	}
}