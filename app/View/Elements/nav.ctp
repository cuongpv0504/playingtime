<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Yasumi</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/users/home">
                    <i class="fa fa-home"></i>
                    Home
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/request">
                    <i class="fa fa-envelope-o">
                        <span class="badge badge-danger">11</span>
                    </i>
                    Add Request
                </a>
            </li>

        </ul>
        <?php
            if($user_data['role'] == 1 || $user_data['role'] == 2){
            ?>
            <form class="form-inline" method="post" action="/users/search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search"  aria-label="Recipient's username" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <?php
                }
            ?>


        <ul class="navbar-nav ">
            <li class="nav-item">
                <a class="nav-link" href="/users/notice">
                    <i class="fa fa-bell">
                        <span class="badge badge-info"><?php echo $user_data['notice'];?></span>
                    </i>
                    Notice
                </a>
            </li>
            <li class="nav-item dropdown" style="padding-top: 10px">
                <div class="d-flex justify-content-between align-items-center">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle" width="45" src="<?php echo $user_data['avatar'] ?>" alt="">
                            <?php echo $user_data['name']; ?>
                        </a>

                        <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>-->
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                            if($user_data['role'] == 1){
                            ?>
                                <a class="dropdown-item" href="/users/profileAdmin">Profile</a>
                            <?php
                            }else{
                            ?>
                                <a class="dropdown-item" href="/users/profile">Profile</a>
                            <?php
                            }
                        ?>

                        <a class="dropdown-item" href="/users/logout">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
<style>
    .dropdown-menu {
       left: -25px;
        /*width: 10px;*/
    }
    .bg-dark {
        background: url(https://asqblog.files.wordpress.com/2018/08/network-3357642_1280.jpg?w=1280) no-repeat top center;
        background-size: 100%;
    }
</style>
<script>
    $(document).ready(function(){
        $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop( true, true ).slideDown("fast");
                $(this).toggleClass('open');
            },
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop( true, true ).slideUp("fast");
                $(this).toggleClass('open');
            }
        );
    });
</script>