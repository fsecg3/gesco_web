<?php 
include 'init.php'; 
if(!$users->isLoggedIn()) {
	header("Location: login.php");	
}

include('inc/header.php');
$user = $users->getUserInfo();
$etudiant = $users->getEtudiantInscription();
$fdv_choix1 = $notes->get_EtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 1);
$fdv_choix2 = $notes->get_EtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 2);
if($_SESSION['DB'] == 'FSPRI_WEB'){
    $fdv_choix3 = $notes->get_EtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 3);
    $fdv_choix4 = $notes->get_EtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 4);
}

$dbConnect = $database->dbConnect();
?>
<link rel="stylesheet" href="css/style.css" />
<?php include('inc/container.php');?>
<?php include('menus.php'); ?>		

<?php
//session_start();
//$_SESSION["userId"] = "1";
//$conn = mysqli_connect("localhost", "root", "", "password_change");

if(isset($_POST['submit'])){    
    if(isset($_POST['fdv_choix1']) && isset($_POST['fdv_choix2'])){
        $msg_error = "";
    }
        $fdv_choix1 = "";
        $fdv_choix2 = "";
        $fdv_choix3 = "";
        $fdv_choix4 = "";
        
        $fdv_choix1 = $_POST['fdv_choix1'];
        $fdv_choix2 = $_POST['fdv_choix2'];
        $choix = "<".$fdv_choix1.">";
        $pos      = strripos($choix, $fdv_choix2);
        if ($pos) {$msg_error .= "الإختيار الثاني مكرر";}
        $choix .= "<".$fdv_choix2.">";
        
        if($_SESSION['DB'] == 'FSPRI_WEB'){
            $fdv_choix3 = $_POST['fdv_choix3'];
            $fdv_choix4 = $_POST['fdv_choix4'];
            $pos      = strripos($choix, $fdv_choix3);
            if ($pos) {$msg_error .= "  الإختيار الثالث مكرر";}
            $choix .= "<".$fdv_choix3.">";

        $pos      = strripos($choix, $fdv_choix4);
        if ($pos) {$msg_error .= "  الإختيار الرابع مكرر";}
        $choix .= "<".$fdv_choix4.">";
            
        }
        

        if($msg_error == ''){
    
            $notes->saveEtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 1);
            $notes->saveEtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 2);
            if($_SESSION['DB'] == 'FSPRI_WEB'){
            
                $fdv_choix3 = $_POST['fdv_choix3'];
                $fdv_choix4 = $_POST['fdv_choix4'];

                $notes->saveEtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 3);
                $notes->saveEtudiantChoix($_SESSION['matricule'], $etudiant['par_code'],$etudiant['ann_code'], 4);
            }
                echo "<script>alert('الإختيارات محفوظة و يمكنك تعديلها قبل عملية التوجيه');</script>";

        }else{ 
                  echo "<script>alert('$msg_error');</script>";
 
        }
    }


?>
<html>
<head>
<title>إختيار التخصص المرغوب في السنة الثالثة</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/form.css" />
<script>
function validateChoix() {
                var choix1, choix2;
                var currentPassword, newPassword, confirmPassword, output = true;
                aler('validate');
	choix1 = document.frmChange.fdv_choix1;
	choix2 = document.frmChange.fdv_choix2;
                 alert(choix1 + '/' + choix2);
	

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
            <form name="frmChange" method="post" action="">
<!--			onSubmit="return validatePassword()"> -->
                                                        <h3 class="text-center">إختيار التخصص المرغوب في السنة الثالثة</h3>
			<div class="panel  panel-primary">
                                                            <div class="panel-heading" id="panel_name">اختر التخصصات بعناية ثم اضغط على الزر  حفظ</div>
                                                            <div class="panel-body" style="min-height: 2; max-height: 2;">
                                                                  <div class="form-group">
				<label for="type_recours" class="control-label">الإختيار الأول</label>
				<select id="fdv_choix1" name="fdv_choix1" class="form--control">
                                                                            <?php $notes->get_ListeChoix($etudiant['par_code'],$etudiant['ann_code'], $fdv_choix1); ?>
				</select>
                                                                   </div>
                                                                
                                                                  <div class="form-group">
				<label for="type_recours" class="control-label">الإختيار الثاني</label>
				<select id="fdv_choix2" name="fdv_choix2" class="form--control">
                                                                            <?php $notes->get_ListeChoix($etudiant['par_code'],$etudiant['ann_code'], $fdv_choix2); ?>
				</select>
                                                                   </div>
                                                                
                                                                   <?php if($_SESSION['DB'] != 'UFC_WEB'): ?> 
                                                        
                                                                  <div class="form-group">
				<label for="type_recours" class="control-label">الإختيار الثالث</label>
				<select id="fdv_choix3" name="fdv_choix3" class="form--control">
                                                                            <?php $notes->get_ListeChoix($etudiant['par_code'],$etudiant['ann_code'], $fdv_choix3); ?>
				</select>
                                                                   </div>
                                                                
                                                                  <div class="form-group">
				<label for="type_recours" class="control-label">الإختيار الرابع</label>
				<select id="fdv_choix4" name="fdv_choix4" class="form--control">
                                                                            <?php $notes->get_ListeChoix($etudiant['par_code'],$etudiant['ann_code'], $fdv_choix4); ?>
				</select>
                                                                   </div>
                                                                 <?php endif; ?> 
                              
                                                                <div class="form-group">
                                                                    <input type="submit" id="submit" name="submit" value="حفظ">
                                                                   </div>
                                                                
                                                            </div>
 <!--                                                   
			<div class="validation-message text-center"><?php if(isset($message)) { echo $message; } ?></div>
			
			<div>
				<div class="row">
					<label class="inline-block">الإختيار الأول</label> <span
						id="currentPassword" class="validation-message"></span> <input
						type="password" name="currentPassword" class="full-width">

				</div>
				<div class="row">
					<label class="inline-block">الإختيار الثاني</label> <span
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
-->
		</form>
	</div>
                    </div>
</body>
</html>
