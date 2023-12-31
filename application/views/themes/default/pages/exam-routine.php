<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="box-title"><i class="fa fa-search"></i> পরীক্ষার রুটিন খুঁজার ফিল্টার</h3>
            </div>
            <div class="card-body">

                <form role="form" action="<?php echo site_url('exam-routine') ?>" method="post">

                    <?php echo $this->customlib->getCSRF(); ?>

                    <div class="row">
                        <div class="col-sm-6 col-lg-3 col-md-3 col20">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('exam') . " " . $this->lang->line('group'); ?></label><small
                                        class="req"> *</small>
                                <select autofocus="" id="exam_group_id" name="exam_group_id"
                                        class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($examgrouplist as $ex_group_key => $ex_group_value) {
                                        ?>
                                        <option value="<?php echo $ex_group_value->id ?>" <?php
                                        if (set_value('exam_group_id') == $ex_group_value->id) {
                                            echo "selected=selected";
                                        }
                                        ?>><?php echo $ex_group_value->name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>
                            </div>
                        </div><!--./col-md-3-->
                        <div class="col-sm-6 col-lg-3 col-md-3 col20">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('exam') ?></label><small class="req">
                                    *</small>
                                <select id="exam_id" name="exam_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                            </div>
                        </div><!--./col-md-3-->


                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" name="search" value="search_filter"
                                        class="btn btn-primary pull-right btn-sm checkbox-toggle"><i
                                            class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">পরীক্ষার রুটিন</div>
                    <table class="table table-hover table-striped table-bordered example" id="subjects_table">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('subject') ?></th>
                            <th><?php echo $this->lang->line('date') . " " . $this->lang->line('from') ?></th>
                            <th><?php echo $this->lang->line('start') . " " . $this->lang->line('time'); ?></th>
                            <th><?php echo $this->lang->line('duration') ?></th>
                            <th><?php echo $this->lang->line('room') . " " . $this->lang->line('no') ?></th>
                            <th><?php echo $this->lang->line('marks') . " (" . $this->lang->line('max') . ".)"; ?></th>
                            <th><?php echo $this->lang->line('marks') . " (" . $this->lang->line('min') . ".)"; ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($exam_subjects)) {
                            foreach ($exam_subjects as $exam_subject_key => $exam_subject_value) {
                                ?>
                                <tr>

                                    <td><?php echo $exam_subject_value->subject_name; ?></td>
                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($exam_subject_value->date_from)); ?></td>
                                    <td><?php echo $exam_subject_value->time_from; ?></td>
                                    <td><?php echo $exam_subject_value->duration; ?></td>

                                    <td><?php echo $exam_subject_value->room_no; ?></td>
                                    <td><?php echo $exam_subject_value->max_marks; ?></td>
                                    <td><?php echo $exam_subject_value->min_marks; ?></td>

                                </tr>
                                <?php
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
    $(document).ready(function () {
        $('.select2').select2();

    });
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: true,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);


    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function (e) {
        $('#exam_id').html("");
        var exam_group_id = $(this).val();
        getExamByExamgroup(exam_group_id, 0);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';


            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = "";
                        if (section_id === obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }


    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';


            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {'exam_group_id': exam_group_id},
                dataType: "json",
                beforeSend: function () {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });
                    $('#exam_id').append(div_data);
                },
                complete: function () {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>

