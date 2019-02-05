@push('js')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
            $("#dropzonefileupload").dropzone({
                url : "{{aurl('upload/image/'.$product->id)}}",
                paramName : 'file',
                uploadMultiple : false,
                maxFiles:15,
                maxFilessize:3, // MB
                acceptedFiles :'image/*',
                dictDefaultMessage:'إضغط هنا لسحب الملفات',
                dictRemoveFile:'{{trans('admin.delete')}}',
                addRemoveLinks:true,
                removedfile:function(file){
                  // alert(file.fid)
                    $.ajax({
                        dataType: 'json',
                        method:'post',
                        url:'{{aurl('delete/image')}}',
                        data:{_token:'{{csrf_token()}}',id:file.fid}
                    });
                    var fmock;
                    return (fmock = file.previewElement) != null ? fmock.parentNode.removeChild(file.previewElement):void 0;
                },
                params : {
                    _token :"{{ csrf_token() }}",
                },init:function () {
                    @foreach($product->files()->get() as $file)
                        var mock = {name : '{{$file->name}}' ,fid : '{{$file->id}}' ,size : '{{$file->size}}' ,type : '{{$file->mime_type}}' };
                        // this.addFile.call(this,mock);
                        this.emit('addedfile',mock);
                        this.options.thumbnail.call(this,mock,'{{url('storage/'.$file->full_file)}}')
                    @endforeach

                    this.on('sending',function (file,xhr,formData) {
                        formData.append('fid','');
                        file.fid='';
                    });
                    this.on('success',function (file,response) {
                        file.fid = response.id;
                    })
                }
            });
            $("#main-photo").dropzone({
                url : "{{aurl('update/image/'.$product->id)}}",
                paramName : 'file',
                uploadMultiple : false,
                maxFiles:1,
                maxFilessize:3, // MB
                acceptedFiles :'image/*',
                dictDefaultMessage:'{{trans('admin.mainphoto')}}',
                dictRemoveFile:'{{trans('admin.delete')}}',
                addRemoveLinks:true,
                removedfile:function(file){
                    // alert(file.fid)
                    $.ajax({
                        dataType: 'json',
                        method:'post',
                        url:'{{aurl('delete/product/image/'.$product->id)}}',
                        data:{_token:'{{csrf_token()}}'}
                    });
                    var fmock;
                    return (fmock = file.previewElement) != null ? fmock.parentNode.removeChild(file.previewElement):void 0;
                },
                params : {
                    _token :"{{ csrf_token() }}",
                },init:function () {
                    @if(!empty($product->photo))
                    var mock = {name : '{{$product->title}}' ,size : '' ,type : '' };
                    // this.addFile.call(this,mock);
                    this.emit('addedfile',mock);
                    this.options.thumbnail.call(this,mock,'{{url('storage/'.$product->photo)}}');
                    $('.dz-progress').remove();
                    @endif
                        this.on('sending',function (file,xhr,formData) {
                        formData.append('fid','');
                        file.fid='';
                    });
                    this.on('success',function (file,response) {
                        file.fid = response.id;
                    })
                }
            });
        })
    </script>
@endpush

<div id="product_media" class="tab-pane fade">
    <h3>{{trans('admin.product_media')}}</h3>
    <hr>
    <center><h1>{{trans('admin.mainphoto')}}</h1></center>
    <div class="dropzone" id="main-photo"></div>
    <hr>
    <center><h1>{{trans('admin.photo')}}</h1></center>

    <div class="dropzone" id="dropzonefileupload"></div>
</div>