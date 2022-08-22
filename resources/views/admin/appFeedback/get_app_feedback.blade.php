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
                    <h1><?= $data['main_bread'] ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?= $data['forward_bread'] ?></li>
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
                        <h3 class="card-title"><?= $data['bread'] ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <table id="products" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Rating</th>                                        
                                        <th>Feedback</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appFeedback as $appFeedbacks)
                                    <?php 
                                        $starts = "";
                                        for($i=1;$i<=5;$i++){
                                            if($i<=$appFeedbacks->rating){
                                                $starts .= '<i class="fas fa-star"></i>'; 
                                            }else{
                                                $starts .= '<i class="fas fa-star"></i>'; 
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td>{{ $appFeedbacks->id }}</td>
                                        <td>{{ $appFeedbacks->name }}</td>
                                        <!-- <td>{{ $appFeedbacks->rating }}</td> -->
                                        <td>
                                            <?php
                                                $starts = "";
                                                for($i=1;$i<=5;$i++){
                                                    if($i<=$appFeedbacks->rating){
                                                        $starts .= '<i class="nav-icon fas fa-star" style="color:yellow;"></i>'; 
                                                    }
                                                    else{
                                                        $starts .= '<i class="nav-icon far fa-star" style="color:yellow;"></i>';
                                                    }
                                                }
                                                echo $starts;
                                            ?>
                                        </td>
                                        <td>{{ $appFeedbacks->feedback }}</td>
                                        <td class="project-state">
                                            @if($appFeedbacks->status==1)
                                            <a class="updateAppFeedbackStatus" feedback_id="{{ $appFeedbacks->id }}" href="javascript:void(0)" data-status="{{ $appFeedbacks->status }}">
                                                <span class="badge badge-success" id="feedback-{{ $appFeedbacks->id }}">Active</span>
                                            </a>
                                            @else
                                            <a class="updateAppFeedbackStatus" id="feedback-{{ $appFeedbacks->id }}" feedback_id="{{ $appFeedbacks->id }}" href="javascript:void(0)"  data-status="{{ $appFeedbacks->status }}">
                                                <span class="badge badge-danger" id="feedback-{{ $appFeedbacks->id }}">Inactive</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="project-actions">
                                            <!-- <a href="{{ url('admin/add-edit-faq/'.$appFeedbacks->id) }}" title="Edit Users" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a> -->
                                            <a title="Delete App Feedback" href="javascript:void(0)" class="comfirmDelete btn btn-danger btn-sm" record="app-feedback" recordid="{{ $appFeedbacks->id }}" <?php /* href="{{ url('admin/delete-user/'.$appFeedbacks->id) }}" */ ?>>
                                                <i class="fas fa-trash">
                                                </i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Rating</th>                                        
                                        <th>Feedback</th>
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
