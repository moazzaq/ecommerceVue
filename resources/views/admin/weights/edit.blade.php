@extends('admin.index')
@section('content')

<div class="box">
  <div class="box-header">
    <h3 class="box-title">{{ $title }}</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    {!! Form::open(['url'=>aurl('weights/'.$weight->id),'method'=>'put','files'=>true ]) !!}

   <div class="form-group">
        {!! Form::label('title_ar',trans('admin.name_ar')) !!}
        {!! Form::text('title_ar',$weight->title_ar,['class'=>'form-control']) !!}
     </div>

     <div class="form-group">
        {!! Form::label('title_en',trans('admin.title_en')) !!}
        {!! Form::text('title_en',$weight->title_en,['class'=>'form-control']) !!}
     </div>

     </div>



     {!! Form::submit(trans('admin.save'),['class'=>'btn btn-primary']) !!}
    {!! Form::close() !!}
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->



@endsection