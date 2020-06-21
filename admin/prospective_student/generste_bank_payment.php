<?php
error_reporting(0);
session_start(); 

if(!isset($_SESSION['reg_id'])){
    header("Location:../index.php");
}
?>

<div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-xs-12 col-sm-4 custom_left">
                <h5 class="card-title mb-1"><p>Acceptance payment</p></h5>
            </div>
        </div>
    </div>
    <br>
<div class="card-body">
<div class="row">
<div class="col-md-8">
    <form name="form1" id="form1" onsubmit="return false">
    <input type="hidden" name="vcode" value="<?php echo($_SESSION['vcode']); ?>">
    <table>

        <tr>
            <td class="ui-helper-center">
                The Acceptance form costs NGN10,000 (Ten Thousand  hundred Naira only)<br/>
                Please note that transaction fees may apply<br/>

                <!--                                                To pay Online now via the application portal, click on 'Online Payment' below.-->
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <!--                                                  <input type="submit" value="Online Payment" class="btn btn-default btn-block commonBtn"  name="subbtn" onclick="javascript:getpage('online_payment.php','mainContent');" >-->
                <!-- <a href="javascript:void(0);" class="commonBtn" onclick="javascript: getpage('online_payment.php','mainContent');">Online Payment</a> -->
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <br>
                <strong> Proccedure for making payment</strong><br>
                <ul>
                    <li>  Click "generate Payment Code" below to generate Payment RRR 
                    </li>
                    <li>  Procceed to any Nigerian Bank and request for payment Via Remitta
                    </li>
                    <li>  Fill the information provided: make sure you fill your <strong>RRR</strong> generated from the portal
                    </li>
                    <li>   Make Payment and return to Continue Application</li></ul><br>
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <?php if($_SESSION['rrr_acceptance'] == null) {?>
                <input type="submit" value="Generate RRR Code" class="btn btn-lg btn-info btn-block"  name="subbtn" onclick="javascript:generateRRR();" >
                <?php } else {?>
                    <div>
                    Your Generated RRR is: <?php echo($_SESSION['rrr_acceptance']);?>
                    </div>
                    <br />
                    <input type="submit" value="Click to Proceed to Remita Payment Gateway" class="btn btn-lg btn-success btn-block"  name="subbtn" onclick="javascript:window.location.href = 'https://login.remita.net/remita/onepage/biller/<?php echo($_SESSION['rrr_acceptance']);?>/payment.spa'" >
                <?php }?>
            </td>
        </tr>
        <td class="ui-helper-center">

            <!-- <br/> <input type="submit" value="Application Form" class="btn commonBtn"  name="subbtn" onclick="javascript:getpagephp('appication_from.php','mainContent','banks_payment');" > -->
        </td>
        </tr>


        <tr>
            <td class="ui-helper-center">

                <br/>
            </td>
        </tr>

    </table>

</form>
</div>
</div>
</div>

</div>
