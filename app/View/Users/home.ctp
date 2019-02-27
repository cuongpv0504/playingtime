<?php  
    $this->assign('title','Yasumi Network');
?>
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Yasumi</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fa fa-home"></i>
          Home
          <span class="sr-only">(current)</span>
          </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-envelope-o">
            <span class="badge badge-danger">11</span>
          </i>
          Add Request
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">
          <i class="fa fa-envelope-o">
            <span class="badge badge-warning">11</span>
          </i>
          Disabled
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-envelope-o">
            <span class="badge badge-primary">11</span>
          </i>
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav ">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-bell">
            <span class="badge badge-info">11</span>
          </i>
          Notice
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fa fa-globe">
            <span class="badge badge-success">11</span>
          </i>
          Profile
        </a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

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
                <div class="card-body">
                    <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i><?php echo $value['post_at'] ?></div>
                    <a class="card-link" href="#">
                        <h5 class="card-title"><?php echo 'Asking for ' . $status . ' ' . $time; ?></h5>
                    </a>

                    <p class="card-text">
                        <?php echo 'Reason: ' . $value['reason']; ?>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end status text-white"><?php echo $value['status']; ?></div>
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
        if ($(this).text() == 'WAITING') {
          $(this).parent().addClass('bg-warning');
        }
        if ($(this).text() == 'APPROVED') {
          $(this).parent().addClass('bg-success');
        }
        if ($(this).text() == 'DENY') {
          $(this).parent().addClass('bg-danger');
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

