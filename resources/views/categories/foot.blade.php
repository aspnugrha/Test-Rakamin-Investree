<script>
    var table = $('#tbl-categories');
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $.noConflict();
        
        
        var table = $('#tbl-categories').DataTable({
                ajax: {
                    type: "POST",
                    url: "{{ url('categories/all') }}",
                    data: function (d) {
                            d.filter_search = $('#filter_search').val()
                        }
                    },
                serverSide: true,
                processing: true,
                aaSorting:[[0,"desc"]],
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action'},
                ]
            });

        // ClassicEditor
        //     .create( document.querySelector( '#content' ) )
        //     .catch( error => {
        //         console.error( error );
        //     } );

            
    })

    function load() {
        $('#tbl-categories').DataTable().ajax.reload();
    }

    function addCategories(){
        $('.modal').modal('show');
        $('.form').trigger('reset')
        $('.modal').find('.modal-title').text('Add New Categories')
    }

    function saveCategories(){
        var categories = document.getElementById('categories');

        if(categories.value != ''){
            categories.classList.remove('is-invalid');

            $.ajax({
                type: "POST",
                url: "{{ url('categories/save') }}",
                data: $('.form').serialize(),
                success: function (data) {
                    load();
                    $('.form').trigger("reset");
                    $('.modal').modal('hide');
                    
                    if (data.status == 'save') {
                        toastr["success"]("New Categories Added!")
                    }
                    else{
                        toastr["success"]("Categories Updated!")
                    }
                }
            }); 
        }else{
            categories.classList.add('is-invalid');
        }
    }

    function editCat(id){
        $.ajax({
            type: "GET",
            url: "{{ url('categories/edit') }}/"+id,
            data: $('.form').serialize(),
            success: function (res) {
                $('.form').trigger("reset");
                $('#id').val(res.id);
                $('#categories').val(res.name);
                $('.modal').find('.modal-title').text('Edit Categories')
                $('.modal').modal('show');
            }
        }); 
    }

    function deleteCat(id){
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
                        url: "{{ url('categories/delete') }}/"+id,
                        data: $('.form').serialize(),
                        success: function (res) {
                            load();
                            $('.form').trigger("reset");
                            toastr["success"]("Categories Deleted!")
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
                <form method="POST" class="form">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categories" name="categories" required>
                        <div class="invalid-feedback">Categories can't be null.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveCategories()">Save</button>
            </div>
        </div>
    </div>
</div>
