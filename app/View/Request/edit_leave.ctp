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
                        Edit Leave
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12" style="margin-top: 20px">
            <!--hdgfgkdjhgfkgh-->
            <div id="Leave" class="tab-pane"><br>
                <form method="post">
                    <input class="form-control" type="text" name="check" value="Leave" style="display: none">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Reason</label>
                        <div class="col-10">
                            <input class="form-control" name="reason" type="text" value="<?php echo $leave['Leave']['reason'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Emotion</label>
                        <div class="col-10">
                            <input class="form-control" name="emotion" type="text" value="<?php echo $leave['Leave']['emotion'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Date</label>
                        <div class="col-10">
                            <input class="form-control" name="date" type="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $leave['Leave']['date'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">From</label>
                        <div class="col-4">
                            <input class="form-control" name="start" type="time" value="<?php echo $leave['Leave']['start'] ?>">
                        </div>
                        <label class="col-1 offset-1 col-form-label">To</label>
                        <div class="col-4">
                            <input class="form-control" name="end" type="time" value="<?php echo $leave['Leave']['end'] ?>">
                        </div>
                    </div>
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