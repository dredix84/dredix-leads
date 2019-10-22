<?php $this->layout = 'register'; ?>
<?php echo $this->Form->create(); ?>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'full_name',
        ['placeholder' => 'Full name', 'class' => 'form-control', 'label' => false, 'required' => true]
    );
    ?>
    <span class="glyphicon glyphicon-user form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'email',
        ['type' => 'email', 'placeholder' => 'Email', 'class' => 'form-control', 'label' => false, 'required' => true]
    );
    ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'password',
        ['type'        => 'password',
         'placeholder' => 'Password',
         'class'       => 'form-control',
         'label'       => false,
         'required'    => true
        ]
    );
    ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $this->Form->control(
        'confirm_password',
        ['type'        => 'password',
         'placeholder' => 'Password',
         'class'       => 'form-control',
         'label'       => false,
         'required'    => true
        ]
    );
    ?>
    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-xs-8">
        <div class="checkbox icheck">
            <label>
                <?= $this->Form->control(
                    'i_agree_to_terms',
                    [
                        'type'     => 'checkbox',
                        'class'    => 'form-control',
                        'label'    => ' Yes, I agree to the <a href="#">terms</a>',
                        'escape'   => false,
                        'required' => true
                    ]
                );
                ?>

            </label>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
    </div>
    <!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>
