<nav class="navbar navbar-expand-sm navbar  bg-opacity-25 bg-dark">
        <div class="container">
            <a href="index.php" class="navbar navbar-brand text-light"><h3> Lovejoy </h3></a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php" class="nav-link text-light">Home</a>
                </li>
                <?php
                
                    if(isset($_SESSION['Email']) || isset($_COOKIE['email']))
                    {
                ?>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-light">Logout</a>
                    </li>
                <?php
                    if($_SESSION['UType']=='admin') {
                ?>
                    <li class="nav-item">
                        <a href="list.php" class="nav-link text-light">List of Requests</a>
                    </li>
                <?php
                    } else{
                ?>
                    <li class="nav-item">
                        <a href="request.php" class="nav-link text-light">Request Evaluation</a>
                    </li>
                <?php 
                    }
                    }
                    else
                    {
                ?>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link text-light">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link text-light">Register</a>
                    </li>
                <?php
                    }
                
                ?>
            </ul>
           
        </div>
    </nav>