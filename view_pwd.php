
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
$md_pwd = 'لم تنشر بعد من طرف المركز';
$sql = "SELECT * FROM gpw_users WHERE username= '".$_SESSION["username"]."'";
$result = mysqli_query($dbConnect, $sql);
if(!empty($result)){
    $row = mysqli_fetch_assoc($result);

    if (! empty($row)) {
        if (strlen($row['md_pwd']) > 5){
        $md_pwd = $row["md_pwd"];
        }
    }
}

if (count($_POST) > 0) {
    header('Location: notes.php');
    //die();

}
?>
<html>
<head>
<title>تغيير كلمة السر</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/form.css" />
<script>
function validatePassword() {
    return;
}
</script>
</head>
<body>
	<div class="phppot-container tile-container">
		<form name="frmChange" method="post" action=""
			onSubmit="return validatePassword()">

			<div class="validation-message text-center"><?php if(isset($message)) { echo $message; } ?></div>
			<h2 class="text-center">إسم المستخدم وكلمة السر للولوج الى الأرضية</h2>
			<div>
				<div class="row">
					<label class="inline-block">إسم االمستخدم</label> <span
						id="matricule" class="validation-message"></span> 
<!--                                                <input
						type="text" name="matricule" value="<?php echo $_SESSION["username"]; ?>" class="form-control " > -->
                    <input type="text" readonly class="form-control-plaintext" style="color:black;background-color:yellow"  id="nom" name="matricule" value="<?= $_SESSION['username']; ?>" maxlength="50" placeholder="">                                                
                                                

				</div>
				<div class="row">
					<label class="inline-block">كـلــــمة الســر</label> 
<!--                                        <span
						id="md_pwd" class="validation-message"></span><input
						type="text" name="md_pwd" class="full-width"> -->
<input type="text" readonly class="form-control-plaintext" style="color:black;background-color:yellow"  id="nom" name="matricule" value="<?= $md_pwd; ?>" maxlength="50" placeholder="">   

				</div>
				</div>
				<div class="row">
					<input type="submit" name="submit" value="خروج"
						class="full-width">
				</div>
			</div>

		</form>
	</div>
</body>
</html>