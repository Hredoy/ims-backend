<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-pencil-square-o"></i> Quick Marks Entry</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- Filter Panel -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam &amp; Class</h3>
                    </div>
                    <div class="box-body">
                        <div id="qmMsg"></div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam') . ' ' . $this->lang->line('group'); ?> <small class="req">*</small></label>
                                    <select id="qm_examgroup" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($examgrouplist as $eg) { ?>
                                        <option value="<?php echo $eg->id; ?>"><?php echo $eg->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam'); ?> <small class="req">*</small></label>
                                    <select id="qm_exam" class="form-control" disabled>
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?> <small class="req">*</small></label>
                                    <select id="qm_subject" class="form-control" disabled>
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('session'); ?> <small class="req">*</small></label>
                                    <select id="qm_session" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($sessionlist as $s) { ?>
                                        <option value="<?php echo $s['id']; ?>" <?php echo ($s['id'] == $current_session) ? 'selected' : ''; ?>>
                                            <?php echo $s['session']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?> <small class="req">*</small></label>
                                    <select id="qm_class" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($classlist as $cl) { ?>
                                        <option value="<?php echo $cl['id']; ?>"><?php echo $cl['class']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select id="qm_section" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" id="qmLoadBtn" class="btn btn-primary btn-block">
                                        <i class="fa fa-search"></i> Load Students
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marks Entry Panel -->
                <div id="qmMarksPanel" style="display:none;">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> Enter Marks</h3>
                            <div class="box-tools pull-right">
                                <span class="badge bg-blue" id="qmSubjectInfo"></span>
                                <span class="badge bg-red ml5" id="qmMaxMarks"></span>
                            </div>
                        </div>
                        <div class="box-body">
                            <form id="qmMarksForm">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" id="qm_subject_hidden" name="exam_group_class_batch_exam_subject_id">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="qmStudentTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <th><?php echo $this->lang->line('attendence'); ?></th>
                                                <th><?php echo $this->lang->line('marks'); ?> <small class="text-muted" id="qmMaxLabel"></small></th>
                                                <th><?php echo $this->lang->line('note'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="qmStudentBody">
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <div id="qmSaveMsg"></div>
                            <?php if ($this->rbac->hasPrivilege('exam_marks', 'can_edit')) { ?>
                            <button type="button" id="qmSaveBtn" class="btn btn-success btn-block">
                                <i class="fa fa-save"></i> Save Marks
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function () {
    var base_url    = '<?php echo base_url(); ?>';
    var allSections = <?php echo json_encode($all_sections); ?>;
    var attendenceTypes = <?php echo json_encode($attendence_exam); ?>;

    // Load sections when class changes
    $(document).on('change', '#qm_class', function () {
        var class_id = $(this).val();
        var $sel = $('#qm_section');
        $sel.html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
        if (class_id && allSections[class_id]) {
            $.each(allSections[class_id], function (i, obj) {
                $sel.append('<option value="' + obj.section_id + '">' + obj.section + '</option>');
            });
        }
    });

    // Load exams when exam group changes
    $(document).on('change', '#qm_examgroup', function () {
        var group_id = $(this).val();
        $('#qm_exam').html('<option value=""><?php echo $this->lang->line('select'); ?></option>').prop('disabled', true);
        $('#qm_subject').html('<option value=""><?php echo $this->lang->line('select'); ?></option>').prop('disabled', true);
        if (!group_id) return;
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/examgroup/getExamsByGroup',
            data: {group_id: group_id},
            dataType: 'json',
            success: function (data) {
                var $sel = $('#qm_exam').prop('disabled', false);
                $.each(data, function (i, obj) {
                    $sel.append('<option value="' + obj.id + '">' + obj.exam + '</option>');
                });
            }
        });
    });

    // Load subjects when exam changes
    $(document).on('change', '#qm_exam', function () {
        var exam_id = $(this).val();
        $('#qm_subject').html('<option value=""><?php echo $this->lang->line('select'); ?></option>').prop('disabled', true);
        if (!exam_id) return;
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/examgroup/getSubjectsByExam',
            data: {exam_id: exam_id},
            dataType: 'json',
            success: function (data) {
                var $sel = $('#qm_subject').prop('disabled', false);
                $.each(data, function (i, obj) {
                    $sel.append('<option value="' + obj.id + '">' + obj.subject_name + ' (Max: ' + obj.max_marks + ')</option>');
                });
            }
        });
    });

    // Load students
    $('#qmLoadBtn').on('click', function () {
        var subject_id = $('#qm_subject').val();
        var class_id   = $('#qm_class').val();
        var section_id = $('#qm_section').val();
        var session_id = $('#qm_session').val();

        if (!subject_id || !class_id || !session_id) {
            $('#qmMsg').html('<div class="alert alert-warning">Please select Exam Group, Exam, Subject, Session and Class.</div>');
            return;
        }
        $('#qmMsg').html('');

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            type: 'POST',
            url: base_url + 'admin/examgroup/getStudentsForMarks',
            data: {
                exam_subject_id: subject_id,
                class_id: class_id,
                section_id: section_id,
                session_id: session_id
            },
            dataType: 'json',
            success: function (res) {
                var students       = res.students;
                var subject_detail = res.subject_detail;
                var max_marks      = subject_detail ? subject_detail.max_marks : '?';
                var subject_name   = subject_detail ? subject_detail.subject_name : '';

                $('#qm_subject_hidden').val(subject_id);
                $('#qmSubjectInfo').text(subject_name);
                $('#qmMaxMarks').text('Max: ' + max_marks);
                $('#qmMaxLabel').text('/ ' + max_marks);

                var tbody = '';
                if (students && students.length > 0) {
                    $.each(students, function (i, s) {
                        var attendHtml = '';
                        $.each(attendenceTypes, function (k, v) {
                            var chk = (s.exam_group_exam_result_attendance == v) ? 'checked' : '';
                            attendHtml += '<label class="checkbox-inline"><input type="checkbox" class="attendance_chk" name="exam_group_student_attendance_' + s.exam_group_class_batch_exam_students_id + '" value="' + v + '" ' + chk + '> ' + v + '</label> ';
                        });
                        tbody += '<tr>';
                        tbody += '<td>' + (i + 1) + '</td>';
                        tbody += '<input type="hidden" name="exam_group_student_id[]" value="' + s.exam_group_class_batch_exam_students_id + '">';
                        tbody += '<input type="hidden" name="prev_id[' + s.exam_group_class_batch_exam_students_id + ']" value="' + s.exam_group_exam_result_id + '">';
                        tbody += '<td>' + s.admission_no + '</td>';
                        tbody += '<td>' + s.firstname + ' ' + (s.lastname || '') + '</td>';
                        tbody += '<td>' + (s.father_name || '') + '</td>';
                        tbody += '<td>' + attendHtml + '</td>';
                        tbody += '<td><input type="number" step="any" class="form-control marks-input" name="exam_group_student_mark_' + s.exam_group_class_batch_exam_students_id + '" value="' + (s.exam_group_exam_result_get_marks || '') + '" max="' + max_marks + '" style="width:90px;"></td>';
                        tbody += '<td><input type="text" class="form-control" name="exam_group_student_note_' + s.exam_group_class_batch_exam_students_id + '" value="' + (s.exam_group_exam_result_note || '') + '" style="width:120px;"></td>';
                        tbody += '</tr>';
                    });
                    $('#qmStudentBody').html(tbody);
                    $('#qmMarksPanel').show();
                    $('#qmSaveMsg').html('');
                } else {
                    $('#qmStudentBody').html('');
                    $('#qmMarksPanel').hide();
                    $('#qmMsg').html('<div class="alert alert-info">No students found for this selection. Make sure students are assigned to this exam group.</div>');
                }
            },
            error: function () {
                $('#qmMsg').html('<div class="alert alert-danger">Error loading students.</div>');
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fa fa-search"></i> Load Students');
            }
        });
    });

    // Save marks
    $('#qmSaveBtn').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#qmSaveMsg').html('');

        $.ajax({
            type: 'POST',
            url: base_url + 'admin/examgroup/entrymarks',
            data: $('#qmMarksForm').serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status == 1) {
                    $('#qmSaveMsg').html('<div class="alert alert-success"><i class="fa fa-check"></i> ' + res.message + '</div>');
                    $('html, body').animate({scrollTop: $('#qmSaveMsg').offset().top - 100}, 400);
                } else {
                    $('#qmSaveMsg').html('<div class="alert alert-danger">' + (res.error ? JSON.stringify(res.error) : 'Error saving marks.') + '</div>');
                }
            },
            error: function () {
                $('#qmSaveMsg').html('<div class="alert alert-danger">Server error. Please try again.</div>');
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Marks');
            }
        });
    });
});
</script>
