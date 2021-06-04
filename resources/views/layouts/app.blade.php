<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.head')
    </head>
    <body class="skin-purple hold-transition sidebar-mini">
        <!-- Common Modal -->
        <div id="alertMessageModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Intro Video Modal Popup Start  -->
        <div id="introVideoModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="introPopupHeading">
                            <h4 class="modal-title">You Have Been Accepted!</h4>
                            <span>You are just a few steps away from automating your fulfillment</span>
                        </div>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Let’s Get Started!</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- Intro Video Modal Popup End  -->

        <div class="wrapper">
            @include('layouts.header')
            @include('layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('flash-message')

                @section('main-content')
                @show
            </div>

            <?php //@include('layouts.footer') ?>
        </div>
        @include('layouts.foot')
        @yield('script')
        <script type="text/javascript">
            $(document).ready(function () {
<?php
$settingData = getAdminSettingData();
if (auth()->user()->status == 1 && isset(auth()->user()->intro_video_status) && auth()->user()->intro_video_status == 0 && $settingData['intro_video_url']) {
    ?>
                    showIntroPopup('<?php echo $settingData['intro_video_url']; ?>');
<?php } ?>

                notDelaySuccess();
                var delay = 10000;
                setInterval(function () {
                    notDelaySuccess();
                }, delay);
                $(".notifications-menu").click(function () {
                    var userID = '{{ auth()->user()->id }}';
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("notifications_unread") }}',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data: {"user_id": userID},
                        success: function (data) {
                            if (data.data.success) {
                                if (data.data.notifications && data.data.notifications.length > 0) {
                                    $("#notification_list_header").html('');
                                    $.each(data.data.notifications, function (i, item) {
                                        if (item.notification_url != "") {
                                            url = "{!!url('" + item.notification_url + "')!!}";
                                        } else {
                                            url = "{{ url('storenotifications')}}";
                                        }
                                        $("#notification_list_header").append('<li><a href="' + url + '">' + item.notifications + '</a></li>');
                                    });
                                }
                            }
                        }
                    });
                });
                //call function to open corresponding tab
                explodeAndTrigerClick();
            });
            function showIntroPopup(video_url) {
                $('#introVideoModal .modal-body').html('<iframe width="560" height="315" src="' + video_url + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                $('#introVideoModal').modal('show');
                $("#introVideoModal").on('hidden.bs.modal', function (e) {
                    $("#introVideoModal iframe").attr("src", $("#introVideoModal iframe").attr("src"));
                });
                var userID = '{{ auth()->user()->id }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ url("intro_video_status_change") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID},
                    success: function (data) {

                    }
                });
            }
            function notDelaySuccess() {
                var userID = '{{ auth()->user()->id }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ url("user_not_count") }}',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {"user_id": userID},
                    success: function (data) {
                        if (data.data.success) {
                            $('.notCountShow').html(data.data.not_count);
                            $('.msgCountShow').html(data.data.msg_count);
                        }
                    }
                });
            }
            explodeAndTrigerClick = () => {
                let currentURL = window.location.href;
                let urlData = currentURL.split("#");
                if (urlData[1]) {
                    let tabIDdata = urlData[1] + "_tab";
                    document.getElementById(tabIDdata).click();
                }
            }
        </script>
        @yield('style')
    </body>
</html>