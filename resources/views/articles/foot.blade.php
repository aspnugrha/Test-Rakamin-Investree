<script>
    var table = $('#tbl-articles');
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $.noConflict();
        
        
        var table = $('#tbl-articles').DataTable({
                ajax: {
                    type: "POST",
                    url: "{{ url('articles/all') }}",
                    data: function (d) {
                            d.filter_search = $('#filter_search').val()
                        }
                    },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {data: 'image', name: 'image'},
                    {data: 'category', name: 'category'},
                    {data: 'title', name: 'title'},
                    {data: 'content', name: 'content'},
                    {data: 'action', name: 'action'},
                ]
            });

        // ClassicEditor
        //     .create( document.querySelector( '#content' ) )
        //     .catch( error => {
        //         console.error( error );
        //     } );

        load_categories();
    })
    
    function load() {
        $('#tbl-articles').DataTable().ajax.reload();
    }
    
    function load_categories(id){
        $.ajax({
            type: "GET",
            url: "{{ url('categories/get_all') }}/",
            success: function (res) {
                var html = '<option value="">Select Category</option>';

                if(res.length > 0){
                    res.forEach(category => {
                        html += '<option value="'+ category['id'] +'">'+ category['name'] +'</option>';
                    });
                }

                $('#category').html(html);
            }
        }); 
    }

    function addArticles(){
        $('.modal').modal('show');
        $('.form').trigger('reset')
        $('#mymodal').find('.modal-title').text('Add New Articles')
    }

    function saveArticles(){
        var title = document.getElementById('title'),
            category = document.getElementById('category'),
            content = document.getElementById('content');

        if(category != '' && title.value != '' && content.value != ''){

            var postData = new FormData($('.form')[0]);
            $.ajax({
                type: "POST",
                url: "{{ url('articles/save') }}",
                data: postData,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    load();
                    $('.form').trigger("reset");
                    $('.modal').modal('hide');
                    
                    if (data.status == 'save') {
                        toastr["success"]("New Articles Added!")
                    }
                    else{
                        toastr["success"]("Articles Updated!")
                    }
                }
            }); 
        }else{
            if(title.value == ''){
                title.classList.add('is-invalid');
            }else{
                title.classList.remove('is-invalid');
            }
            if(content.value == ''){
                content.classList.add('is-invalid');
            }else{
                content.classList.remove('is-invalid');
            }
            if(category.value == ''){
                category.classList.add('is-invalid');
            }else{
                category.classList.remove('is-invalid');
            }
        }
    }

    function editArt(id){
        $.ajax({
            type: "GET",
            url: "{{ url('articles/edit') }}/"+id,
            data: $('.form').serialize(),
            success: function (res) {
                $('.form').trigger("reset");
                $('#id').val(res.id);
                $('#title').val(res.title);
                $('#category').val(res.category_id);
                $('#content').val(res.content);
                $('.modal').find('.modal-title').text('Edit Articles')
                $('.modal').modal('show');
            }
        }); 
    }

    function deleteArt(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('articles/delete') }}/"+id,
                        data: $('.form').serialize(),
                        success: function (res) {
                            load();
                            $('.form').trigger("reset");
                            toastr["success"]("Articles Deleted!")
                        }
                    }); 
                }
            })
    }
</script>

<!-- Modal -->
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select Category</option>
                            
                        </select>
                        <div class="invalid-feedback">Category can't be null.</div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback">Title can't be null.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        <div class="invalid-feedback">Content can't be null.</div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        <div class="invalid-feedback">Image can't be null.</div>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveArticles()">Save changes</button>
            </div>
        </div>
    </div>
</div>
