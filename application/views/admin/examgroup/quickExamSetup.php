<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bolt"></i> Quick Exam Setup</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-map-o"></i> Create Exam Group &amp; Exam</h3>
                    </div>
                    <div class="box-body">
                        <div id="qesMsg"></div>
                        <form id="quickExamSetupForm">
                            <?php echo $this->customlib->getCSRF(); ?>

                            <h4 class="text-primary" style="margin-top:0;border-bottom:1px solid #ddd;padding-bottom:6px;">
                                <i class="fa fa-folder-open-o"></i> Exam Group
                            </h4>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?> <small class="req">*</small></label>
                                        <input type="text" name="group_name" id="group_name" class="form-control" placeholder="e.g. First Term Examination" autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') . ' ' . $this->lang->line('type'); ?> <small class="req">*</small></label>
                                        <select name="exam_type" id="exam_type" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($examType as $k => $v) { ?>
                                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('description'); ?></label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Optional description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <h4 class="text-primary" style="border-bottom:1px solid #ddd;padding-bottom:6px;">
                                <i class="fa fa-file-text-o"></i> Exam Details
                            </h4>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam'); ?> <?php echo $this->lang->line('name'); ?> <small class="req">*</small></label>
                                        <input type="text" name="exam_name" id="exam_name" class="form-control" placeholder="e.g. Mathematics Final">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('session'); ?> <small class="req">*</small></label>
                                        <select name="session_id" id="session_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($sessionlist as $s) { ?>
                                            <option value="<?php echo $s['id']; ?>" <?php echo ($s['id'] == $current_session) ? 'selected' : ''; ?>>
                                                <?php echo $s['session']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="callout callout-info" style="margin-top:10px;">
                                <i class="fa fa-info-circle"></i>
                                After saving, you will be taken to the full exam page to assign <strong>students</strong> and <strong>subjects</strong>.
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="qesSaveBtn" class="btn btn-primary btn-block">
                            <i class="fa fa-save"></i> Create &amp; Continue to Full Setup
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

    $('#qesSaveBtn').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
        $('#qesMsg').html('');

        $.ajax({
            type: 'POST',
            url: base_url + 'admin/examgroup/quick_exam_setup_save',
            data: $('#quickExamSetupForm').serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status == 1) {
                    $('#qesMsg').html('<div class="alert alert-success"><i class="fa fa-check"></i> ' + res.message + ' Redirecting...</div>');
                    setTimeout(function () { window.location.href = res.redirect; }, 1500);
                } else {
                    $('#qesMsg').html('<div class="alert alert-danger"><i class="fa fa-times"></i> ' + res.message + '</div>');
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create &amp; Continue to Full Setup');
                }
            },
            error: function () {
                $('#qesMsg').html('<div class="alert alert-danger">Server error. Please try again.</div>');
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create &amp; Continue to Full Setup');
            }
        });
    });
});
</script>
