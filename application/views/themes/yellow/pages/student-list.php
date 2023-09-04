<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">  <i class="fa fa-user-plus"></i> শিক্ষার্থীর তথ্য</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <form role="form" action="<?php echo site_url('student-list') ?>" method="post" class="">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>

                            </div>
                        </form>

                    </div>
                    <div class="col-sm-6">
                        <form role="form" action="<?php echo site_url('student-list') ?>" method="post" class="">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                    <input type="text" name="search_text" class="form-control" value="<?php  echo set_value('search_text'); ?>"   placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($resultlist)) {
    ?>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                    <thead>
                    <tr>

                        <th><?php echo $this->lang->line('admission_no'); ?></th>

                        <th><?php echo $this->lang->line('student_name'); ?></th>
                        <th><?php echo $this->lang->line('class'); ?></th>
                        <?php if ($sch_setting->father_name) {  ?>
                            <th><?php echo $this->lang->line('father_name'); ?></th>
                        <?php } ?>
                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                        <th><?php echo $this->lang->line('gender'); ?></th>
                        <?php if ($sch_setting->category) {
                            ?>
                            <?php if ($sch_setting->category) {  ?>
                                <th><?php echo $this->lang->line('category'); ?></th>
                            <?php }
                        } if ($sch_setting->mobile_no) {
                            ?>
                            <th><?php echo $this->lang->line('mobile_no'); ?></th>
                            <?php
                        }
                        if (!empty($fields)) {

                            foreach ($fields as $fields_key => $fields_value) {
                                ?>
                                <th><?php echo $fields_value->name; ?></th>
                                <?php
                            }
                        }
                        ?>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (empty($resultlist)) {
                        ?>

                        <?php
                    } else {
                        $count = 1;
                        foreach ($resultlist as $student) {
                            ?>
                            <tr>

                                <td><?php echo $student['admission_no']; ?></td>

                                <td>

                                    <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname); ?>
                                    </a>
                                </td>
                                <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                <?php if ($sch_setting->father_name) {  ?>
                                    <td><?php echo $student['father_name']; ?></td>
                                <?php }?>
                                <td><?php
                                    if ($student["dob"] != null && $student["dob"]!='0000-00-00') {
                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                    }
                                    ?></td>
                                <td><?php echo $student['gender']; ?></td>
                                <?php if ($sch_setting->category) {  ?>
                                    <td><?php echo $student['category']; ?></td>
                                <?php } if ($sch_setting->mobile_no) {  ?>
                                    <td><?php echo $student['mobileno']; ?></td>
                                <?php }
                                if (!empty($fields)) {

                                    foreach ($fields as $fields_key => $fields_value) {
                                        $display_field=$student[$fields_value->name];
                                        if($fields_value->type == "link"){
                                            $display_field= "<a href=".$student[$fields_value->name]." target='_blank'>".$student[$fields_value->name]."</a>";

                                        }
                                        ?>
                                        <td>
                                            <?php echo $display_field; ?>

                                        </td>
                                        <?php
                                    }
                                }
                                ?>


                            </tr>
                            <?php
                            $count++;
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>

<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/chartjs/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/fastclick/fastclick.min.js"></script>
<script src="<?php echo base_url(); ?>backend/dist/js/app.min.js"></script>
<!--nprogress-->
<script src="<?php echo base_url(); ?>backend/dist/js/nprogress.js"></script>
<!--file dropify-->
<script src="<?php echo base_url(); ?>backend/dist/js/dropify.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>backend/dist/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>backend/dist/datatables/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.colVis.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>backend/dist/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/ss.custom.js"></script>
<script type="text/javascript">


    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>