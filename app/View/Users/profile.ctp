<?php
	echo $this->Html->css('profile');
    $this->assign('title','My Profile');
    echo $this->Html->script('accept');
    echo $this->element('nav');
?>
<div class="container-fluid">
    <div class="row" style="margin-top: 20px">
        <div class="col-md-2 col-sm-12 offset-md-1 pull-right">
            <div class="card gedf-card">
                <?php
			            echo $this->Form->create("User", array("type" => "file"));
                ?>
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <div class="h5 m-0"><img class="rounded-circle" width="45" src="<?php echo $userData['User']['avatar']?>" alt=""> <?php echo $userData['User']['name']; ?></div>
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
                            "type" => "textarea",
                            "rows" => "3",
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
                            "value" => 'Day off left: '. $userData['User']['day_off_left'],
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
            <?php
                if($user_data['role'] == 2 || $user_data['role'] == 1){
                ?>
                    <div class="card gedf-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="ml-2">
                                        <div class="h5 m-0">List users</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 listfriend">
                            <div class="memberblock">
                            <?php
                                foreach($userData['listUser'] as $list){
                                ?>
                                <a href="/users/profile/<?php echo $list['User']['id']?>" class="member"> <img class="img<?php echo $list['User']['id']?>" src="<?php echo $list['User']['avatar']?>" alt="">
                                    <div class="memmbername"><?php echo $list['User']['name']?></div>
                                </a>
                                <?php
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
            ?>
        </div>
        <div class="col-md-8 col-sm-12 pull-left posttimeline">
            <h4 style="margin-bottom: 10px; color: #212529"><i class="fa fa-history" aria-hidden="true"></i>  History</h4>
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Off">Off</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Leave">Leave</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Statistic">Statistic</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="Off" class="tab-pane active"><br>
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
                                                    <div class="h5 m-0"><?php echo $value['user_name'] ?><i style="font-size: 14px;"> - feeling <?php echo $value['emotion']?></i></div>
                                                    <div class="h7 text-muted"><?php echo $value['author']['email'] ?></div>
                                                </div>
                                            </div>
                                            <div>
                                                <?php
                                                   if(isset($user_data['role']) && $user_data['role'] == 1){
                                                        if($value['status'] == 'WAITING'){
                                                        ?>
                                                            <div class="dropdown">
                                                                <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-h"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                                                    <a class="dropdown-item accept" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Accept</a>
                                                                    <a class="dropdown-item denny" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Denny</a>
                                                                </div>
                                                            </div>
                                                            <?php
                                                         }
                                                         ?>
                                                            <?php
                                                     }else{
                                                        ?>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-h"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                                                <a class="dropdown-item edit" href="/chatwork/request/editOff/<?php echo $value['id'] ?>">Edit</a>
                                                                <a class="dropdown-item delete" data="<?php echo $value['id']?>" data-info="off">Delete</a>
                                                            </div>
                                                        </div>
                                                            <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body status" data="<?php echo $value['status']; ?>" style="padding-bottom: 1.25rem;padding-top: 1.25rem;padding-left: 1.25rem;padding-right: 0rem;">
                                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $value['post_at'] ?></div>
                                        <a class="card-link" href="#">
                                            <h5 class="card-title"><?php echo 'Asking for ' . $status . ' ' . $time; ?></h5>
                                        </a>
                                        <p class="card-text" style="margin-bottom: 10px;">
                                            <?php echo 'Type: ' . $value['type']; ?>
                                        </p>
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
                <div id="Leave" class="tab-pane fade"><br>
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
                                                    <div class="h5 m-0"><?php echo $value['user_name'] ?><i style="font-size: 14px;"> - feeling <?php echo $value['emotion']?></i></div>
                                                    <div class="h7 text-muted"><?php echo $value['author']['email'] ?></div>
                                                </div>
                                            </div>
                                            <div>
                                                <?php
                                                   if(isset($user_data['role']) && $user_data['role'] == 1){
                                                        if($value['status'] == 'WAITING'){
                                                        ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-h"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                                            <a class="dropdown-item accept" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Accept</a>
                                                            <a class="dropdown-item denny" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Denny</a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                             }
                                                             ?>
                                                    <?php
                                                         }else{
                                                            ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-h"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                                            <a class="dropdown-item edit" href="/chatwork/request/editLeave/<?php echo $value['id']?>">Edit</a>
                                                            <a class="dropdown-item delete" data="<?php echo $value['id']?>" data-info="leave">Delete</a>
                                                        </div>
                                                    </div>
                                                <?php
                                                    }
                                                ?>
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
                <div id="Statistic" class="tab-pane fade">
                    <?php echo $this->Html->link('Download', '/word/downloadDocument/'.$userData['User']['id'], array('class' => 'btn btn-primary download')); ?>
                    <div class="container" style="margin-bottom: 10px; margin-top: 10px">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="http://www.tmh-techlab.vn/img/common/OGP_img.jpg" alt="" style="width: 90%">
                            </div>
                            <div class="col-md-8" style="text-align: center; margin-top: 40px">
                                <h4>LEAVE REQUEST FORM</h4>
                                <h6>ĐƠN XIN NGHỈ</h6>
                            </div>
                        </div>
                    </div>
                        <table class="table table-bordered">
                            <tr class="title">
                                <!--<th colspan="7" class="month-head month">-->
                                    <!--<div class="container-fluid">-->
            <!---->
                                    <!--</div>-->
                                <!--</th>-->
                                <td colspan="2">
                                    <p>Division</p>
                                    <p>(Bộ phận)</p>
                                </td>
                                <td colspan="2">
                                    <p>Team</p>
                                    <p>(Tổ, nhóm)</p>
                                </td>
                                <td colspan="2">
                                    <p>Postion</p>
                                    <p>(Chức vụ)</p>
                                </td>
                                <td colspan="2">
                                    <p>Full name</p>
                                    <p>(Họ tên)</p>
                                </td>
                                <td>
                                    <p>Enabale Annual Leave</p>
                                    <p>(Số phép năm)</p>
                                </td>
                            </tr>
                            <tr class="title">
                                <td colspan="2">
                                    <p>Engineer</p>
                                </td>
                                <td colspan="2">
                                    <p> </p>
                                </td>
                                <td colspan="2">
                                    <p>Staff</p>
                                </td>
                                <th colspan="2">
                                    <p><?php echo $userData['User']['email']?></p>
                                </th>
                                <th>
                                    <p>12</p>
                                </th>
                            </tr>
                            <tr class="title">
                                <td rowspan="2">
                                    <p>Date of request</p>
                                    <p>(Ngày viết đơn)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Date of leave</p>
                                    <p>(Ngày xin nghỉ)</p>
                                </td>
                                <td colspan="2">
                                    <p>Annual Leave</p>
                                    <p>(Phép năm)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Orther leave</p>
                                    <p>(Số ngày nghỉ ngoài phép)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Type of orther leave</p>
                                    <p>(Hình thức nghỉ ngoài phép)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Reason</p>
                                    <p>(Lý do nghỉ)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Signature of employee</p>
                                    <p>(Chữ ký nhân viên)</p>
                                </td>
                                <td rowspan="2">
                                    <p>Approval Authority</p>
                                    <p>(Phê duyệt)</p>
                                </td>
                            </tr>
                            <tr class="title">
                                <td>
                                    <p>No of AL day</p>
                                    <p>(Số ngày nghỉ)</p>
                                </td>
                                <td>
                                    <p>No of AL day remain</p>
                                    <p>(Số ngày còn lại)</p>
                                </td>
                            </tr>
                            <?php
                                if(!empty($userData['Off'])) {
                                    foreach ($userData['Off'] as $key => $value) {
                                    ?>
                                    <tr id="<?php echo $key; ?>">
                                        <td style="width: 10%;">
                                            <?php echo date( 'Y-m-d', strtotime( $value['create_at'] ) )?>
                                        </td>
                                        <td>
                                            <?php echo $value['dates'] ?>
                                        </td>
                                        <td>
                                            <?php if($value['type_id'] == 0){
                                                echo $value['duration'];
                                            }else{
                                                echo "";
                                            }?>
                                        </td>
                                        <td>
                                            <?php if($value['type_id'] == 0){
                                                echo $value['day_left'];
                                            }else{
                                                echo "";
                                            }?>
                                        </td>
                                        <td>
                                            <?php if($value['type_id'] != 0){
                                                echo $value['duration'];
                                            }else{
                                                echo "";
                                            }?>
                                        </td>
                                        <td>
                                            <?php if($value['type_id'] != 0){
                                                echo $value['type'];
                                            }else{
                                                echo "";
                                            }?>
                                        </td>

                                        <td>
                                            <?php echo $value['reason'] ?>
                                        </td>
                                        <td>
                                            <?php echo $value['author']['name'] ?>
                                        </td>
                                        <td>
                                            <?php if($value['status'] === 'APPROVED'){
                                                ?>
                                                    <span class="fa fa-check text-success"></span>
                                                <?php
                                            }?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                }
                            ?>
                        </table>


                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>


</div>
</div>
<style>
    .download {
        float: right;
    }
    .table-bordered {
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .table-bordered .title {
        background-color: #f8f9fa;
    }
    .table td, .table th {
        padding: 5px;
        text-align: center;
    }
    .table-bordered tr {
        background-color: #fff;
    }
</style>