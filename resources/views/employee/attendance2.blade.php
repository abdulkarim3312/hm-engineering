@extends('layouts.app')

@section('style')
    <style>
        .d-none {
            display: none;
        }
    </style>

@endsection

@section('title')
    Attendance
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
    @endif
    @isset($employees)
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <button type="button" id="btnActivateCamera">Activate Camera</button>
                        <button type="button" id="btnDeactivateCamera" class="d-none ">Deactivate Camera</button>
                        <br />
                        <video id="capturevideo" width="230" height="230" class=""></video>
                        <canvas id="capturecanvas" width="230" height="230" class="d-none"></canvas>
                        <img id="src_img" src="" width="230" height="230">

                        <br />
                        <button type="button" id="btnCapture" class="d-none">Capture</button>
                        <hr />

                    </div>
                </div>
            </div>
        </div>
    @endisset


@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var videoCapture;
        $(document).ready(function () {
            videoCapture = document.getElementById('capturevideo');
        });
        $(document).on('click', '#btnActivateCamera', function () {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                // access video stream from webcam
                navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                    // on success, stream it in video tag
                    window.localStream = stream;
                    videoCapture.srcObject = stream;
                    videoCapture.play();
                    activateCamera();

                }).catch(e => {
                    // on failure/error, alert message.
                    alert("Please Allow: Use Your Camera!");
                });
            }
        });
        $(document).on('click', '#btnDeactivateCamera', function () {
            // stop video streaming if any
            localStream.getTracks().forEach(function (track) {
                if (track.readyState == 'live' && track.kind === 'video') {
                    track.stop();
                    deactivateCamera();
                }
            });
        });
        $(document).on('click', '#btnCapture', function () {
            console.log(videoCapture);
            alert(videoCapture.srcObject);
            // $("#src_img").attr("src",videoCapture)
           document.getElementById('capturecanvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            screenshotImage.src = canvas.toDataURL('image/webp');
        });
        function activateCamera() {
            $("#btnActivateCamera").addClass("d-none");
            $("#btnDeactivateCamera").removeClass("d-none");
            $("#capturevideo").removeClass("d-none");
            $("#btnCapture").removeClass("d-none");
            $("#capturecanvas").removeClass("d-none");
        }
        function deactivateCamera() {
            $("#btnDeactivateCamera").addClass("d-none");
            $("#btnActivateCamera").removeClass("d-none");
            $("#capturevideo").addClass("d-none");
            $("#btnCapture").addClass("d-none");
            $("#capturecanvas").addClass("d-none");
        }

    </script>
@endsection

