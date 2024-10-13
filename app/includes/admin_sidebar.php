<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar">	

        <div class="user-profile">
            <div class="ulogo">
                <a href="index">
                    <!-- logo for regular state and mobile devices -->
                    <h3><b>Insecurity Reporting System</b></h3>
                </a>
            </div>
            <div class="profile-pic">
                <img src="../images/icon.png" alt="user">	
                <div class="profile-info"><h4><?php echo $userid; ?></h4>
                    <div class="list-icons-item dropdown">
                        <a href="#" class="list-icons-item dropdown-toggle btn-xs btn btn-primary text-white" data-toggle="dropdown"><span class="mr-2 badge badge-ring fill badge-warning"></span>Online</a>

                    </div>
                </div>
            </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">
            <?php
            if ($role == "user" && $role == "lecturer") {
                ?>
                
                
                <?php
            } elseif ($role == "admin") {
                ?>
                <li>
                    <a href="home">
                        <i class="ti-agenda"></i>
                        <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="manage-members">
                        <i class="ti-bolt"></i>
                        <span>Manage Reporters</span></a>
                </li>
                
                
               
                <?php
            }
            ?>


            <li>
                <a href="changepassword">
                    <i class="ti-lock"></i>
                    <span>Change Password</span></a>
            </li>
            <li>
                <a href="logout">
                    <i class="ti-power-off"></i>
                    <span>Log Out</span>
                </a>
            </li> 
        </ul>
    </section>
</aside>