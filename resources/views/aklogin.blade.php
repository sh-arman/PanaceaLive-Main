<html>
<head>
    <title>Test site for AK</title>

</head>
<body>
<form method="get" action="https://www.accountkit.com/v1.0/basic/dialog/sms_login/">
    <input type="hidden" name="app_id" value="513931092299311">
    <input type="hidden" name="redirect" value="https://www.panacea.live/accountkitresponse">
    <input type="hidden" name="state" value="{{ csrf_token() }}">
    <input type="hidden" name="fbAppEventsEnabled" value=true>
    <input type="hidden" name="country_code" value="880">
    <input type="hidden" name="debug" value="true">
    <input type="number" name="phone_number" value="1671066000">

    <button type="submit">Login</button>
</form>

</body>
</html>