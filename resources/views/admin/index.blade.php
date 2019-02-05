@include('admin.layouts.header')
@include('admin.layouts.navbar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        @include('admin.layouts.messages')
@yield('content')

    </section>
    <!-- /.content -->
</div>
@include('admin.layouts.footer')


