<header>
    <?php
    $header ="1";
    if($header=="1"){
        ?>
        <div id="header2" class="header2-area">
            <div class="header-top-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="header-top-left">
                                <ul>
                                    <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Tel:+1234567890"> + 123 456 78910 </a></li>
                                    <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="#">info@academics.com</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="header-top-right">
                                <ul>
                                    <li>
                                        <a class="login-btn-area" href="#" id="login-button"><i class="fa fa-lock" aria-hidden="true"></i> Login</a>
                                        <div class="login-form" id="login-form" style="display: none;">
                                            <div class="title-default-left-bold">Login</div>
                                            <form>
                                                <label>Username or email address *</label>
                                                <input type="text" placeholder="Name or E-mail" />
                                                <label>Password *</label>
                                                <input type="password" placeholder="Password" />
                                                <label class="check">Lost your password?</label>
                                                <span><input type="checkbox" name="remember"/>Remember Me</span>
                                                <button class="default-big-btn" type="submit" value="Login">Login</button>
                                                <button class="default-big-btn form-cancel" type="submit" value="">Cancel</button>
                                            </form>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="apply-btn-area">
                                            <a href="apply.php" class="apply-now-btn">Apply Now</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-menu-area bg-textPrimary" id="sticker">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="logo-area">
                                <a href="index.php"><img class="img-responsive" src="img/logo-primary.png" alt="logo" height="20px"></a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <nav id="desktop-nav">
                                <ul>
                                    <li class="active"><a href="index.php">Home</a>

                                    </li>
                                    <li><a href="about.php">About Us</a>

                                    </li>
                                    <li><a href="history.php">Our History</a>

                                    </li>
                                    <li><a href="#">Courses</a>

                                    </li>

                                    <li><a href="#">Admission</a>

                                    </li>

                                    <li><a href="#">Contact us</a>

                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php }else{  ?>
        <div id="header1" class="header1-area">
            <div class="main-menu-area bg-primary" id="sticker">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="logo-area">
                                <a href="index.php"><img class="img-responsive" src="img/logo-primary.png" alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-9">
                            <nav id="desktop-nav">
                                <ul>
                                    <li class="active"><a href="index.php">Home</a>

                                    </li>
                                    <li><a href="about.php">About Us</a>

                                    </li>
                                    <li><a href="history.php">Our History</a>

                                    </li>
                                    <li><a href="#">Courses</a>

                                    </li>

                                    <li><a href="#">Admission</a>

                                    </li>

                                    <li><a href="#">Contact us</a>

                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-2 col-md-2 hidden-sm">
                            <div class="apply-btn-area">
                                <a href="apply.php" class="apply-now-btn">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Mobile Menu Area Start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul>
                                <li class="active"><a href="index.php">Home</a>

                                </li>
                                <li><a href="about.php">About Us</a>

                                </li>
                                <li><a href="#">Our History</a>

                                </li>
                                <li><a href="#">Courses</a>

                                </li>

                                <li><a href="#">Admission</a>

                                </li>

                                <li><a href="#">Contact us</a>

                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End -->
</header>
