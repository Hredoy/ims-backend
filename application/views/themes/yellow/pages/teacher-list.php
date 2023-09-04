<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">শিক্ষক-কর্মচারীর তথ্য</div>
            <div class="card-body">
                <?php if ($this->session->flashdata('msg')) { ?>  <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <form role="form" action="<?php echo site_url('teacher-stuff-list') ?>" method="post" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("role"); ?></label><small class="req"> *</small>
                                        <select name="role" class="form-control">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($role as $key => $role_value) {
                                                ?>
                                                <option <?php
                                                if ($role_id == $role_value["id"]) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $role_value['id'] ?>"><?php echo $role_value['type'] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <form role="form" action="<?php echo site_url('teacher-stuff-list') ?>" method="post" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                        <input type="text" name="search_text" class="form-control" value="<?php echo set_value('search_text');?>"  placeholder="<?php echo $this->lang->line('search_by_staff'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                শিক্ষক-কর্মচারীর লিস্ট
            </div>
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link " id="nav-card-tab" data-bs-toggle="tab" data-bs-target="#nav-card" type="button" role="tab" aria-controls="nav-card" aria-selected="false"><i class="fa fa-newspaper-o"></i> CARD VIEW</button>
                        <button class="nav-link active" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="true"><i class="fa fa-list"></i>  LIST VIEW</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade " id="nav-card" role="tabpanel" aria-labelledby="nav-card-tab">
                        <div class="row">
                            <?php if (empty($resultlist)) {
                                ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                <?php
                            } else {
                                $count = 1;
                                foreach ($resultlist as $staff) {

                                    ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6 img_div_modal">
                                        <div class="staffinfo-box">
                                            <div class="staffleft-box">
                                                <?php
                                                if (!empty($staff["image"])) {
                                                    $image = $staff["image"];
                                                } else {
                                                    if($staff['gender']=='Male'){
                                                        $image = "default_male.jpg";
                                                    }else{
                                                        $image = "default_female.jpg";
                                                    }

                                                }
                                                ?>
                                                <img  src="<?php echo base_url() . "uploads/staff_images/" . $image ?>" />
                                            </div>
                                            <div class="staffleft-content">
                                                <h5><span data-toggle="tooltip" title="<?php echo $this->lang->line('name'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["name"] . " " . $staff["surname"]; ?></span></h5>
                                                <p><font data-toggle="tooltip" title="<?php echo "Employee Id"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["employee_id"] ?></font></p>
                                                <p><font data-toggle="tooltip" title="<?php echo "Contact Number"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["contact_no"] ?></font></p>
                                                <p><font data-toggle="tooltip" title="<?php echo 'Location'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php
                                                        if (!empty($staff["location"])) {
                                                            echo $staff["location"] . ",";
                                                        }
                                                        ?></font><font data-toggle="tooltip" title="<?php echo 'Department'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $staff["department"]; ?></font></p>
                                                <p class="staffsub" ><span data-toggle="tooltip" title="<?php echo $this->lang->line('role'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["user_type"] ?></span> <span data-toggle="tooltip" title="<?php echo 'Designation'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["designation"] ?></span></p>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                <?php }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane active fade show" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                <th><?php echo $this->lang->line('name'); ?></th>
                                <th><?php echo $this->lang->line('role'); ?></th>
                                <th><?php echo $this->lang->line('department'); ?></th>
                                <th><?php echo $this->lang->line('designation'); ?></th>
                                <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                <?php
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

                            } else {
                                $count = 1;
                                foreach ($resultlist as $staff) {
                                    ?>
                                    <tr>
                                        <td><?php echo $staff['employee_id']; ?></td>
                                        <td>
                                            <?php echo $staff['name'] . " " . $staff['surname']; ?>
                                        </td>

                                        <td><?php echo $staff['user_type']; ?></td>
                                        <td><?php echo $staff['department']; ?></td>
                                        <td><?php echo $staff['designation']; ?></td>
                                        <td><?php echo $staff['contact_no']; ?></td>
                                        <?php
                                        if (!empty($fields)) {

                                            foreach ($fields as $fields_key => $fields_value) {
                                                $display_field=$staff[$fields_value->name];
                                                if($fields_value->type == "link"){
                                                    $display_field= "<a href=".$staff[$fields_value->name]." target='_blank'>".$staff[$fields_value->name]."</a>";

                                                }
                                                ?>
                                                <td>

                                                    <?php echo $display_field; ?></td>
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
        </div>
    </div>
</div>
<style type="text/css">

</style>


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