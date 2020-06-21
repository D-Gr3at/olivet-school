

<div class="sidebar">

    <div class="sidebar-box">
        <div class="sidebar-box-inner">

            <h3 class="sidebar-title">Login</h3>
            <form name="form1" id="form1" onsubmit="return false">
                <div class="form-group">
                    <input type="text" placeholder="Your Email" id="loginemail" name="loginemail" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Your Password" id="loginpass" name="loginpass" class="form-control">
                </div>
                <!-- <button class="btn btn-default btn-block commonBtn" type="submit">Login</button> -->
                <div class="myerror" id="error_label_login" style="display:none;"></div>
                <button class="sidebar-search-btn disabled" type="submit" value="Login" name="subbtn" onclick="javascript:applylogin('apply_now_login')">Login</button>

            </form>

        </div>
    </div>

    <div class="sidebar-box">
        <div class="sidebar-box-inner">
            <h3 class="sidebar-title">Payment Status</h3>

            <p>
                Enter your RRR
            </p>
            <form method="post" name="form1" onsubmit="event.preventDefault();">
                <div class="form-group">
                    <input type="text" placeholder="RRR" id="checkrrr" name="checkrrr" class="form-control">
                </div>
                <div id="checkrr" name="checkrr"> </div>
                <button class="sidebar-search-btn disabled" type="submit" value="Check" name="Check" onclick="javascript:getRRRStaus('getrrr')"  >Check</button>


            </form>
        </div>
    </div>
</div><!--end sidebar-->
