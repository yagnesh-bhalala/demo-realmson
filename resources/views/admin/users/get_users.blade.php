@extends('admin.adminLayout.admin_design')
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
                            <a class="btn btn-block btn-success" style="max-width: 150px; float:right; inline:block;" href="<?=$breadcrumb['button_add_link'] ?>">
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->firstName }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->state }}</td>
                                        <td class="project-state">
                                            @if($user->status==1)
                                            <a class="updateUserStatus" user_id="{{ $user->id }}" href="javascript:void(0)" data-status="{{$user->status}}">
                                                <span class="badge badge-success" id="user-{{ $user->id }}">Active</span>
                                            </a>
                                            @else
                                            <a class="updateUserStatus" id="user-{{ $user->id }}" user_id="{{ $user->id }}" href="javascript:void(0)"  data-status="{{$user->status}}">
                                                <span class="badge badge-danger" id="user-{{ $user->id }}">Inactive</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="project-actions">
                                            <!-- <a class="btn btn-primary btn-sm" href="#">
                                    <i class="fas fa-folder">
                                    </i>
                                    View
                                </a> -->
                                            <!-- <a title="View Full Details" class="btn btn-info btn-sm" data-toggle="modal" href="#myModal{{ $user->id }}" data-target="#myModal">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                            <a href="{{ url('admin/add-edit-user/'.$user->id) }}" title="Edit Users" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a title="Delete user" href="javascript:void(0)" class="comfirmDelete btn btn-danger btn-sm" record="user" recordid="{{ $user->id }}" <?php /* href="{{ url('admin/delete-user/'.$user->id) }}" */ ?>>

                                                <i class="fas fa-trash">
                                                </i>
                                                <?/*=$breadcrumb['button_delete']*/ ?>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- /.modal -->

                                    <div class="modal fade" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-info">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">User Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Name : {{ $user->firstName }}</p>
                                                    <p>Email : {{ $user->email }}</p>
                                                    <p>Address : {{ $user->state }}, {{$user->city}}, {{$user->zipCode}}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Country</th>
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