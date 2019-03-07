<?php
     $this->assign('title','Add Request');
    echo $this->element('nav');
?>

<div class="container" style="margin-top: 20px">
    <div class="card gedf-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mr-2">
                        Add Request
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12" style="margin-top: 20px">
            <!--hdgfgkdjhgfkgh-->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Off">Off</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Leave">Leave</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <form name="Off" method="post">
                <div id="Off" class="tab-pane active"><br>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Reason</label>
                        <div class="col-10">
                            <select class="form-control" name="reason" data="off" onchange="reasonOff(this)">
                                <option>I feel not fine</option>
                                <option>I have private reason</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="otherOff" style="display: none">
                        <div class="col-10 offset-2" >
                            <input class="form-control" type="text" value="Other...">
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
                            <select class="form-control" name="type">
                                <?php
                                    foreach ($typeData as $type) {
                                    ?>
                                        <option id="<?php echo $type['Type']['id']; ?>" value="<?php echo $type['Type']['id']; ?>"><?php echo $type['Type']['description']; ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Date</label>
                        <div class="col-7">
                            <input class="form-control" name="date[1]" type="date" >
                        </div>
                        <label class="col-1 col-form-label">In</label>
                        <div class="col-2">
                            <select class="form-control" name="in[1]">
                                <option>ALL</option>
                                <option>AM</option>
                                <option>PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="insertDate">

                    </div>
                    <a class="text-info more">one more day</a>
                    <div class="form-group row">
                        <!--<button class="col-md-1 offset-4 btn btn-danger">Cancel</button>-->
                        <button type="submit" class="col-md-1 offset-2 btn btn-primary">Send</button>
                    </div>
                </div>
                </form>
                <div id="Leave" class="tab-pane fade"><br>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Reason</label>
                        <div class="col-10">
                            <select class="form-control" data="leave" onchange="reasonLeave(this)">
                                <option selected>Traffic jam</option>
                                <option>I feel not fine</option>
                                <option>Other</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group row" id="otherLeave" style="display: none">
                        <div class="col-10 offset-2" >
                            <input class="form-control" type="text" value="Other...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Emotion</label>
                        <div class="col-10">
                            <select class="form-control">
                                <option>happy</option>
                                <option>sad</option>
                                <option>confused</option>
                                <option>afraid</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Date</label>
                        <div class="col-10">
                            <input class="form-control" type="date" value="2011-08-19">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">From</label>
                        <div class="col-4">
                            <input class="form-control" type="time" value="13:45:00">
                        </div>
                        <label class="col-1 offset-1 col-form-label">To</label>
                        <div class="col-4">
                            <input class="form-control" type="time" value="13:45:00">
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php
                            echo $this->Html->link("Cancel", array(
                        'admin' => true,
                        'controller' => 'request'
                        ),
                        array( 'class' => 'btn btn-danger col-md-1 back offset-4')
                        );
                        ?>
                        <?php
                            echo $this->Form->end(array(
                        'label' => 'Send',
                        'class' => 'btn btn-primary col-md-2 offset-3'
                        ));
                        ?>
                    </div>
                </div>
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
<script>
    function reasonOff(obj)
    {
        var reason = obj.value;
        if(reason == 'Other'){
            document.getElementById('otherOff').style.display = "block";
        }else{
            document.getElementById('otherOff').style.display = "none";
        }
    }
    function reasonLeave(obj)
    {
        var reason = obj.value;
        if(reason == 'Other'){
            document.getElementById('otherLeave').style.display = "block";
        }else{
            document.getElementById('otherLeave').style.display = "none";
        }
    }
    $(document).ready(function(){
        //profile
        var count = 1;

        $('.reason').click(function(){
            console.log(this);
            $(this).val();
        });
        $('.more').click(function () {
            console.log("thao");
            count++;
            var formDate = '<div class="col-10 offset-2" >\n' +
                '<input class="form-control" name="date" type="date" value="2011-08-19">' +
                '</div>';
            var formDate = '<label class="col-2 col-form-label">Date</label>' +
                '                        <div class="col-7">' +
                '                            <input class="form-control" name="date['+ count +']" type="date">' +
                '                        </div>' +
                '                        <label class="col-1 col-form-label">In</label>' +
                '                        <div class="col-2">' +
                '                            <select class="form-control" name="in['+ count +']">' +
                '                                <option>ALL</option>' +
                '                                <option>AM</option>' +
                '                                <option>PM</option>' +
                '                            </select>\n' +
                '                        </div><div class="container" style="margin-bottom: 15px"></div>';
            $('#insertDate').append(formDate);
        })

    });
</script>