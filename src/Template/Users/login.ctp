<?php $this->layout = 'login'; ?>
<?php echo $this->Form->create(); ?>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'email',
        [
            'type'        => 'email',
            'placeholder' => 'Email',
            'class'       => 'form-control',
            'label'       => false,
            'required'    => true
        ]
    );
    ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'password',
        [
            'type'        => 'password',
            'placeholder' => 'Password',
            'class'       => 'form-control',
            'label'       => false,
            'required'    => true
        ]
    );
    ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-xs-8">
        <div class="checkbox icheck">
            <label>
                <input type="checkbox"> Remember Me
            </label>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div>
    <!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>
