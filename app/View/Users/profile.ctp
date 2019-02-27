<?php
	echo $this->Html->css('profile');
?>

<?php
    $this->assign('title','My Profile');
?>
<?php
    echo $this->element('nav');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center ">
            <div class="panel panel-default">
                <div class="userprofile social ">
                    <div class="userpic"> <img src="<?php echo $userData['User']['avatar']  ?>" alt="" class="userpicimg"> </div>
                    <h2 class="username">
                        <?php
                            echo $userData['User']['name'];
                        ?>
                    </h2>
                    <p>
                        <?php
                            echo $userData['User']['email'];
                        ?>
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.col-md-12 -->
        <div class="col-md-4 col-sm-12 pull-right">
                <div class="card gedf-card">
                    <?php
			            echo $this->Form->create("User", array("type" => "file"));
                    ?>
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-2">
                                    <div class="h5 m-0">My Profile</div>
                                    <!--<h1 class="page-header small">Worked with many domain</h1>-->
                                    <!--<p class="page-subtitle small">Like to work fr different business</p>-->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("name", array(
                                                "value" => $userData['User']['name'],
                                                "type" => "text",
                                                "class" => "inputText"
                                                ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("email", array(
                                        "value" => $userData['User']['email'],
                                        "type" => "text"
                                        ));
                                ?>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("birthday", array(
                                        "value" => $userData['User']['birthday'],
                                        "type" => "text"
                                        ));
                                ?>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("address", array(
                                        "value" => $userData['User']['address'],
                                        "type" => "text"
                                        ));
                                ?>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("description", array(
                                        "value" => $userData['User']['description'],
                                        "type" => "text"
                                        ));
                                ?>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("day_off_left", array(
                                        "value" => $userData['User']['day_off_left'],
                                        "type" => "text"
                                        ));
                                        ?>
                            </li>
                        </ul>
                    </div>
                    <?php
				        echo $this->Form->end(array(
                            'label' => 'Save profile'
                            ));
                    ?>
                </div>

            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="ml-2">
                                <div class="h5 m-0">Worked with many domain</div>
                                <div class="h7 text-muted">Like to work fr different business</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-12 listfriend">
                    <div class="memberblock"> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="">
                        <div class="memmbername">Ajay Sriram</div>
                    </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <div class="memmbername">Rajesh Sriram</div>
                    </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <div class="memmbername">Manish Sriram</div>
                    </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <div class="memmbername">Chandra Amin</div>
                    </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <div class="memmbername">John Sriram</div>
                    </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <div class="memmbername">Lincoln Sriram</div>
                    </a> </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="col-md-8 col-sm-12 pull-left posttimeline">
            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <img class="rounded-circle" width="45" src="https://picsum.photos/50/50" alt="">
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0">@LeeCross</div>
                                <div class="h7 text-muted">Miracles Lee Cross</div>
                            </div>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                    <div class="h6 dropdown-header">Configuration</div>
                                    <a class="dropdown-item" href="#">Save</a>
                                    <a class="dropdown-item" href="#">Hide</a>
                                    <a class="dropdown-item" href="#">Report</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i>10 min ago</div>
                    <a class="card-link" href="#">
                        <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur adip.</h5>
                    </a>

                    <p class="card-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo recusandae nulla rem eos ipsa praesentium esse magnam nemo dolor
                        sequi fuga quia quaerat cum, obcaecati hic, molestias minima iste voluptates.
                    </p>
                </div>
            </div>
            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <img class="rounded-circle" width="45" src="https://picsum.photos/50/50" alt="">
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0">@LeeCross</div>
                                <div class="h7 text-muted">Miracles Lee Cross</div>
                            </div>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                    <div class="h6 dropdown-header">Configuration</div>
                                    <a class="dropdown-item" href="#">Save</a>
                                    <a class="dropdown-item" href="#">Hide</a>
                                    <a class="dropdown-item" href="#">Report</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i>10 min ago</div>
                    <a class="card-link" href="#">
                        <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur adip.</h5>
                    </a>

                    <p class="card-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo recusandae nulla rem eos ipsa praesentium esse magnam nemo dolor
                        sequi fuga quia quaerat cum, obcaecati hic, molestias minima iste voluptates.
                    </p>
                </div>
                <div class="card-footer">
                    <a href="#" class="card-link"><i class="fa fa-gittip"></i> Like</a>
                    <a href="#" class="card-link"><i class="fa fa-comment"></i> Comment</a>
                    <a href="#" class="card-link"><i class="fa fa-mail-forward"></i> Share</a>
                </div>
            </div>
            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <img class="rounded-circle" width="45" src="https://picsum.photos/50/50" alt="">
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0">@LeeCross</div>
                                <div class="h7 text-muted">Miracles Lee Cross</div>
                            </div>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                    <div class="h6 dropdown-header">Configuration</div>
                                    <a class="dropdown-item" href="#">Save</a>
                                    <a class="dropdown-item" href="#">Hide</a>
                                    <a class="dropdown-item" href="#">Report</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i>10 min ago</div>
                    <a class="card-link" href="#">
                        <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur adip.</h5>
                    </a>

                    <p class="card-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo recusandae nulla rem eos ipsa praesentium esse magnam nemo dolor
                        sequi fuga quia quaerat cum, obcaecati hic, molestias minima iste voluptates.
                    </p>
                </div>
                <div class="card-footer">
                    <a href="#" class="card-link"><i class="fa fa-gittip"></i> Like</a>
                    <a href="#" class="card-link"><i class="fa fa-comment"></i> Comment</a>
                    <a href="#" class="card-link"><i class="fa fa-mail-forward"></i> Share</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>