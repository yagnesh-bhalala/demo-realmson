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
              <li class="breadcrumb-item"><a href="{{ route('admin.home')}}">Home</a></li>
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
      <form method="POST" @if(empty($blogData->id)) action="{{ $form['form_add_action'] }}" @else action="{{ $form['form_edit_action'] }}" @endif name="{{ $form['form_name'] }}" id="{{ $form['form_id'] }}" enctype="multipart/form-data">
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
                      <label for="inputName">Title</label>
                      <input class="form-control" onkeyup="convertToSlug(this.value)" id="title" name="title" @if(!empty($blogData->title)) value="{{ $blogData->title }}" @else value="{{ old('title') }}" @endif placeholder="Enter Title">
                  </div>
                  <div class="form-group">
                      <label for="inputName">Description</label>
                      <textarea class="form-control" id="id-ckeditor" name="description">@if(!empty($blogData->description)) {{ $blogData->description }} @else {{ old('description') }} @endif</textarea>                      
                  </div>
                  <div class="form-group">
                    <label for="inputName">Meta Title</label>
                    <input class="form-control" id="metatitle" name="metatitle" @if(!empty($blogData->metatitle)) value="{{ $blogData->metatitle }}" @else value="{{ old('metatitle') }}" @endif placeholder="Enter Meta Title">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="inputName">Slug</label>
                      <input class="form-control" onkeyup="convertToSlug(this.value)" id="slug" name="slug" @if(!empty($blogData->slug)) value="{{ $blogData->slug }}" @else value="{{ old('slug') }}" @endif placeholder="Enter Slug">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Meta Keyword</label>
                    <input class="form-control" id="metakeyword" name="metakeyword" @if(!empty($blogData->metakeyword)) value="{{ $blogData->metakeyword }}" @else value="{{ old('metakeyword') }}" @endif placeholder="Enter Meta Keyword">
                  </div>
                  <div class="form-group">
                      <label for="inputName">Meta Description</label>
                      <textarea class="form-control" id="metadescription" name="metadescription">@if(!empty($blogData->metadescription)) {{ $blogData->metadescription }} @else {{ old('metadescription') }} @endif</textarea>                      
                  </div>
                  <div class="form-group">
                    <label for="inputName">CreatedDate</label>
                    <input type="date" class="form-control" id="createdDate" name="createdDate" value="<?php echo isset($blogData->createdDate)? date('Y-m-d', strtotime($blogData->createdDate)) :""?>">
                  </div>
                  <div div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                        @foreach($statusArray as $k=> $status)
                          <option value="{{ $k }}" @if(!empty($blogData->status) && $blogData->status == $k)  selected @endif >{{ $status }}</option>
                        @endforeach
                    </select>
                  </div>
                
                  <div class="form-group">
                    <label for="exampleInputFile">Blog Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                          <input type="file" class="custom-file-input" id="image" name="image">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <!-- <div class="input-group-append">
                          <span class="input-group-text" id="">Upload</span>
                      </div> -->
                    </div>
                      <?php  if(isset($blogData->thumbImage)) { ?>
                        <div class="form-group">
                            <a href="<?php echo isset($blogData->profileimage)?$blogData->profileimage:"" ?>" target="_blank">
                                <img id="imagePreview" src="<?php echo isset($blogData->thumbImage)?$blogData->thumbImage:"" ?>" style="width:100px; margin-top: 15px"/>
                            </a>
                        </div> 
                        <?php } else {
                        ?>
                            <div class="form-group">
                                    <img class="d-none" id="imagePreview" src="<?php echo isset($blogData->thumbImage)?$blogData->thumbImage:"" ?>" style="width:100px; margin-top: 15px" />
                            </div>
                        <?php
                      } ?>
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
  <script src="{{ url('public/ckeditor/ckeditor.js') }}"></script>
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
    CKEDITOR.replace('id-ckeditor');
  </script>

<script>
    function convertToSlug( str ) {	  
      //replace all special characters | symbols with a space
      str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
      
      // trim spaces at start and end of string
      str = str.replace(/^\s+|\s+$/gm,'');
      
      // replace space with dash/hyphen
      str = str.replace(/\s+/g, '-');	
      document.getElementById("slug").value= str;
      $("#slug").trigger("blur");
      //return str;
    }
</script>
@endsection