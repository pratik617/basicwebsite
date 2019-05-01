<!DOCTYPE html>
<html>
<head>
	<title>RideApp Driver Registration</title>
</head>
<body>

<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
  <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
    <div style="padding: 40px; background: #fff;">
      <table style="width: 100%;" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td><b>Dear {{ $data['name'] }}</b>
              <p>This is to inform you that, Your account with RideApp Driver has been created successfully.</p>
              <p>Your Password : <b>{{ $password }}</b></p>
              <!-- <p>Note : OTP is valid for one time only.</p> -->
              <b>- Thanks (RideApp team)</b> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>