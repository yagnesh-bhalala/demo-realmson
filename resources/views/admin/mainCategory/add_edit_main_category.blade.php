@extends('admin.adminLayout.admin_design')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $breadcrumb['main_bread'] }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ $breadcrumb['forward_bread'] }}</a></li>
            </ol>
          </div>
        </div>
        <!-- @if ($message = Session::get('error'))
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                    <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('success_message'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                    <strong>{{ $message }}</strong>
            </div>
        @endif -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="<?=$form['form_method']?>" @if(empty($mainCategoryData->id)) action="<?=$form['form_add_action']?>" @else action="<?=$form['form_edit_action']?>" @endif name="<?=$form['form_name']?>" id="<?=$form['form_id']?>" enctype="multipart/form-data">
        @csrf
          <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ $breadcrumb['bread'] }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="inputName">First Name</label>
                      <input class="form-control" id="mainCategoryName" name="mainCategoryName" @if(!empty($mainCategoryData->mainCategoryName)) value="{{ $mainCategoryData->mainCategoryName }}" @else value="{{ old('mainCategoryName') }}" @endif placeholder="Enter Main Category Name">
                  </div>
                  <div class="form-group">
                      <label for="inputName">Color Code</label>
                      <input class="form-control" id="colorCode" name="colorCode" @if(!empty($mainCategoryData->colorCode)) value="{{ $mainCategoryData->colorCode }}" @else value="{{ old('colorCode') }}" @endif placeholder="Enter Color Code">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputFile">Category Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                          <input type="file" class="custom-file-input" id="image" name="image">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <!-- <div class="input-group-append">
                          <span class="input-group-text" id="">Upload</span>
                      </div> -->
                    </div>
                      <?php  if(isset($mainCategoryData->thumbprofileimage)) {  ?>
                        <div class="form-group">
                            <a href="<?php echo isset($mainCategoryData->profileimage)?$mainCategoryData->profileimage:"" ?>" target="_blank">
                                <img id="imagePreview" src="<?php echo isset($mainCategoryData->thumbprofileimage)?$mainCategoryData->thumbprofileimage:"" ?>" style="width:100px; margin-top: 15px"/>
                            </a>
                        </div> 
                        <?php } else { 
                        ?>
                            <div class="form-group">
                              <img class="d-none" id="imagePreview" src="<?php echo isset($mainCategoryData->thumbprofileimage)?$mainCategoryData->thumbprofileimage:"" ?>" style="width:100px; margin-top: 15px" />
                            </div>
                        <?php
                      } ?>
                  </div>
                  <div div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                        @foreach($statusArray as $k=> $status)
                          <option value="{{ $k }}" @if(!empty($mainCategoryData->status) && $mainCategoryData->status == $k) selected @else @endif >{{ $status }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ $breadcrumb['button_add'] }}</button>
              </div>
            </div>
          </div>
        </form> 
      </div>
    </section>
  </div>

@endsection

@section('scripts')

  <script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                if (!input.files[0].type.match('image.*')) {return true; }
                
                else{                            
                        reader.onload = function(e) {
                            $('#imagePreview').removeClass("d-none");
                            $('#imagePreview').attr("src", e.target.result );
                    
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function() {
            
            readURL(this);
        });
  </script>

@endsection