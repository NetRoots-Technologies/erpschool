<script src="<?php echo e(url('/js/admin/ledgers_tree/create_modify.js')); ?>" type="text/javascript"></script>


<!-- JQuery min js -->
<script src="<?php echo e(asset('dist/assets/plugins/jquery/jquery.min.js')); ?>"></script>


<!-- Bootstrap Bundle js -->
<script src="<?php echo e(asset('dist/assets/plugins/bootstrap/js/popper.min.js')); ?>"></script>


<script src="<?php echo e(asset('dist/assets/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>

<!--Internal  Chart.bundle js -->
<script src="<?php echo e(asset('dist/assets/plugins/chart.js/Chart.bundle.min.js')); ?>"></script>

<!-- Moment js -->
<script src="<?php echo e(asset('dist/assets/plugins/moment/moment.js')); ?>"></script>

<!--Internal Sparkline js -->
<script src="<?php echo e(asset('dist/assets/plugins/jquery-sparkline/jquery.sparkline.min.js')); ?>"></script>


<!-- Moment js -->
<script src="<?php echo e(asset('dist/assets/plugins/raphael/raphael.min.js')); ?>"></script>

<!--Internal Apexchart js-->



<!-- Rating js-->
<script src="<?php echo e(asset('dist/assets/plugins/ratings-2/jquery.star-rating.js')); ?>"></script>

<script src="<?php echo e(asset('dist/assets/plugins/ratings-2/star-rating.js')); ?>"></script>

<!--Internal  Perfect-scrollbar js -->

<script src="<?php echo e(asset('dist/assets/plugins/perfect-scrollbar/p-scroll.js')); ?>"></script>
<!-- Eva-icons js -->
<script src="<?php echo e(asset('dist/assets/js/eva-icons.min.js')); ?>"></script>

<!-- right-sidebar js -->
<script src="<?php echo e(asset('dist/assets/plugins/sidebar/sidebar.js')); ?>"></script>


<script src="<?php echo e(asset('dist/assets/plugins/sidebar/sidebar-custom.js')); ?>"></script>

<!-- Sticky js -->
<script src="<?php echo e(asset('dist/assets/js/sticky.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/modal-popup.js')); ?>"></script>

<!-- Left-menu js-->
<script src="<?php echo e(asset('dist/assets/plugins/side-menu/sidemenu.js')); ?>"></script>
<!-- Internal Map -->
<script src="<?php echo e(asset('dist/assets/plugins/jqvmap/jquery.vmap.min.js')); ?>"></script>


<script src="<?php echo e(asset('dist/assets/plugins/jqvmap/maps/jquery.vmap.usa.js')); ?>"></script>

<!--Internal  index js -->


<!--themecolor js-->
<script src="<?php echo e(asset('dist/assets/js/themecolor.js')); ?>"></script>

<!-- Apexchart js-->

<script src="<?php echo e(asset('dist/assets/js/jquery.vmap.sampledata.js')); ?>"></script>

<!-- custom js -->
<script src="<?php echo e(asset('dist/assets/switcher/js/switcher.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/custom-1.js')); ?>"></script>


<!--Internal Fileuploads js-->
<script src="<?php echo e(asset('dist/assets/plugins/fileuploads/js/fileupload.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/fileuploads/js/file-upload.js')); ?>"></script>

<!--Internal Fancy uploader js-->
<script src="<?php echo e(asset('dist/assets/plugins/fancyuploder/jquery.ui.widget.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/fancyuploder/jquery.fileupload.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/fancyuploder/jquery.iframe-transport.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/fancyuploder/fancy-uploader.js')); ?>"></script>

<script src="<?php echo e(asset('dist/assets/plugins/sumoselect/jquery.sumoselect.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/select2/js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/select2.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')); ?>"></script>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.1/api/sum().js"></script>


<script src="https://cdn.jsdelivr.net/npm/[email protected]/dist/js/select2.min.js"></script>


<script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function () {
        var activeItem = $('.slide-item.active');

        if (activeItem.length > 0) {
            var sidebar = activeItem.closest('.app-sidebar');
            var sidebarHeight = sidebar.height();
            var activeItemOffset = activeItem.offset().top - sidebar.offset().top;
            var activeItemHeight = activeItem.outerHeight(true);
            var scrollTop = activeItemOffset - (sidebarHeight / 2) + (activeItemHeight / 2);

            sidebar.scrollTop(scrollTop);
        }
    });

    $('.side-menu__item').on('click', function () {
        var divSlideMenu = $(this).next('.slide-menu').closest('ul');
        console.log(divSlideMenu);
        if (divSlideMenu.css('display') === 'block') {
            divSlideMenu.css('display', 'none');
        } else {
            divSlideMenu.css('display', 'block');
        }
    });



</script>
<?php /**PATH C:\Users\afnan\Desktop\cornerstone\resources\views/admin/layouts/js.blade.php ENDPATH**/ ?>