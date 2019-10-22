<div class="user-panel">
    <div class="pull-left image">
        <?php echo $this->Html->image($appUser->image_url, array('class' => 'img-circle', 'alt' => 'User Image')); ?>
    </div>
    <div class="pull-left info">
        <p><?= $appUser->full_name ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>
