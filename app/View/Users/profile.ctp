<?php
	echo $this->Html->css('profile');
    $this->assign('title','My Profile');
    echo $this->element('nav');
?>
<div class="container">
    <div class="row" style="margin-top: 20px">
        <!--<div class="col-md-12 text-center ">-->
            <!--<div class="panel panel-default">-->
                <!--<div class="userprofile social ">-->
                    <!--<div class="userpic"> <img src="<?php echo $userData['User']['avatar']  ?>" alt="" class="userpicimg"> </div>-->
                    <!--<h2 class="username">-->
                        <!--<?php-->
                            <!--echo $userData['User']['name'];-->
                        <!--?>-->
                    <!--</h2>-->
                    <!--<p>-->
                        <!--<?php-->
                            <!--echo $userData['User']['email'];-->
                        <!--?>-->
                    <!--</p>-->
                <!--</div>-->
                <!--<div class="clearfix"></div>-->
            <!--</div>-->
        <!--</div>-->
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
                                    <div class="h5 m-0"><span class="fa fa-user-circle-o "></span> My Profile</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="display: none">
                                <?php
                                    echo $this->Form->Input("id", array(
                                                "value" => $userData['User']['id'],
                                                ));
                                ?>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("name", array(
                                                "value" => $userData['User']['name'],
                                                "type" => "text",
                                                "class" => "inputText",
                                                "disabled" => "disabled"
                                                ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("email", array(
                                        "value" => $userData['User']['email'],
                                        "type" => "text",
                                        "class" => "inputText",
                                        "disabled" => "disabled"
                                        ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("birthday", array(
                                        "value" => $userData['User']['birthday'],
                                        "type" => "text",
                                        "class" => "inputText"
                                        ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("address", array(
                                        "value" => $userData['User']['address'],
                                        "type" => "text",
                                        "class" => "inputText"
                                        ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("country", array(
                                        "value" => $userData['User']['country'],
                                        "type" => "text",
                                        "class" => "inputText"
                                        ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("description", array(
                                        "value" => $userData['User']['description'],
                                        "type" => "textarea",
                                        "rows" => "3",
                                        "class" => "inputText"
                                        ));
                                ?>
                                <div class="line"></div>
                            </li>
                            <li class="list-group-item">
                                <?php
                                    echo $this->Form->Input("day_off_left", array(
                                        "value" => $userData['User']['day_off_left'],
                                        "type" => "text",
                                        "class" => "inputText",
                                        "disabled" => "disabled"
                                        ));
                                ?>
                                <div class="line"></div>
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
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Off">Off</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Leave">Leave</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="Off" class="container tab-pane active"><br>
                    <?php
                        if(!empty($userData['Off'])) {
                            foreach ($userData['Off'] as $keyOff => $value) {
                                $status = 'off';
                                $time = 'on ' . $value['dates'];
                                ?>
                                <!--- \\\\\\\Post-->
                                <div class="card gedf-card" id="<?php echo $keyOff; ?>">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="mr-2">
                                                    <img class="rounded-circle" width="45" src="<?php echo $value['author']['avatar'] ?>" alt="">
                                                </div>
                                                <div class="ml-2">
                                                    <div class="h5 m-0"><?php echo $value['user_name'] ?></div>
                                                    <div class="h7 text-muted"><?php echo $value['user_name'] ?></div>
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
                                    <div class="card-body status" data="<?php echo $value['status']; ?>" style="padding-bottom: 1.25rem;padding-top: 1.25rem;padding-left: 1.25rem;padding-right: 0rem;">
                                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $value['post_at'] ?></div>
                                        <a class="card-link" href="#">
                                            <h5 class="card-title"><?php echo 'Asking for ' . $status . ' ' . $time; ?></h5>
                                        </a>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="card-text">
                                                    <?php echo 'Reason: ' . $value['reason']; ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3 offset-md-3" style="text-align: right">
                                                <span class="colorStatus" style="padding-right: 15px;"><?php echo $value['status']; ?></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Post /////-->
                                <?php
                            }
                        }

                    ?>
                </div>
                <div id="Leave" class="container tab-pane fade"><br>
                    <?php
                        if(!empty($userData['Leave'])) {
                            foreach ($userData['Leave'] as $keyLeave => $value) {
                                $status = $value['check'];
                                if ($status == 'leave') {
                                    $time = 'from ' . date('H:i',strtotime($value['start'])) . ' to ' . date('H:i',strtotime($value['end'])) . ' on ' . $value['date'];
                                } elseif ($status == 'leaving soon') {
                                    $time = 'at ' . date('H:i',strtotime($value['start'])) . ' on ' .$value['date'];
                                } else {
                                    $time = 'at ' . date('H:i',strtotime($value['end'])) . ' on ' .$value['date'];
                                }
                                ?>
                                <!--- \\\\\\\Post-->
                                <div class="card gedf-card" id="<?php echo $keyLeave; ?>">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="mr-2">
                                                    <img class="rounded-circle" width="45" src="<?php echo $value['author']['avatar'] ?>" alt="">
                                                </div>
                                                <div class="ml-2">
                                                    <div class="h5 m-0"><?php echo $value['user_name'] ?></div>
                                                    <div class="h7 text-muted"><?php echo $value['user_name'] ?></div>
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
                                    <div class="card-body status" data="<?php echo $value['status']; ?>" style="padding-bottom: 1.25rem;padding-top: 1.25rem;padding-left: 1.25rem;padding-right: 0rem;">
                                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $value['post_at'] ?></div>
                                        <a class="card-link" href="#">
                                            <h5 class="card-title"><?php echo 'Asking for ' . $status . ' ' . $time; ?></h5>
                                        </a>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="card-text">
                                                    <?php echo 'Reason: ' . $value['reason']; ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3 offset-md-3" style="text-align: right">
                                                <span class="colorStatus" style="padding-right: 15px;"><?php echo $value['status']; ?></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Post /////-->
                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        //profile

        $('.inputText').click(function(){
            $(this).parent().parent().find(".line").css("width","100%");
        });
        $(".inputText").focusout(function(){
            $(this).parent().parent().find(".line").css("width","0%");
        });

        $('.status').each(function(){
            if ($(this).attr("data") == 'WAITING') {
                // $(this).css("background-color","#fcf8e3");
                $(this).find(".colorStatus").addClass("text-warning");
            }
            if ($(this).attr("data") == 'APPROVED') {
                $(this).css("background-color","#dff0d8");
                $(this).find(".colorStatus").addClass("text-success");
            }
            if ($(this).attr("data") == 'DENY') {
                $(this).css("background-color","#f2dede");
                $(this).find(".colorStatus").addClass("text-danger");
            }
        });
    });
    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            // ajax call get data from server and append to the div
            alert('End page');
        }
    });
</script>