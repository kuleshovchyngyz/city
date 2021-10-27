<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax Post Request Example</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="{{ asset('/js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
<div class="container">
    <h1>Laravel Ajax Post Request Example with <a href="https://codingdriver.com/">Coding Driver</a></h1>
    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
    <form id="ajaxform">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Enter Name" required="">
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
        </div>

        <div class="form-group">
            <strong>Mobile Number:</strong>
            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile" required="">
        </div>
        <div class="form-group">
            <strong>Message:</strong>
            <input type="text" name="message" class="form-control" placeholder="Enter Your Message" required="">
        </div>
        <div class="form-group">
            <button class="btn btn-success save-data">Save</button>
        </div>
    </form>
</div>
</body>
</html>
