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
                                        <th>Ticket Title</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->name }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ $ticket->createdDate }}</td>
                                        <td class="project-state">
                                            @if($ticket->status==1)
                                            <a class="updateTicketStatus" ticket_id="{{ $ticket->id }}" href="javascript:void(0)" data-status="{{$ticket->status}}">
                                                <span class="badge badge-success" id="ticket-{{ $ticket->id }}">Active</span>
                                            </a>
                                            @else
                                            <a class="updateTicketStatus" id="ticket-{{ $ticket->id }}" ticket_id="{{ $ticket->id }}" href="javascript:void(0)"  data-status="{{$ticket->status}}">
                                                <span class="badge badge-danger" id="ticket-{{ $ticket->id }}">Inactive</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="project-actions">
                                            <!-- <a class="btn btn-primary btn-sm" href="#">
                                    <i class="fas fa-folder">
                                    </i>
                                    View
                                </a> -->
                                            <!-- <a title="View Full Details" class="btn btn-info btn-sm" data-toggle="modal" href="#myModal{{ $ticket->id }}" data-target="#myModal">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                            <a href="{{ url('admin/ticket-reply/'.$ticket->id) }}" title="Edit Ticket" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a title="Delete Ticket" href="javascript:void(0)" class="comfirmDelete btn btn-danger btn-sm" record="ticket" recordid="{{ $ticket->id }}" <?php /* href="{{ url('admin/delete-ticket/'.$ticket->id) }}" */ ?>>

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
                                        <th>Name</th>
                                        <th>Ticket Title</th>
                                        <th>Created Date</th>
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