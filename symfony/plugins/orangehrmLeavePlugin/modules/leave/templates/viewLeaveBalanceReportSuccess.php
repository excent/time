<?php

use_javascripts_for_form($form);
use_stylesheets_for_form($form);
use_stylesheet(plugin_web_path('orangehrmLeavePlugin', 'css/viewLeaveBalanceReport'));
?>


<?php if ($form->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    </div>
<?php endif; ?>
<div class="box searchForm" id="leave-balance-report">
    <div class="head">
        <h1><?php echo ($mode == 'my') ? __("My Leave Entitlements and Usage Report") : __("Leave Entitlements and Usage Report");?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php if (!isset($hide_form)): ?>
        <form id="frmLeaveBalanceReport" name="frmLeaveBalanceReport" method="post" 
              action="">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>                   
                <p>
                    <input type="button" name="view" id="viewBtn" value="<?php echo __('View');?>"/>                    
                </p>
            </fieldset>
        </form>
        <?php endif;?>
    </div> <!-- inner -->    
</div> 

<?php if (!empty($resultsSet)) { ?>
    <div id="report-results" class="box noHeader">
        <div class="inner">
            <?php if ($pager->haveToPaginate()):?>
            <div class="top">
                <?php include_partial('core/report_paging', array('pager' => $pager));?>                
            </div>
            <?php endif; ?> 
            <table class="table nosort" cellspacing="0" cellpadding="0">

            <?php $headers = $sf_data->getRaw('tableHeaders');
                  $headerInfo = $sf_data->getRaw('headerInfo');?>

                <thead class="fixedHeader">
                <tr class="heading">
                    <?php 
                          foreach($headers as $mainHeader => $subHeaders):  
                              $subHead = array_shift($subHeaders);
                    ?>                      
                    <th class="header" colspan="<?php echo count($subHeaders);?>" style="text-align: center;"><?php echo __($subHead);?></th>
                    <?php endforeach;?>
                </tr>
                <tr class="subHeading">
                    <?php $i = 0; foreach($headers as $subHeaders): array_shift($subHeaders);?>

                            <?php foreach($subHeaders as $subHeader):?>
                    <th class="header" style="text-align: center;" ><?php echo __($subHeader);?></th>
                            <?php endforeach;?>                    
                    <?php endforeach;?>
                </tr>
                </thead>
                <?php                
                    $reportBuilder = new ReportBuilder();
                    $linkParamsRaw = $sf_data->getRaw('linkParams');
                    $rowCssClass = "even";
                    $results = $sf_data->getRaw('resultsSet');?>                
                <tbody class="scrollContent"> 
                <?php foreach ($results as $row):      
                    
                        $rowCssClass = ($rowCssClass === 'odd') ? 'even' : 'odd';?>                      
                <tr class="<?php echo $rowCssClass;?>">
                <?php foreach ($row as $key => $column):                            
                         $info = $headerInfo[$key];
                         $tdClass = !empty($info['align']) ? " class='{$info['align']}'" : '';
                         if(is_array($column)):
                            foreach ($column as $colKey => $colVal):
                                $headInf = $info[$colKey];                                                                            
                                if(($headInf["groupDisp"] == "true") && ($headInf["display"] == "true")):?>
                                    <!--<td><table>-->
                                    <td><ul>                                      
                                        <ul>                                         
                                        <?php foreach($colVal as $data):?>
                                               <!--<tr style="height: 10px;"><td headers="10"><?php // echo __($data);?></td></tr>-->                                               
                                               <li><?php echo esc_specialchars(__($data));?></li>                                        
                                        <?php endforeach;?>
                                        </ul>                                    
                                     </td>
                                     <!--</table></td>-->
                            <?php endif;                                                                                      
                             endforeach;
                         else:
                             //echo $key . '-' . $column;
                            if(($info["groupDisp"] == "true") && ($info["display"] == "true")):?>
                            <td<?php echo $tdClass;?>><?php if(($column == "") || is_null($column)):
                                    echo "0.00";
                                else :
                                    
                                    if (isset($info['link'])):
                                        if ($reportType == LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE):
                                            $linkParamsRaw['empNumber'] = array($row['empNumber']);
                                        else:
                                            $linkParamsRaw['leaveType'] = array($row['leaveTypeId']);
                                        endif;
 
                                        $link = $info['link'];
                                        if ($mode == 'my') {
                                            $link = str_replace('viewLeaveList', 'viewMyLeaveList', $link);
                                        }
                                        $url = $reportBuilder->replaceHeaderParam($link, $linkParamsRaw);
                                        echo link_to(esc_specialchars(__($column)), $url);
                                    
                                    else:
                                        echo esc_specialchars(__($column));

                                    endif;                                    
                                
                                endif;?></td>
                      <?php else: ?>
                            <input type="hidden" name="<?php echo $key;?>[]" value="<?php echo $column;?>"/>
                      <?php endif;
                         endif;?>                            
                 <?php endforeach;?>
                 </tr>             
                 <?php endforeach;?>
                </tbody>
            </table>
            <?php if ($pager->haveToPaginate()):?>
            <div class="bottom">
                <?php include_partial('core/report_paging', array('pager' => $pager));?>                
            </div>
            <?php endif; ?>             
        </div>    
    </div>
<?php } ?>

<script type="text/javascript">
    var employeeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_EMPLOYEE;?>;
    var leaveTypeReport = <?php echo LeaveBalanceReportForm::REPORT_TYPE_LEAVE_TYPE;?>;
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';    
    
    function submitPage(pageNo) {
        var actionUrl = $('#frmLeaveBalanceReport').attr('action') + '?pageNo=' + pageNo;
        $('#frmLeaveBalanceReport').attr('action', actionUrl).submit(); 
    }
    
    function toggleReportType(reportType) {
        
        var reportType = $("#leave_balance_report_type").val();
        var reportTypeLi = $('#leave_balance_leave_type').parent('li');
        var employeeNameLi = $('#leave_balance_employee_empName').parent('li');
        var dateLi = $('#date_from').parent('li');
        var jobTitleLi = $('#leave_balance_job_title').parent('li');
        var locationLi = $('#leave_balance_location').parent('li');
        var subUnitLi = $('#leave_balance_sub_unit').parent('li');
        var terminatedLi = $('#leave_balance_include_terminated').parent('li');
        
        var viewBtn = $('#viewBtn');

        if (reportType == employeeReport) {
            reportTypeLi.hide();
            employeeNameLi.show(); 
            dateLi.show();
            jobTitleLi.hide();
            locationLi.hide();
            subUnitLi.hide();
            terminatedLi.hide();
            viewBtn.show();
           
        } else if (reportType == leaveTypeReport) {
            reportTypeLi.show();
            employeeNameLi.hide();           
            jobTitleLi.show();
            locationLi.show();
            subUnitLi.show();
            terminatedLi.show();            
            dateLi.show();            
            viewBtn.show();
        } else {
            reportTypeLi.hide();
            employeeNameLi.hide();                    
            dateLi.hide();
            jobTitleLi.hide();
            locationLi.hide();
            subUnitLi.hide();
            terminatedLi.hide();
            viewBtn.hide();
            
            var reportTypeWidget = $("#leave_balance_report_type");
            var empNameWidget = $("#leave_balance_employee_empName");
            empNameWidget.innerWidth(reportTypeWidget.innerWidth());
            
            
        }        
    }   
   
    $(document).ready(function() {        
        
        $('a.total').live('click', function(){
            
        });
        
        <?php if ($mode != 'my') { ?>
        toggleReportType();
        <?php } ?>       
        
        $('#report-results table.table thead.fixedHeader tr:first').hide();
        
        $('#viewBtn').click(function() {
            $('#frmLeaveBalanceReport').submit();
        });
        
        $("#leave_balance_report_type").change(function() {          
            toggleReportType();
        });
        
        $('#frmLeaveBalanceReport').validate({
                rules: {
                    'leave_balance[employee][empName]': {
                        required: function(element) {
                            return $("#leave_balance_report_type").val() == leaveTypeReport;
                        },
                        no_default_value: function(element) {
                            return {
                                defaults: $(element).data('typeHint')
                            }
                        }
                    },
                    'leave_balance[leave_type]':{required: function(element) {
                            return $("#leave_balance_report_type").val() == employeeReport;
                        } 
                    },
                    'leave_balance[date][from]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,                                
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        }
                    },
                    'leave_balance[date][to]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        },
                        date_range: function() {
                            return {
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat,
                                fromDate:$("#date_from").val()
                            }
                        }
                    }
                    
                },
                messages: {
                    'leave_balance[employee][empName]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                        no_default_value:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'leave_balance[leave_type]':{
                        required:'<?php echo __(ValidationMessages::REQUIRED); ?>'
                    },
                    'leave_balance[date][from]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate
                    },
                    'leave_balance[date][to]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate ,
                        date_range: lang_dateError
                    }                  
            }

        });        

    });

</script>

