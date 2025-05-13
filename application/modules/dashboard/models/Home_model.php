<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function checkUser($data = array()) {
        return $this->db->select("
				user.id,
				CONCAT_WS(' ', user.firstname, user.lastname) AS fullname,
				user.email,
				user.image,
				user.last_login,
				user.last_logout,
				user.ip_address,
				user.status,
				user.is_admin,
				IF (user.is_admin=1, 'Admin', 'User') as user_level
			")
                    ->from('user')
                    ->where('email', $data['email'])
                    ->where('password', md5($data['password']))
                    ->get();
    }

    public function userPermission($id = null) {
        return $this->db->select("
			module.controller,
			module_permission.fk_module_id,
			module_permission.create,
			module_permission.read,
			module_permission.update,
			module_permission.delete
			")
                    ->from('module_permission')
                    ->join('module', 'module.id = module_permission.fk_module_id', 'full')
                    ->where('module_permission.fk_user_id', $id)
                    ->get()
                    ->result();
    }

    public function last_login($id = null) {
        return $this->db->set('last_login', date('Y-m-d H:i:s'))
                    ->set('ip_address', $this->input->ip_address())
                    ->where('id', $this->session->userdata('id'))
                    ->update('user');
    }

    public function last_logout($id = null) {
        return $this->db->set('last_logout', date('Y-m-d H:i:s'))
                    ->where('id', $this->session->userdata('id'))
                    ->update('user');
    }

    public function profile($id = null) {
        return $this->db->select("
			*,
				CONCAT_WS(' ', firstname, lastname) AS fullname,
				IF (user.is_admin=1, 'Admin', 'User') as user_level
			")
                    ->from("user")
                    ->where("id", $id)
                    ->get()
                    ->row();
    }

    public function setting($data = array()) {
        return $this->db->where('id', $data['id'])
                    ->update('user', $data);
    }

    public function countorder() {
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where('order_status!=', 5);
        $this->db->where('isdelete!=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }
    public function countcompleteorder() {
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where('order_status', 4);
        $this->db->where('isdelete!=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }

    public function todayorder() {
        $today = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where('order_date', $today);
        $this->db->where('order_status!=', 5);
        $this->db->where('isdelete!=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }

    public function totalcustomer() {
        $this->db->select('*');
        $this->db->from('customer_info');
        $this->db->where('is_active', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }

    public function todayamount($sdate = null) {

        $this->db->select('SUM(bill_amount) as amount');
        $this->db->from('bill');
        if (!empty($sdate)) {
            $this->db->where('bill_date', $sdate);
        }
        $this->db->where('bill_status=', 1);
        $this->db->where('isdelete!=', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return 0;
    }

    public function todayreturnamount($sdate = null) {

        $this->db->select('SUM(pay_amount) as amount');
        $this->db->from('sale_return');
        if (!empty($sdate)) {
            $this->db->where('return_date', $sdate);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return 0;
    }
    public function totalreservation() {
        $this->db->select('*');
        $this->db->from('tblreservation');
        $this->db->where('status', '2');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }
    public function latestoreder() {
        $this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('customer_order');
        $this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
        $this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
        $this->db->where('order_status!=', 5);
        $this->db->where('isdelete!=', 1);
        $this->db->order_by('saleinvoice', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function latestoredercount() {
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where('order_status', 1);
        $this->db->where('isdelete!=', 1);
        $this->db->order_by('saleinvoice', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows;
        }
        return 0;
    }
    public function latestonline() {
        $this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('customer_order');
        $this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
        $this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
        $this->db->where('order_status!=', 5);
        $this->db->where('isdelete!=', 1);
        $this->db->where('cutomertype', 2);
        $this->db->order_by('saleinvoice', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function latestreservation() {
        $this->db->select('tblreservation.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('tblreservation');
        $this->db->join('customer_info', 'tblreservation.cid=customer_info.customer_id', 'left');
        $this->db->join('rest_table', 'tblreservation.tableid=rest_table.tableid', 'left');
        $this->db->where('tblreservation.status', 2);
        $this->db->order_by('tblreservation.reserveday', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function latestpending() {
        $this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('customer_order');
        $this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
        $this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
        $this->db->where('order_status', 1);
        $this->db->where('isdelete!=', 1);
        $this->db->order_by('saleinvoice', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
    public function dailysales($days) {
        $amount     = '';
        $wherequery = "bill_date='$days' AND bill_status=1 AND isdelete!=1 GROUP BY bill_date";
        $this->db->select('SUM(bill_amount) as amount');
        $this->db->from('bill');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $total = number_format($row->amount, 2, '.', '');
                $amount .= $total . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function ordersum() {
        $amount     = '';
        $wherequery = "customer_order.cutomertype IN(1,4,2,99,3) GROUP BY customer_order.cutomertype";
        $this->db->select('SUM(bill.bill_amount) as amount,customer_order.cutomertype');
        $this->db->from('bill');
        $this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
        $this->db->where($wherequery, NULL, FALSE);
        $this->db->order_by('customer_order.cutomertype', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $total = number_format($row->amount, 2, '.', '');
                $amount .= $total;
            }
            return trim($amount);
        }
        return 0;
    }
    public function ordersumbytype($type, $start, $end) {
        $wherequery = "customer_order.cutomertype=$type AND bill.bill_date BETWEEN '$start' AND '$end' AND bill.bill_status=1 AND bill.isdelete!=1";
        $this->db->select('SUM(bill.bill_amount) as amount,customer_order.cutomertype');
        $this->db->from('bill');
        $this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row    = $query->row();
            $amount = round($row->amount);
            return $amount;
        }
        return 0;
    }
    public function purchasedata($pdate) {
        $amount     = '';
        $wherequery = "purchasedate='$pdate' GROUP BY purchasedate";
        $this->db->select('SUM(total_price) as amount');
        $this->db->from('purchaseitem');
        $this->db->where($wherequery, NULL, FALSE);
        //echo $this->db->last_query();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $total = round($row->amount);
                $amount .= $total . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function purchasedatamonthly($year, $month) {
        $groupby    = "GROUP BY YEAR(purchasedate), MONTH(purchasedate)";
        $amount     = '';
        $wherequery = "YEAR(purchasedate)='$year' AND MONTH(purchasedate)='$month' GROUP BY YEAR(purchasedate), MONTH(purchasedate)";
        $this->db->select('SUM(total_price) as amount');
        $this->db->from('purchaseitem');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result();

            foreach ($result as $row) {
                $amount .= round($row->amount) . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function getordernum($mydate, $type) {
        $startdate = date('Y-m-d');
        if ($type == 3) {
            $wherequery = "bill_date BETWEEN '" . $startdate . "' AND '" . $mydate . "'";
        } else {
            $wherequery = "bill_date BETWEEN '" . $mydate . "' AND '" . $startdate . "'";
        }

        $orderid = '';
        $this->db->select('order_id');
        $this->db->from('bill');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        $order_ids = array('');
        if ($query->num_rows() > 0) {
            $i      = 0;
            $result = $query->result();
            foreach ($result as $row) {
                $order_ids[$i] = $row->order_id;
                $i++;
            }
        }
        return $order_ids;
    }

    public function countordercomparision($stdate, $endate) {
        $wherequery = "order_date BETWEEN '$stdate' AND '$endate' AND order_status=4 AND isdelete!=1";
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }

    public function saleamountcomparision($stdate, $endate) {
        $amount     = '';
        $wherequery = "bill_date BETWEEN '$stdate' AND '$endate' AND bill_status=1 AND isdelete!=1";
        $this->db->select('SUM(bill_amount) as amount');
        $this->db->from('bill');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            //echo $this->db->last_query();
            $amount = $result->amount;
            return trim($amount, ', ');
        }
        return 0;
    }
    public function purchasedatacoparision($stdate, $endate) {
        $wherequery = "purchasedate BETWEEN '$stdate' AND '$endate'";
        $this->db->select('SUM(total_price) as amount');
        $this->db->from('purchaseitem');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $amount = $result->amount;
            return $amount;
        }
        return 0;
    }

    public function hourlyordernum($stdate, $endate, $searchdate) {
        $wherequery = "order_date='" . $searchdate . "' AND order_time BETWEEN '$stdate' AND '$endate:59'";
        $this->db->select('*');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return 0;
    }
    public function hourlyordeval($stdate, $endate, $searchdate) {
        $wherequery = "bill_date='" . $searchdate . "' AND bill_time BETWEEN '$stdate' AND '$endate:59'";
        $this->db->select('SUM(bill_amount) as amount');
        $this->db->from('bill');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $amount = $result->amount;
            return $amount;
        }
        return 0;
    }

    public function monthlysaleamount($year, $month) {
        $groupby    = "GROUP BY YEAR(bill_date), MONTH(bill_date)";
        $amount     = '';
        $wherequery = "YEAR(bill_date)='$year' AND month(bill_date)='$month' AND bill_status=1 AND isdelete!=1 GROUP BY YEAR(bill_date), MONTH(bill_date)";
        $this->db->select('SUM(bill_amount) as amount');
        $this->db->from('bill');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            //echo $this->db->last_query();
            foreach ($result as $row) {
                $amount .= $row->amount . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function monthlysaleorder($year, $month) {
        $totalorder = '';
        $wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND order_status!=5 AND isdelete!=1 GROUP BY YEAR(order_date), MONTH(order_date)";
        $this->db->select('count(order_id) as totalorder');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $totalorder .= $row->totalorder . ", ";
            }
            return trim($totalorder, ', ');
        }
        return 0;
    }
    public function onlinesaleamount($year, $month) {
        $groupby    = "GROUP BY YEAR(order_date), MONTH(order_date)";
        $amount     = '';
        $wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=2 AND order_status!=5 AND isdelete!=1 GROUP BY YEAR(order_date), MONTH(order_date)";
        $this->db->select('SUM(totalamount) as amount');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $amount .= $row->amount . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function onlinesaleorder($year, $month) {
        $groupby    = "GROUP BY YEAR(order_date), MONTH(order_date)";
        $totalorder = '';
        $wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=2 AND order_status!=5 AND isdelete!=1 GROUP BY YEAR(order_date), MONTH(order_date)";
        $this->db->select('count(order_id) as totalorder');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $totalorder .= $row->totalorder . ", ";
            }
            return trim($totalorder, ', ');
        }
        return 0;
    }
    public function waiterlist() {

        $this->db->select('*');
        $this->db->from('employee_history');
        $this->db->where('pos_id', 6);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        }
        return 0;
    }
    public function waiterorder($waiterid, $sdate, $endate) {
        $amount     = '';
        $wherequery = "order_date BETWEEN '$sdate' AND '$endate' AND waiter_id='$waiterid' AND order_status=4 AND isdelete!=1";
        $this->db->select('SUM(totalamount) as amount');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $amount = $result->amount;
            return round($result->amount);
        }
        return 0;
    }

    public function offlinesaleamount($year, $month) {
        $groupby    = "GROUP BY YEAR(order_date), MONTH(order_date)";
        $amount     = '';
        $wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=1 AND order_status!=5 AND isdelete!=1 GROUP BY YEAR(order_date), MONTH(order_date)";
        $this->db->select('SUM(totalamount) as amount');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $amount .= $row->amount . ", ";
            }
            return trim($amount, ', ');
        }
        return 0;
    }
    public function offlinesaleorder($year, $month) {
        $groupby    = "GROUP BY YEAR(order_date), MONTH(order_date)";
        $totalorder = '';
        $wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=1 AND order_status!=5 AND isdelete!=1 GROUP BY YEAR(order_date), MONTH(order_date)";
        $this->db->select('count(order_id) as totalorder');
        $this->db->from('customer_order');
        $this->db->where($wherequery, NULL, FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $totalorder .= $row->totalorder . ", ";
            }
            return trim($totalorder, ', ');
        }
        return 0;
    }
	/* ACCM

    public function get_head_summery($type, $phead, $dtpFromDate, $dtpToDate, $resultType) {
        $secondLevel = $this->get_secondlebelheadName($type, $phead);
        $mainHead    = array();
        $sumTotal    = 0;
        if ($secondLevel) {
            $secondArray = array();
            foreach ($secondLevel as $chac) {
                $subTotal   = 0;
                $innerArray = array();
                $thirdLevel = $this->get_thirdlebelheadName($type, $chac->id);
                if ($thirdLevel) {
                    $thirdLevelArray = array();
                    foreach ($thirdLevel as $tdl) {
                        $balance         = 0;
                        $transationLevel = $this->get_fourthlebelheadName($type, $tdl->id);

                        if ($transationLevel) {
                            $tDebit  = 0;
                            $tCredit = 0;
                            foreach ($transationLevel as $trans) {
                                $tval = $this->get_general_ledger_report($trans->id, $dtpFromDate, $dtpToDate, 0, 0);
                                //print_r($tval);
                                if ($tval) {
                                    foreach ($tval as $amounts) {
                                        $tDebit += $amounts->Debit;
                                        $tCredit += $amounts->Credit;
                                    }
                                }
                            }
                            if ($type == 1 || $type == 4) {

                                $balance = $tDebit - $tCredit;
                            } else {
                                $balance = $tCredit - $tDebit;
                            }
                            $sumTotal += $balance;
                            $subTotal += $balance;
                        }
                        $cdata = array('headCode' => $tdl->id,
                            'headName'                => $tdl->name,
                            'amount'                  => $balance,
                        );
                        array_push($innerArray, $cdata);
                    }
                }
                $data = array('headCode' => $chac->id,
                    'headName'               => $chac->name,
                    'subtotal'               => $subTotal,
                    'innerHead'              => $innerArray,

                );
                array_push($secondArray, $data);
            }
        }
        $maina = array('head' => $phead,
            'gtotal'              => $sumTotal,
            'nextlevel'           => $secondArray);

        array_push($mainHead, $maina);

        if ($resultType == 0) {
            return $mainHead;
        } else if ($resultType == 1) {
            return $sumTotal;
        }
    }
    public function get_head_summeryprofitloss($type, $phead, $dtpFromDate, $dtpToDate, $resultType) {
        $secondLevel = $this->get_secondlebelheadName($type, $phead, $expt = 1);
        $mainHead    = array();
        $sumTotal    = 0;
        if ($secondLevel) {
            $secondArray = array();
            foreach ($secondLevel as $chac) {
                $subTotal   = 0;
                $innerArray = array();
                $thirdLevel = $this->get_thirdlebelheadName($type, $chac->id);
                if ($thirdLevel) {
                    $thirdLevelArray = array();
                    foreach ($thirdLevel as $tdl) {
                        $balance         = 0;
                        $transationLevel = $this->get_fourthlebelheadName($type, $tdl->id);

                        if ($transationLevel) {
                            $tDebit  = 0;
                            $tCredit = 0;
                            foreach ($transationLevel as $trans) {
                                $tval = $this->get_general_ledger_report($trans->id, $dtpFromDate, $dtpToDate, 0, 0);
                                //print_r($tval);
                                if ($tval) {
                                    foreach ($tval as $amounts) {
                                        $tDebit += $amounts->Debit;
                                        $tCredit += $amounts->Credit;
                                    }
                                }
                            }
                            if ($type == 1 || $type == 4) {

                                $balance = $tDebit - $tCredit;
                            } else {
                                $balance = $tCredit - $tDebit;
                            }
                            $sumTotal += $balance;
                            $subTotal += $balance;
                        }
                        $cdata = array('headCode' => $tdl->id,
                            'headName'                => $tdl->name,
                            'amount'                  => $balance,
                        );
                        array_push($innerArray, $cdata);
                    }
                }
                $data = array('headCode' => $chac->id,
                    'headName'               => $chac->name,
                    'subtotal'               => $subTotal,
                    'innerHead'              => $innerArray,

                );
                array_push($secondArray, $data);
            }
        }
        $maina = array('head' => $phead,
            'gtotal'              => $sumTotal,
            'nextlevel'           => $secondArray);

        array_push($mainHead, $maina);

        if ($resultType == 0) {
            return $mainHead;
        } else if ($resultType == 1) {
            return $sumTotal;
        }
    }
	*/
    public function get_general_ledger_report($cmbCode, $dtpFromDate, $dtpToDate, $chkIsTransction, $isfyear = 0, $subtype = 1, $subcod = null) {
        $financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
        if ($chkIsTransction == 1) {
            $this->db->select('acc_transaction.*,a.Name,b.Name as reversename');
            $this->db->from('acc_transaction');
            $this->db->join('tbl_ledger a', 'a.id = acc_transaction.COAID', 'left');
            $this->db->join('tbl_ledger b', 'b.id = acc_transaction.reversecode', 'left');
            $this->db->where('acc_transaction.IsAppove', 1);
            $this->db->where('acc_transaction.VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"');
            $this->db->where('acc_transaction.COAID', $cmbCode);
            if ($subtype != 1 && $subcod != null) {
                $this->db->join('acc_subtype st', 'acc_transaction.subtype = st.id', 'left');
                $this->db->join('acc_subcode sc', 'acc_transaction.subcode = sc.id', 'left');
                $this->db->where('acc_transaction.subtype', $subtype);
                $this->db->where('acc_transaction.subcode', $subcod);
            }
            if ($isfyear != 0) {
                $this->db->where('acc_transaction.fin_yearid', $financialyears->fiyear_id);
            }
            $this->db->order_by('acc_transaction.VDate', 'Asc');
            $this->db->order_by('acc_transaction.Vtype', 'Asc');
            $query = $this->db->get();
            //echo $this->db->last_query();
            return $query->result();

        } else {
            $this->db->select('sum(acc_transaction.Debit) as Debit,sum(acc_transaction.Credit) as Credit,a.Name,b.Name as reversename');
            $this->db->from('acc_transaction');
            $this->db->join('tbl_ledger a', 'a.id = acc_transaction.COAID', 'left');
            $this->db->join('tbl_ledger b', 'b.id = acc_transaction.reversecode', 'left');
            $this->db->where('acc_transaction.IsAppove', 1);
            $this->db->where('acc_transaction.VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"');
            $this->db->where('acc_transaction.COAID', $cmbCode);
            if ($isfyear != 0) {
                $this->db->where('acc_transaction.fin_yearid', $financialyears->fiyear_id);
            }
            $query = $this->db->get();
            //echo $this->db->last_query();
            return $query->result();
        }
    }
    public function get_secondlebelheadName($type, $phead, $except = null) {
        $this->db->select('tbl_groupacc.id,tbl_groupacc.name,tbl_accnature.id as pheadid,tbl_accnature.name as PHeadName');
        $this->db->from('tbl_groupacc');
        $this->db->join('tbl_accnature', 'tbl_accnature.id=tbl_groupacc.accNatureid', 'left');
        $this->db->where('tbl_groupacc.accNatureid', $type);

        if ($except != null) {
            //$this->db->where("tbl_groupacc.id !=",$except);
        } else {
            $this->db->where('tbl_groupacc.id!=', 15);
            $this->db->where('tbl_groupacc.id!=', 19);
        }
        $this->db->where('tbl_accnature.name', $phead);
        $CharterAccounts = $this->db->get();
        //echo $this->db->last_query().$phead;
        if ($CharterAccounts->num_rows() > 0) {
            return $CharterAccounts->result();
        } else {
            return false;
        }
    }

    /* ACCM
    public function get_thirdlebelheadName($type,$phead, $except = null) {
    $this->db->select('tbl_groupaccsub.id,tbl_groupaccsub.name,tbl_groupacc.id as pheadid,tbl_groupacc.name as PHeadName');
    $this->db->from('tbl_groupaccsub');
    $this->db->join('tbl_groupacc','tbl_groupacc.id=tbl_groupaccsub.GroupID','left');
    $this->db->where('tbl_groupaccsub.AccNatureID', $type);
    if($except != null) {
    $this->db->where("tbl_groupaccsub.id !=",$except);
    }
    $this->db->where('tbl_groupacc.id',$phead);
    //echo $this->db->last_query().$phead;
    $CharterAccounts = $this->db->get();
    if($CharterAccounts->num_rows() > 0) {
    return $CharterAccounts->result();
    } else {
    return false;
    }
    }
     */

    public function get_fourthlebelheadName($type, $phead, $except = null) {
        $this->db->select('tbl_ledger.id,tbl_ledger.Name,tbl_ledger.AssetsCode,tbl_ledger.DepreciationRate,tbl_groupaccsub.id as pheadid,tbl_groupaccsub.name as PHeadName');
        $this->db->from('tbl_ledger');
        $this->db->join('tbl_groupaccsub', 'tbl_groupaccsub.id=tbl_ledger.Groupsubid', 'left');
        $this->db->where('tbl_ledger.NatureID', $type);
        if ($except != null) {
            $this->db->where("tbl_ledger.id !=", $except);
        }
        $this->db->where('tbl_groupaccsub.id', $phead);
        $CharterAccounts = $this->db->get();
        if ($CharterAccounts->num_rows() > 0) {
            return $CharterAccounts->result();
        } else {
            return false;
        }
    }

}
