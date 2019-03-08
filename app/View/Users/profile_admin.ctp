<?php
	echo $this->Html->css('profile');
$this->assign('title','My Profile');
echo $this->element('nav');
?>
<div class="container">
    <div class="row" style="margin-top: 20px">
        <div class="col-md-4 col-sm-12 pull-right">
            <div class="card gedf-card">
                <?php
			            echo $this->Form->create("User", array("type" => "file"));
                ?>
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <div class="h5 m-0"><img class="rounded-circle" width="45" src="<?php echo $userData['User']['avatar']?>" alt="">  My Profile</div>
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


        </div>
        <div class="col-md-8 col-sm-12 pull-left posttimeline">

            <?php
                foreach($userData['listUser'] as $key => $value){
                ?>
                <div class="row" style="border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;padding-top: 20px;padding-bottom: 20px; margin-bottom: 10px; background-color: #f8f9fa">
                    <div class="col-2" >
                        <img style="border-radius: 50%!important;" src="<?php echo $value['User']['avatar'] ?>" class="img-circle">
                    </div>

                    <div class="col-8">
                        <h5><?php echo $value['User']['name'] ?></h5>
                        <h7><?php echo $value['User']['email'] ?></h7>
                    </div>

                    <div class="col-2">
                        <div class="btn-group">
                            <a class="btn btn-info" href="/users/profile/<?php echo $value['User']['id'] ?>">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>
        </div>
        <div class="col-md-1"></div>
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
    });
</script>
<style>
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