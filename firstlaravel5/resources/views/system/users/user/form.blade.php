@extends('templates.oc_template')
@section('button_pull_right')
<button type="submit" form="form-user" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
<a href="<?php echo $data->go_back; ?>" data-toggle="tooltip" title="Go Back" class="btn btn-danger"><i class="fa fa-backward" aria-hidden="true"></i>
</a>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Add User</h3>
      </div>
      <div class="panel-body">

        <?php 
          if (count($errors) > 0) { ?>
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br><br>
              <ul>
                <?php
                  foreach ($errors->all() as $error) { ?>
                    <li><?php echo $error; ?></li>
                  <?php }
                ?>
              </ul>
            </div>
          <?php }
        ?>

        <form class="form-horizontal" role="form" method="POST" action="<?php echo url('/auth/register'); ?>">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="form-group">
            <label class="col-sm-4 control-label">Name</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name" value="<?php echo old('name'); ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">E-Mail Address</label>
            <div class="col-sm-6">
              <input type="email" class="form-control" name="email" value="<?php echo old('email'); ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="password">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Confirm Password</label>
            <div class="col-sm-6">
              <input type="password" class="form-control" name="password_confirmation">
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection