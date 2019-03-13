<?php
    $this->assign('title','Yasumi Network');
    echo $this->Html->script('accept');
    echo $this->element('nav');
?>
<div class="container-fluid gedf-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-3 gedf-main">

            <?php
                foreach ($noticeData as $key => $value) {
                    if ($value['info'] == 'off') {
                        $status = 'off';
                        $time = 'on ' . $value['dates'];
                        } else {
                        $status = $value['check'];
                        if ($status == 'leave') {
                        $time = 'from ' . date('H:i',strtotime($value['start'])) . ' to ' . date('H:i',strtotime($value['end'])) . ' on ' . $value['date'];
                        } elseif ($status == 'leaving soon') {
                        $time = 'at ' . date('H:i',strtotime($value['start'])) . ' on ' .$value['date'];
                    } else {
                        $time = 'at ' . date('H:i',strtotime($value['end'])) . ' on ' .$value['date'];
                    }
                }
            ?>
            <!--- \\\\\\\Post-->
            <div class="card gedf-card" id="<?php echo $key; ?>">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <a href="<?php if( $user_data['id'] == $value['user_id'] || $user_data['role'] == 1 || $user_data['role'] == 2){ echo '/chatwork/users/profile/'.$value['user_id'];}?>"><img class="rounded-circle" width="45" src="<?php echo $value['author']['avatar'] ?>" ></a>
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0"><?php echo $value['user_name']?><i style="font-size: 14px;"> - feeling <?php echo $value['emotion']?></i></div>
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
                                                    if($value['info'] == "off"){
                                                    ?>
                                    <div class="dropdown">
                                        <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                            <a class="dropdown-item edit" href="/chatwork/request/editOff/<?php echo $value['id'] ?>">Edit</a>
                                            <a class="dropdown-item delete" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Delete</a>
                                        </div>
                                    </div>
                                    <?php
                                                    }elseif($value['info'] == 'leave'){
                                                 ?>
                                    <div class="dropdown">
                                        <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                            <a class="dropdown-item edit" href="/chatwork/request/editLeave/<?php echo $value['id']?>">Edit</a>
                                            <a class="dropdown-item delete" data="<?php echo $value['id']?>" data-info="<?php echo $value['info']?>">Delete</a>
                                        </div>
                                    </div>
                                    <?php
                                        }}
                                    ?>
                        </div>
                    </div>
                </div>
                <div class="card-body status" data="<?php echo $value['status']; ?>" style="padding-bottom: 1.25rem;padding-top: 1.25rem;padding-left: 1.25rem;padding-right: 0rem;">
                    <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $value['post_at'] ?></div>
                    <a class="card-link" href="/chatwork/request/<?php echo $value['info']?>Detail/<?php echo $value['id']?>">
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
            ?>
        </div>
    </div>
</div>
