@extends('admin.adminLayout.admin_design')

@section('stylesheets')
    
@endsection


@section('content')

<div class="content-wrapper" style="min-height: 1200.88px;">
    <section class="content-header">
        <div class="container-fluid">
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $breadcrumb['main_bread'] ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?= $breadcrumb['forward_bread'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $breadcrumb['bread'] ?></h3>
                        <td class="project-actions text-right">
                            <a class="btn btn-block btn-success" style="max-width: 165px; float:right; inline:block;" href="<?=$breadcrumb['button_add_link'] ?>">
                                <i class="fas fa-plus"></i>
                                <?=$breadcrumb['button_add']?>
                            </a>
                        </td>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <table id="products" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Key</th>
                                        <th>Value_EN</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($apiResponses as $apiResponse)
                                    <tr>
                                        <td>{{ $apiResponse->id }}</td>
                                        <td>{{ $apiResponse->key }}</td>
                                        <td>{{ strip_tags($apiResponse->value_en) }}</td>
                                        <td class="project-state">
                                            @if($apiResponse->status==1)
                                            <a class="updateApiResponseStatus" apiResponse_id="{{ $apiResponse->id }}" href="javascript:void(0)" data-status="{{ $apiResponse->status }}">
                                                <span class="badge badge-success" id="apiResponse-{{ $apiResponse->id }}">Active</span>
                                            </a>
                                            @else
                                            <a class="updateApiResponseStatus" id="apiResponse-{{ $apiResponse->id }}" apiResponse_id="{{ $apiResponse->id }}" href="javascript:void(0)"  data-status="{{ $apiResponse->status }}">
                                                <span class="badge badge-danger" id="apiResponse-{{ $apiResponse->id }}">Inactive</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="project-actions">
                                            <a href="{{ url('admin/add-edit-api-response/'.$apiResponse->id) }}" title="Edit Api Response" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a title="Delete Api Response" href="javascript:void(0)" class="comfirmDelete btn btn-danger btn-sm" record="apiresponse" recordid="{{ $apiResponse->id }}" <?php /* href="{{ url('admin/delete-user/'.$apiResponse->id) }}" */ ?>>

                                                <i class="fas fa-trash">
                                                </i>
                                                <?/*=$breadcrumb['button_delete']*/ ?>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Key</th>
                                        <th>Value_EN</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('scripts')
    
@endsection
