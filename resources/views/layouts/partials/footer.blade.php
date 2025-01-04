</div>
<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                Copyright © <span id="year">2023</span>
                Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0);"> SAS</a>
                All rights reserved
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER END -->
</div>
<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top" style="display: inline;"><i class="fa fa-angle-up"></i></a>
<!-- JQUERY JS -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- BOOTSTRAP JS -->

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>

<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('assets/js/validation.js') }}"></script>

<!-- SIDEBAR JS -->
<script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>
<!-- INPUT MASK JS-->
<script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>
<!-- SIDE-MENU JS-->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
<!-- Sticky js -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>
<!-- Perfect SCROLLBAR JS-->
<script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/p-scroll/pscroll.js') }}"></script>
<!-- Color Theme js -->
<script src="{{ asset('assets/js/themeColors.js') }}"></script>
<!-- swither styles js -->
<script src="{{ asset('assets/js/swither-styles.js') }}"></script>
<!-- select2 -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script src="{{ asset('assets/js/fusion/fusioncharts.js') }}"></script>

<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>



<!-- CUSTOM JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/add-new-row.js') }}"></script>
<script src="{{ asset('assets/js/custom-script.js') }}"></script>
{{-- <script src="{{ asset('assets/js/bulk-approval-rejection.js') }}"></script> --}}
<script>
    $(document).ready(function() {
        $('form').find('input').attr('autocomplete', 'off');
    });
</script>
<script>
    var appUrl = "{{ url('/') }}/";
</script>
@stack('page_scripts')
</div>
</body>

</html>
