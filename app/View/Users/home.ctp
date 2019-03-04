<?php  
    $this->assign('title','Yasumi Network');
    echo $this->element('nav');
?>

<div class="container-fluid gedf-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-3 gedf-main">

            <?php 
                foreach ($data as $key => $value) {
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
                                <img class="rounded-circle" width="45" src="<?php echo $value['author']['avatar'] ?>" alt="">
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0"><?php echo $value['user_name'] ?></div>
                                <div class="h7 text-muted"><?php echo $value['author']['email'] ?></div>
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
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      // $(".card").click(function(){
      //   window.location.replace("http://192.168.0.22/chatwork");
      // });
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

