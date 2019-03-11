<?php
    $this->assign('title','Yasumi Network');
    echo $this->element('nav');
?>
<div class="container" style="margin-top: 20px">
    <?php
        foreach($searchData as $key => $value){
        ?>
        <div class="row col-9 offset-1" style="border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;padding-top: 20px;padding-bottom: 20px; margin-bottom: 10px; background-color: #f8f9fa">
            <div class="col-2" >
                <img style="border-radius: 50%!important;" src="<?php echo $value['User']['avatar'] ?>" class="img-circle">
            </div>

            <div class="col-8">
                <h5><?php echo $value['User']['name'] ?></h5>
                <h7><?php echo $value['User']['email'] ?></h7>
            </div>

            <div class="col-2">
                <div class="btn-group">
                    <a class="btn btn-info" href="/chatwork/users/profile/<?php echo $value['User']['id'] ?>">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        <?php
        }
    ?>
</div>