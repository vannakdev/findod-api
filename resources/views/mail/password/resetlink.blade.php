<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="stylesheet/style.css">
        <style type="text/css">
            @import url('https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700|Muli:400,600,700');


            .logo-sec {
                width: auto;
                margin: 0 auto;
                border: 1px solid rgba(204, 204, 204, 0.37);
                border-radius: 8px;
                max-width: 700px;
            }
            .back-logo {
                background-color: #1F7DCF;
                padding: 0px 15px 0px 15px;
                border-radius: 8px;
                text-align: center;
                height: 71px;
            }
            .back-logo img{
                height: 71px;
            }
            .sec-cont {
                border: 1px solid rgba(204, 204, 204, 0.37);
                width:  90%;
                margin:  0 auto;
                border-bottom-right-radius: 7px;
                border-bottom-left-radius: 7px;
                background:  #fff;
            }
            .sec-cont h5 {
                text-align: center;
                font-size: 23px;
                margin: 10px 0 10px 0;
                border-bottom: 1px solid rgba(204, 204, 204, 0.35);
                padding-bottom: 10px;
                color: #00458c;
                font-weight: 600;
            }
            .sec-cont div.cnt-outer {
                padding: 0 20px;
                display:  block;
            }
            .sec-cont ul {
                margin: 0;
                padding: 0;
            }
            .sec-cont b, .sec-cont h6 {
                color: #00458c;
                font-weight: 600;
                font-size: 15px;
                margin: 0px;
            }
            .sec-cont p {
                color: #00458c;
                font-weight: 500;
                font-size: 14px;
            }
            .sec-cont ul li {
                list-style: none;
                padding:  5px;
                color: #FF5722;
                font-weight: 500;
                font-size: 15px;
            }
            .sec-cont ul li.go-to {
                margin: 10px 0;
                text-align: center;
            }
            .sec-cont ul li.go-to a {
                text-decoration: none;
                color:  #fff;
                background:  #1F7DCF;
                padding:  9px 20px;
                border-radius:  5px;
                font-size: 14px;
            }
            .sec-cont i {
                text-align:  center;
                display:  block;
                color: #848383;
                font-style:  normal;
                margin: 40px 0;
                font-size: 14px;
            }
            .sec-cont ul.scl-icn li {
                display:  inline-block;
                text-align:  center;
            }
            .sec-cont ul.scl-icn li img {
                width:  30px;
                margin: 6px;
            }
            .sec-cont ul.scl-icn, .sec-cont ul.scl-login {
                text-align: center;
                margin: 0;
                padding: 0;
            }
            .sec-cont ul.scl-icn p, .sec-cont ul.scl-login p {
                color: #00458c;
                font-weight: 600;
                font-size: 16px;
                margin: 20px 0 0px 0;
            }
            p.cop-rit {
                text-align:  center;
                font-size: 14px;
                color: #848383;
                margin-top: 0;
                padding: 10px;
            }
            img.foot-imf {
                width: 100%;
                margin: 0 auto;
                text-align: center;
                display: block;
                border-bottom-left-radius: 6px;
                border-bottom-right-radius: 6px;
            }
            h1, h2, h3, h4, h5, h6, p, a, li {
                font-family: muli;
            }
            ul.scl-login li {
                display:  inline-block;
                margin-top: 10px;
            }
            ul.scl-login li img {
                width:  100px;
                height: 35px;
            }
            p.reset {
                overflow: hidden;
            }
            div ul li.middle-order {
                text-align:  center;
                color: #217ccf;
                position:  relative;
                width: 70%;
                margin: 0 auto;
            }
            li.middle-order:after {
                content:  "";
                border-bottom: 1px solid rgba(204, 204, 204, 0.50);
                width: 150px;
                height:  2px;
                display:  block;
                position:  absolute;
                right: 0px;
                top: 12px;
            }
            li.middle-order:before {
                content: "";
                border-bottom: 1px solid rgba(204, 204, 204, 0.50);
                width: 150px;
                height: 2px;
                display: block;
                position: absolute;
                left: 0px;
                top: 12px;
            }

        </style>
    </head>

    <body>
        <div class="container">
            <div class="logo-sec">
                <div class="back-logo">
                    <img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/Find-OD-logo.png")}}">
                </div>
                <div class="sec-cont">
                    <h5>Reset Password</h5>
                    <div class="cnt-outer">
                        <h6>Hey <b>{{ $name }},</b></h6>
                        <p>Forgot Your Password? No problem! just click the link below or copy and paste the adress in your browser to reset.</p>
                        <p>On the website, you will be able to enter and confirm your new password.</p>
                        <p class="reset">Reset Link: <a href="{{ $link }}" target="_blank">{{ $link }}</a></p>

                        <ul>
                            <li class="middle-order">OR</li>
                            <li class="go-to"><a href="{{ $link }}"​​​ target="_blank">Reset Password</a></li>

                        </ul>
                         <ul class="scl-login">
                            <p>Open FindOD App</p>
                            <li><a href="{{ $play_store_android }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/android.png")}}"></a></li>
                            <li><a href="{{ $app_store_ios }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/ios.png")}}"></a></li>
                        </ul>

                        <ul class="scl-icn">
                            <p>Touch With Us</p>
                            <li><a href="{{ $facebook_link }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/facebook.png")}}"></a></li>
                            <li><a href="{{ $social_google_plus_link }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/google.png")}}"></a></li>
                            <li><a href="{{ $social_twitter_link }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/twitter.png")}}"></a></li>
                            <li><a href="{{ $social_youtube_link }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/youtube.png")}}"></a></li>
                            <li><a href="{{ $social_linkedin_link }}" target="_blank"><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/linkedin.png")}}"></a></li>
                        </ul> 
                    </div>
                    <img class="foot-imf" src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/footer-final.jpg")}}">
                </div>
                <p class="cop-rit">@ <?php echo date('Y'); ?> FindOD. All Right Reserved | By : Ocean Delight Real Estate</p>
            </div>
        </div>
    </body>
</html>