<?php
    $this->assign('title','Yasumi Network');
    echo $this->element('nav');
    echo $this->Html->script('accept');
?>
<div class="container-fluid gedf-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-3 gedf-main">
            <?php
                $status = 'off';
            $time = 'on ' . $commentData['Off']['dates'];
            ?>
            <!--- \\\\\\\Post-->
            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-2">
                                <a href="<?php if( $user_data['id'] == $commentData['Off']['user_id'] || $user_data['role'] == 1 || $user_data['role'] == 2){ echo '/chatwork/users/profile/'.$commentData['Off']['user_id'];}?>"><img class="rounded-circle" width="45" src="<?php echo $commentData['Off']['avatar'] ?>" ></a>
                            </div>
                            <div class="ml-2">
                                <div class="h5 m-0"><?php echo $commentData['Off']['user_name'] ?><i style="font-size: 14px;"> - feeling <?php echo $commentData['Off']['emotion']?></i></div>
                                <div class="h7 text-muted"><?php echo $commentData['Off']['email'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body status" data="<?php echo $commentData['Off']['status']; ?>" style="border-bottom: 1px solid rgba(0,0,0,.125);padding-bottom: 1.25rem;padding-top: 1.25rem;padding-left: 1.25rem;padding-right: 0rem;">
                    <a class="card-link">
                        <h5 class="card-title"><?php echo 'Asking for ' . $status . ' ' . $time; ?></h5>
                    </a>
                    <p class="card-text" style="margin-bottom: 10px;">
                        <?php echo 'Type: ' . $commentData['Off']['type']; ?>
                    </p>
                    <p class="card-text" style="margin-bottom: 10px;">
                        <?php echo 'Day left: ' . $commentData['Off']['day_left']; ?>
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text">
                                <?php echo 'Reason: ' . $commentData['Off']['reason']; ?>
                            </p>
                        </div>
                        <div class="col-md-3 offset-md-3" style="text-align: right">
                            <span class="colorStatus" style="padding-right: 15px;"><?php echo $commentData['Off']['status']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="col-12 col-sm-12">
                        <div class="panel panel-white post panel-shadow">

                            <div class="post-footer">
                                <form method="post">
                                    <div class="input-group">
                                        <input class="form-control" value="<?php echo $commentData['Off']['id']; ?>" name="offid" type="text" style="display: none">
                                        <input class="form-control" placeholder="Add a comment" name="comment" type="text">
                                        <button type="submit" style="padding-top: 5px;background-color: rgba(0, 0, 0, 0.125); border: 1px solid rgba(0, 0, 0, 0.125);border-radius: 0 .25rem .25rem 0; padding-left: 5px; padding-right: 5px"><i class="fa fa-send-o" style="font-size:24px"></i></button>
                                    </div>
                                </form>

                                <ul class="comments-list">
                                    <?php foreach($commentData['Comment'] as $key=>$comment): ?>
                                    <li class="comment">
                                        <a class="pull-left" href="#">
                                            <img class="avatar" src="<?php echo $comment['User']['avatar'] ?>" alt="avatar">
                                        </a>
                                        <div class="comment-body">
                                            <div class="comment-heading">
                                                <h4 class="user"><?php echo $comment['User']['name'] ?></h4>
                                            </div>
                                            <p><?php echo $comment['Comment']['comment'] ?></p>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Post /////-->
        </div>
    </div>
</div>
<style>
    .pull-left{
        padding-top: 5px;
    }
    .panel-white  .panel-footer {
        background-color: #fff;
        border-color: #ddd;
    }
    .post .post-image .image {
        width: 100%;
        height: auto;
    }
    .post .post-description {
        padding: 15px;
    }
    .post .post-description p {
        font-size: 14px;
    }
    .post .post-description .stats {
        margin-top: 20px;
    }
    .post .post-description .stats .stat-item {
        display: inline-block;
        margin-right: 15px;
    }
    .post .post-description .stats .stat-item .icon {
        margin-right: 8px;
    }
    .post .post-footer {
        border-top: 1px solid #ddd;
        padding: 15px;
    }
    .post .post-footer .input-group-addon a {
        color: #454545;
    }
    .post .post-footer .comments-list {
        padding: 0;
        margin-top: 20px;
        list-style-type: none;
    }
    .post .post-footer .comments-list .comment {
        display: block;
        width: 100%;
        margin: 20px 0;
    }
    .post .post-footer .comments-list .comment .avatar {
        width: 35px;
        height: 35px;
    }
    .post .post-footer .comments-list .comment .comment-heading {
        display: block;
        width: 100%;
    }
    .post .post-footer .comments-list .comment .comment-heading .user {
        font-size: 14px;
        font-weight: bold;
        display: inline;
        margin-top: 0;
        margin-right: 10px;
    }
    .post .post-footer .comments-list .comment .comment-heading .time {
        font-size: 12px;
        color: #aaa;
        margin-top: 0;
        display: inline;
    }
    .post .post-footer .comments-list .comment .comment-body {
        margin-left: 50px;
    }
    .post .post-footer .comments-list .comment > .comments-list {
        margin-left: 50px;
    }
</style>