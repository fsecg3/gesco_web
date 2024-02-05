
<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}
include('inc/header.php');
$user = $users->getUserInfo();
$dbConnect = $database->dbConnect();
?>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>		

<?php
//session_start();
//$_SESSION["userId"] = "1";
//$conn = mysqli_connect("localhost", "root", "", "password_change");
if (count($_POST) > 0) {
    //header('Location: login.php');
    //die();
    $sql = "SELECT * FROM gpw_users WHERE username= '".$_SESSION["username"]."'";
    $result = mysqli_query($dbConnect, $sql);
    if(!empty($result)){
	    	
    $row = mysqli_fetch_assoc($result);

    if (! empty($row)) {
        $oldPassword = $row["password"];
        if ($oldPassword != MD5($_POST['currentPassword']) ){
            $message = "كلمة السر القديمة خاطئة";
        }
        else{
            $newPassword = MD5(htmlspecialchars($_POST["newPassword"]));
            if (strlen($_POST["newPassword"]) >= 5) {
                $updateQuery = "UPDATE gpw_users";
                $updateQuery .= " SET password = '".$newPassword."'";
                $updateQuery .= " WHERE username = '".$_SESSION['username']."'";
                    
                mysqli_query($dbConnect, $updateQuery);
                $message = "Mot de passe changé avec succès";
                header('Location: login.php');
                die();
            } else
                $message = "كلمة السر الجديدة قصيرة";
        }  
    }
    }
}
?>
<html>
<head>
<title>تغيير كلمة السر</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/form.css" />
<script>
function validatePassword() {
	var currentPassword, newPassword, confirmPassword, output = true;

	currentPassword = document.frmChange.currentPassword;
	newPassword = document.frmChange.newPassword;
	confirmPassword = document.frmChange.confirmPassword;

	if (!currentPassword.value) {
		currentPassword.focus();
		document.getElementById("currentPassword").innerHTML = "إجبارية";
		output = false;
	}
	else if (!newPassword.value) {
		newPassword.focus();
		document.getElementById("newPassword").innerHTML = "إجبارية";
		output = false;
	}
	else if (!confirmPassword.value) {
		confirmPassword.focus();
		document.getElementById("confirmPassword").innerHTML = "إجبارية";
		output = false;
	}
	if (newPassword.value != confirmPassword.value) {
		newPassword.value = "";
		confirmPassword.value = "";
		newPassword.focus();
		document.getElementById("confirmPassword").innerHTML = "غير متطابقة";
		output = false;
	}
	return output;
}
</script>
</head>
<body>
	<div class="phppot-container tile-container">
		<form name="frmChange" method="post" action=""
			onSubmit="return validatePassword()">

			<div class="validation-message text-center"><?php if(isset($message)) { echo $message; } ?></div>
			<h2 class="text-center">تغيير كلمة السر</h2>
			<div>
				<div class="row">
					<label class="inline-block">كلمة السر القديمة</label> <span
						id="currentPassword" class="validation-message"></span> <input
						type="password" name="currentPassword" class="full-width">

				</div>
				<div class="row">
					<label class="inline-block">كلمة السر الجديدة</label> <span
						id="newPassword" class="validation-message"></span><input
						type="password" name="newPassword" class="full-width">

				</div>
				<div class="row">
					<label class="inline-block">تأكيد كلمة السر الجديدة</label> <span
						id="confirmPassword" class="validation-message"></span><input
						type="password" name="confirmPassword" class="full-width">

				</div>
				<div class="row">
					<input type="submit" name="submit" value="حفظ"
						class="full-width">
				</div>
			</div>

		</form>
	</div>
</body>
</html>