<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMS Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group m-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <input type="text" name="sender" class="form-control" id = "sender" placeholder="enter your id here">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <textarea name="phone_number" class="form-control" id="phone" placeholder="enter phone numbers"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <textarea name="message" class="form-control" placeholder="enter your message" id="message"></textarea>
                                        <p>Total Charaters: <b id="character_counts">0</b> </p>
                                        <p>Total Pages: <b id="pages_counts">0</b> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <button type ="button" id="send_sms_btn" class="btn btn-primary">Process SMS</button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center" id="display_error_message"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/sms.js')}}"></script>
</body>

</html>