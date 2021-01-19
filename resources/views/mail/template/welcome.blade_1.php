<!DOCTYPE html>
<!-- saved from url=(0051)http://192.168.80.51/mailtemplate/mailtemplate.html -->
<html class="gr__192_168_80_51"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700|Muli:400,600,700');


.logo-sec {
   width: auto;
    margin: 0 auto;
    border: 1px solid #ccc;
    border-radius: 8px;
    max-width: 700px;
}
.back-logo {
    background-color: #1F7DCF;
    padding: 0px 15px 15px 15px;
    border-radius: 8px;
    text-align: center;
}
.logo-sec img{
    text-align: center;
}
.sec-cont {
    border: 1px solid rgba(204, 204, 204, 0.37);
    width:  90%;
    margin:  0 auto;
    border-radius:  7px;
    margin-top: -20px;
    background:  #fff;
}
.sec-cont h5 {
    text-align: center;
    font-size: 23px;
    margin: 10px 0 10px 0;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    color: #00458c;
    font-weight: 600;
}
.sec-cont div.cnt-outer {
    padding: 0 20px;
    display:  block;
    margin-bottom: 20px;
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
  font-family: Muli,Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
}
body, td, input, textarea, select {
  font-family: arial,sans-serif;
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

<body data-gr-c-s-loaded="true">
    <div class="container">
        <div class="logo-sec">
            <div class="back-logo">
                <img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/Find-OD-logo.png")}}">
            </div>
            <div class="sec-cont">
                <h5>Welcome to FindOD</h5>
                <div class="cnt-outer">
                    <h6>Hey <b>,{{ $userName }}</b></h6>
                    <p>Thanks for Registering with us.  We will service you and provide perfect features to find Property in Cambodia Easier  for your Dream Home</p>
                    <p>we have created new account for you to make use of seamless services and support.</p>
                    <ul>
                        <!-- <li>Username: Helloexample@gmail.com</li>
                        <li>Password: 1234567890</li> -->
                        <li class="go-to"><a href="http://192.168.80.51/mailtemplate/mailtemplate.html#">Go To FindOD</a></li>
                    </ul>
                    <p>Find OD takes your account security very seriously and we make sure you'll get emails that you choose service receive from Find OD</p>
                <!-- <i>If you did not sign up for this account Please Ignore this email and the account will be deleted</i> -->

                    <ul class="scl-login">
                        <p>Open FindOD App</p>
                        <li><a href="http://192.168.80.51/mailtemplate/mailtemplate.html#">
                                <img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/android.png")}}"></a></li>
                        <li><a href="http://192.168.80.51/mailtemplate/mailtemplate.html#">
                                <img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/ios.png")}}"></a></li>
                    </ul>
                    <ul class="scl-icn">
                        <p>Touch With Us</p>
                        <li><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/facebook.png")}}"></li>
                        <li><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/google.png")}}"></li>
                        <li><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/twitter.png")}}"></li>
                        <li><img src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/youtube.png")}}"></li>
                    </ul>
                </div>
                 <img class="foot-imf" src="{{ $message->embed(env('APP_URL')."uploads/template/mailtemplate_files/footer-final.jpg")}}">
            </div>
            <p class="cop-rit">@ 2018 FindOD. All Right Reserved | By : Ocean Delight Real Estate</p>
        </div>
    </div>