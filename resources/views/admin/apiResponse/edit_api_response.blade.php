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
      <form method="POST" @if(empty($apiResponseData->id)) action="{{ $form['form_add_action'] }}" @else action="{{ $form['form_edit_action'] }}" @endif name="{{ $form['form_name'] }}" id="{{ $form['form_id'] }}" enctype="multipart/form-data">
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
                  <label for="inputName">Key</label>
                  <input class="form-control" id="key" name="key" @if(!empty($apiResponseData->key)) value="{{ $apiResponseData->key }}" @else value="{{ old('key') }}" @endif placeholder="Enter key">
                </div>
                
                <div class="form-group">
                  <label for="inputName">Value_ES</label>
                  <input class="form-control" id="value_es" name="value_es" @if(!empty($apiResponseData->value_es)) value="{{ $apiResponseData->value_es }}" @else value="{{ old('value_es') }}" @endif placeholder="Enter Value_ES">
                </div>
                
                <div class="form-group">
                  <label for="inputName">Value_DE</label>
                  <input class="form-control" id="value_de" name="value_de" @if(!empty($apiResponseData->value_de)) value="{{ $apiResponseData->value_de }}" @else value="{{ old('value_de') }}" @endif placeholder="Enter Value_DE">
                </div>
                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputName">Value_EN</label>
                  <input class="form-control" id="value_en" name="value_en" @if(!empty($apiResponseData->value_en)) value="{{ $apiResponseData->value_en }}" @else value="{{ old('value_en') }}" @endif placeholder="Enter Value_EN">
                </div>
                <div class="form-group">
                  <label for="inputName">Value_FR</label>
                  <input class="form-control" id="value_fr" name="value_fr" @if(!empty($apiResponseData->value_fr)) value="{{ $apiResponseData->value_fr }}" @else value="{{ old('value_fr') }}" @endif placeholder="Enter Value_FR">
                </div>
                <div div class="form-group">
                  <label>Status</label>
                  <select name="status" id="status" class="form-control select2" style="width: 100%;">
                    @foreach($statusArray as $k=> $status)
                    <option value="{{ $k }}" @if(isset($apiResponseData->status) && $apiResponseData->status == $k) selected @endif >{{ $status }}</option>
                    @endforeach
                  </select>
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
@endsection