    <div class="container-fluid">
        <header class="row">
            <div class="col-sm-6 site-title">
                <a href="messages.php">
                    <img src="images/h2h-logo2.png" class="img-responsive">
                    <h1>Hand2Heart</h1>
                </a>
            </div>
            <div class="col-sm-6">
                <nav id="main-menu" class="navbar navbar-default">
				    	<ul id="nav" class="nav navbar-nav">
							<li>
							    <?php
							     if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "") {
							        echo "<a href='logout.php'>Logout</a>";
							     }
							     else {
							        echo "<a href='login.php'>Login</a>";
							     }
								?>
							</li>
									
				    	</ul><!-- /.nav navbar-nav-->
				</nav>
            </div>
        </header>
    </div>