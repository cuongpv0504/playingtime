<?php
     $this->assign('title','Add Request');
echo $this->Html->script('request');
echo $this->element('nav');
?>

<div class="container" style="margin-top: 20px">
    <div class="card gedf-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mr-2">
                        Edit Off
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12" style="margin-top: 20px">
            <!--hdgfgkdjhgfkgh-->
            <div id="Off" class="tab-pane"><br>
                <form method="post">
                    <input class="form-control" type="text" name="check" value="Off" style="display: none">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Reason</label>
                        <div class="col-10">
                            <input class="form-control" name="reason" type="text" value="<?php echo $off['Off']['reason'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Emotion</label>
                        <div class="col-10">
                            <select class="form-control" name="emotion">
                                <option>happy</option>
                                <option>sad</option>
                                <option>confused</option>
                                <option>afraid</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Type</label>
                        <div class="col-10">
                            <input class="form-control" name="type" type="text" value="<?php echo $off['Type']['description'] ?>">
                        </div>
                    </div>
                    <?php
                        foreach($dates as $key=>$date):
                    ?>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Date</label>
                        <div class="col-6">
                            <input class="form-control" name="date[<?php echo $key; ?>]" type="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d',strtotime($date['0'])); ?>">
                        </div>
                        <label class="col-1 col-form-label">In</label>
                        <div class="col-2">
                            <select class="form-control" name="in[<?php echo $key; ?>]">
                                <?php if($date['1'] == 'ALL'){ ?>
                                <option selected>ALL</option>
                                <?php }else{?>
                                <option>ALL</option>
                                <?php }?>

                                <?php if($date['1'] == 'AM'){ ?>
                                <option selected>AM</option>
                                <?php }else{?>
                                <option>AM</option>
                                <?php }?>

                                <?php if($date['1'] == 'PM'){ ?>
                                <option selected>PM</option>
                                <?php }else{?>
                                <option>PM</option>
                                <?php }?>

                            </select>
                        </div>
                        <div class="col-1"><a class="remove"><span class="fa fa-remove" style="font-size:24px;color:red"></span></a></div>
                    </div>
                    <?php endforeach; ?>
                    <div id="insertDate">

                    </div>
                    <a class="text-info more">one more day</a>
                    <div class="form-group row">
                        <a class="col-md-1 offset-4 btn btn-danger" href="/chatwork/users/home">Cancel</a>
                        <button type="submit" class="col-md-1 offset-2 btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<style>
    .submit {
        width: 50%;
    }
    .title {
        display: none;
    }
</style>