<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-header">
            <i class="fa fa-book"></i>  <?php echo $this->lang->line('library'); ?>
        </div>
        <div class="card-body">
            <div class="mailbox-messages table-responsive">
                <div class="download_label"><?php echo $this->lang->line('book_list'); ?></div>
                <table id="" class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?php echo $this->lang->line('book_title'); ?></th>
                        <th><?php echo $this->lang->line('book_no'); ?></th>
                        <th><?php echo $this->lang->line('isbn_no'); ?></th>
                        <th><?php echo $this->lang->line('publisher'); ?>
                        </th>
                        <th><?php echo $this->lang->line('author'); ?>
                        </th>
                        <th><?php echo $this->lang->line('subject'); ?></th>
                        <th><?php echo $this->lang->line('rack_no'); ?></th>
                        <th><?php echo $this->lang->line('qty'); ?></th>
                        <th><?php echo $this->lang->line('available'); ?></th>
                        <th><?php echo $this->lang->line('bookprice'); ?></th>
                        <th><?php echo $this->lang->line('postdate'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 1;
                    if (!empty($listbook)) {
                        foreach ($listbook as $book) {
                            ?>
                            <tr>
                                <td class="mailbox-name">
                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $book['book_title'] ?></a>
                                    <div class="fee_detail_popover" style="display: none">
                                        <?php
                                        if ($book['description'] == "") {
                                            ?>
                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                            <?php
                                        } else {
                                            ?>
                                            <p class="text text-info"><?php echo $book['description']; ?></p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="mailbox-name"> <?php echo $book['book_no'] ?></td>
                                <td class="mailbox-name"> <?php echo $book['isbn_no'] ?></td>
                                <td class="mailbox-name"> <?php echo $book['publish'] ?></td>
                                <td class="mailbox-name"> <?php echo $book['author'] ?></td>
                                <td class="mailbox-name"><?php echo $book['subject'] ?></td>
                                <td class="mailbox-name"><?php echo $book['rack_no'] ?></td>
                                <td class="mailbox-name"> <?php echo $book['qty'] ?></td>
                                <td class="mailbox-name"> <?php echo $book['qty'] - $book['total_issue'] ?></td>
                                <td class="mailbox-name"> <?php echo ($currency_symbol . $book['perunitcost']); ?></td>
                                <td class="mailbox-name"> <?php
                                    echo $this->customlib->dateformat($book['postdate']);
                                    ?></td>

                            </tr>
                            <?php
                            $count++;
                        }
                    }
                    ?>
                    </tbody>
                </table><!-- /.table -->
            </div><!-- /.mail-box-messages -->
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
    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }


    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });


    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>