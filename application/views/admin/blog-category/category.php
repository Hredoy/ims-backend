<style type="text/css">
    .table-sortable tbody tr {
        cursor: move;
    }

    .mt-2 {
        margin-top: 2rem;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-empire"></i> <?php echo $this->lang->line('front_cms'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="holist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('category'); ?></h3>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="table-responsive">
                                    <div class="mailbox-controls">
                                        <div class="pull-right">
                                        </div><!-- /.pull-right -->
                                    </div>
                                    <div class="mailbox-messages">
                                        <div class="download_label"><?php ?> <?php echo $this->lang->line('gallery_list'); ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th class="text-right no-print">
                                                        <?php echo $this->lang->line('action'); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $data) : ?>
                                                    <tr>
                                                        <td><?= $data->name ?></td>
                                                        <td>
                                                            <a data-placement="left" href="<?php echo site_url('admin/blogCategory/delete/' . $data->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table><!-- /.table -->
                                    </div><!-- /.mail-box-messages -->
                                </div>
                            </div>
                            <div class="col-sm-4 col-sm-offset-1">
                                <form action="<?php echo site_url('admin/blogCategory/create'); ?>" method="post">
                                    <div class="from-group">
                                        <label for="name">Name</label>
                                        <input type="text" name='name' class="form-control">
                                    </div>
                                    <div class="form-group mt-2">
                                        <input type="submit" value="ADD" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->