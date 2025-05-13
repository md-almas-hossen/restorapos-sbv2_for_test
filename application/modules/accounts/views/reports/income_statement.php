<div class="row">
    <div class="col-sm-12 col-md-12">
        <?php echo include($this->load->view('accounts/reports/financial_report_header')) ?>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('balance_sheet') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo  form_open_multipart('accounts/AccReportController/income_statement', array('class' => 'form-inline', 'method' => 'post')) ?>
                <div class="row" id="">
                    <div class="col-sm-8">
     
                        <div class="form-group form-group-new">
                            <label for="dtpFromDate"><?php echo display('financial_year') ?> :</label>
                        </div>
                        <div class="form-group form-group-new empdropdown">
                            <select id="financial_year" class="form-control" name="dtpYear">
                                <option value="">Select Financial Year</option>
                                <?php foreach ($financial_years as $year): ?>
                                    <option 
                                        value="<?php echo $year['title']; ?>" 
                                        data-start_date="<?php echo $year['start_date']; ?>" 
                                        data-end_date="<?php echo $year['end_date']; ?>"
                                        <?php echo (isset($financialyears) && $financialyears->title == $year['title']) ? 'selected' : ''; ?>>
                                        <?php echo $year['title']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group form-group-new">
                            <label for="dtpFromDate"><?php echo display('from_date') ?> :</label>
                            <input type="text" name="dtpFromDate" value="<?php echo isset($financialyears) ? $financialyears->start_date : ''; ?>" class="form-control" id="from_date" readonly/>
                        </div>
                        <div class="form-group form-group-new">
                            <label for="dtpToDate"><?php echo display('to_date') ?> :</label>
                            <input type="text" class="form-control" name="dtpToDate" value="<?php echo isset($financialyears) ? $financialyears->end_date : ''; ?>" id="to_date" readonly/>
                        </div>
                       
                        <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<?php
    $path = base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png'));
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<div class="text-center">
    <img src="<?php echo  $path; ?>" alt="logo">
    <h2 class="mb-0"><?php echo $setting->title; ?></h2>
    <h3 class="mt-10"><?php echo display('income_statement') . ' ' . display('report') ?></h3>
    <h5><?php echo display('as_on') ?> <?php echo $date; ?></h5>
</div>

<table style="width: 70%;margin: auto;" class="table table-bordered">
        <thead style="background:#008d4b9e!important">
            <th>Description</th>
            <th>Amount</th>
            <th>Amount</th>
            <th>Amount</th>
        </thead>
        <tbody>
            <?php foreach($income_statement as $income):?>
            <tr>
                <td> <b> <?php echo $income->description;?> </b> </td>
                <td style="text-align:right"> <b> <?php echo $income->amountA;?> </b> </td>
                <td style="text-align:right"> <b> <?php echo $income->amountB;?> </b> </td>
                <td style="text-align:right"> <b> <?php echo $income->amountC;?> </b> </td>
            </tr>
            <?php endforeach;?>
        </tbody>
</table>