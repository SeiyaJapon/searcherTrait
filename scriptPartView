<script>
	$('input[name="search"]').on('keyup', function (event) {
        ajaxSearch($(this).val());
    });
    
    function ajaxSearch(element, page = 1)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "{{ route('users.ajaxSearch') }}",
            data: {element: element, page: page},
            success: function (response) {
                $('.table-content').html(response);
            }
        })
        .fail(function( jqXHR, textStatus ) {
            console.log( jqXHR );
            console.log( "Request failed: " + textStatus );
        });
    }
</script>
