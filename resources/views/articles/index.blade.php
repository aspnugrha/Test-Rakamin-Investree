@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Articles</div>

                <div class="card-body">
                    
                    <button class="btn btn-success mb-2" onclick="addArticles()">TAMBAH</button>

                        <table class="table table-bordered table-striped" id="tbl-articles">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.2.1/classic/ckeditor.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?= view('articles/foot') ?>


@endsection
