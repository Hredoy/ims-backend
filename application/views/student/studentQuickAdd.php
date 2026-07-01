<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bolt"></i> Quick Add Student
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user-plus"></i> Student Quick Entry</h3>
                        <div class="box-tools pull-right">
                            <span id="qaSuccessCount" class="badge bg-green" style="display:none;">0 added</span>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="qaMsg"></div>
                        <form id="quickAddStudentForm">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <?php if (!$adm_auto_insert) { ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('admission_no'); ?> <small class="req">*</small></label>
                                        <input type="text" name="admission_no" id="qa_admission_no" class="form-control" placeholder="<?php echo $this->lang->line('admission_no'); ?>">
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('first_name'); ?> <small class="req">*</small></label>
                                        <input type="text" name="firstname" id="qa_firstname" class="form-control" placeholder="<?php echo $this->lang->line('first_name'); ?>" autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('last_name'); ?></label>
                                        <input type="text" name="lastname" id="qa_lastname" class="form-control" placeholder="<?php echo $this->lang->line('last_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('gender'); ?> <small class="req">*</small></label>
                                        <select name="gender" id="qa_gender" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($genderList as $gk => $gv) { ?>
                                            <option value="<?php echo $gk; ?>"><?php echo $gv; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_of_birth'); ?> <small class="req">*</small></label>
                                        <input type="text" name="dob" id="qa_dob" class="form-control date" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('mobile_no'); ?></label>
                                        <input type="text" name="mobileno" id="qa_mobileno" class="form-control" placeholder="<?php echo $this->lang->line('mobile_no'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?> <small class="req">*</small></label>
                                        <select name="class_id" id="qa_class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $cl) { ?>
                                            <option value="<?php echo $cl['id']; ?>"><?php echo $cl['class']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select name="section_id" id="qa_section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <?php if ($sch_setting->guardian_name) { ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian_name'); ?> <small class="req">*</small></label>
                                        <input type="text" name="guardian_name" id="qa_guardian_name" class="form-control" placeholder="<?php echo $this->lang->line('guardian_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian'); ?> <small class="req">*</small></label>
                                        <select name="guardian_is" id="qa_guardian_is" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if ($sch_setting->guardian_phone) { ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian_phone'); ?> <small class="req">*</small></label>
                                        <input type="text" name="guardian_phone" id="qa_guardian_phone" class="form-control" placeholder="<?php echo $this->lang->line('guardian_phone'); ?>">
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="qaSaveBtn" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i> Save &amp; Add Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function () {
    var base_url = '<?php echo base_url(); ?>';
    var addedCount = 0;
    var savedClassId = '';
    var savedSectionId = '';

    var allSections = <?php echo json_encode($all_sections); ?>;

    function loadSections(class_id, selected_id) {
        var $sel = $('#qa_section_id');
        $sel.html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
        if (class_id && allSections[class_id]) {
            $.each(allSections[class_id], function (i, obj) {
                var sel = (selected_id && obj.section_id == selected_id) ? ' selected' : '';
                $sel.append('<option value="' + obj.section_id + '"' + sel + '>' + obj.section + '</option>');
            });
        }
    }

    $(document).on('change', '#qa_class_id', function () {
        savedSectionId = '';
        loadSections($(this).val(), null);
    });

    $('#qaSaveBtn').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#qaMsg').html('');

        savedClassId  = $('#qa_class_id').val();
        savedSectionId = $('#qa_section_id').val();

        var formData = $('#quickAddStudentForm').serialize();
        $.ajax({
            type: 'POST',
            url: base_url + 'student/quick_add',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    addedCount++;
                    $('#qaSuccessCount').show().text(addedCount + ' added');
                    $('#qaMsg').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-check"></i> Student saved successfully!</div>');

                    $('#quickAddStudentForm')[0].reset();
                    if (savedClassId) {
                        $('#qa_class_id').val(savedClassId);
                        loadSections(savedClassId, savedSectionId);
                    }

                    $('#qa_firstname').focus();
                } else {
                    $('#qaMsg').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-times"></i> ' + res.message + '</div>');
                }
            },
            error: function () {
                $('#qaMsg').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save &amp; Add Next');
            }
        });
    });

    $(document).on('keydown', '#quickAddStudentForm input, #quickAddStudentForm select', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#qaSaveBtn').trigger('click');
        }
    });
});
</script>
