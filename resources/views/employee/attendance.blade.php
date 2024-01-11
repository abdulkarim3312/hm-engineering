@extends('layouts.app')

@section('style')
    <style>
        .screenshot-image {
            width: 150px;
            height: 90px;
            border-radius: 4px;
            border: 2px solid whitesmoke;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
            position: absolute;
            bottom: 5px;
            left: 10px;
            background: white;
        }

        .display-cover {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 70%;
            margin: 5% auto;
            position: relative;
        }

        video {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
        }

        .video-options {
            position: absolute;
            left: 20px;
            top: 30px;
        }

        .controls {
            position: absolute;
            right: 20px;
            top: 20px;
            display: flex;
        }

        .controls > button {
            width: 45px;
            height: 45px;
            text-align: center;
            border-radius: 100%;
            margin: 0 6px;
            background: transparent;
        }

        .controls > button:hover svg {
            color: white !important;
        }

        @media (min-width: 300px) and (max-width: 400px) {
            .controls {
                flex-direction: column;
            }

            .controls button {
                margin: 5px 0 !important;
            }
        }

        .controls > button > svg {
            height: 20px;
            width: 18px;
            text-align: center;
            margin: 0 auto;
            padding: 0;
        }

        .controls button:nth-child(1) {
            border: 2px solid #D2002E;
        }

        .controls button:nth-child(1) svg {
            color: #D2002E;
        }

        .controls button:nth-child(2) {
            border: 2px solid #008496;
        }

        .controls button:nth-child(2) svg {
            color: #008496;
        }

        .controls button:nth-child(3) {
            border: 2px solid #00B541;
        }

        .controls button:nth-child(3) svg {
            color: #00B541;
        }

        .controls > button {
            width: 45px;
            height: 45px;
            text-align: center;
            border-radius: 100%;
            margin: 0 6px;
            background: transparent;
        }

        .controls > button:hover svg {
            color: white;
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
        <div class="container">
            <form method="post" action="{{ route('employees.attendance') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="employee_photo_src" name="employee_photo_src">
                <div class="row">

                <div class="col-md-6">
                    <video id="video" width="100%" height="350px" autoplay></video>
                </div>
                <div class="col-md-6">
                    <canvas id="canvas" width="100%" height="350px"></canvas>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary btn-sm" id="click-photo">Capture Photo</button>
                </div>
                <div class="col-md-6 submit-button" style="display: none">
                    <button class="btn btn-primary btn-sm">Submit</button>
                </div>
            </div>
            </form>
        </div>
    @endisset


@endsection
@section('script')
    <script type="text/javascript">
        let camera_button = document.querySelector("#start-camera");
        let video = document.querySelector("#video");
        let click_button = document.querySelector("#click-photo");
        let canvas = document.querySelector("#canvas");
        canvas.width  = 400;
        canvas.height = 350;
        captaure();
        async function captaure() {


            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream;
        }


        // camera_button.addEventListener('click', async function() {
        //    	captaure();
        // });

        click_button.addEventListener('click', function() {
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            let image_data_url = canvas.toDataURL('image/jpeg');

            $("#img_src").attr("src",image_data_url);
            $("#employee_photo").attr("src",image_data_url);
            $("#employee_photo_src").val(image_data_url);

            $(".submit-button").show();

            // data url of the image
            console.log(image_data_url);
        });
    </script>
@endsection

