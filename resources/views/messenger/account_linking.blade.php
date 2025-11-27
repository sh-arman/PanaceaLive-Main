<link rel="stylesheet" href="{{ asset('mobile/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('mobile/css/style.css') }}">

<div class="full-height-white">
        <div class="container container-login">

<h4>Login</h4>
<form method="post" action="{{ route('messengerLinkPost') }}">
    <input type="hidden" name="redirectURL" value="<?=$redirectURL?>">
    <input type="hidden" name="linking_token" value="<?=$linking_token?>">
    <input type="tel" name="phone_number" class="form-control" placeholder="Mobile No.">
    <input type="password" name="password" class="form-control" placeholder="Password" style="margin:10px 0;">
    <div class="btn-group btn-group-justified" role="group">
        <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Login</button>
        </div>
    </div>
</form>
            </div>
        </div>
