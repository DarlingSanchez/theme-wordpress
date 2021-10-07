//console.log('Prueba');
(function($){
    $('#categorias-productos').change(function(){
        $.ajax({
            url: dsg.ajaxurl ,
            method: "POST",
            data: {
                'action': 'dsgFiltroProductos',
                "categoria": $(this).find(':selected').val()
            },
            beforeSend: function(){
                $(".lista-productos").html("Cargando...");
            },
            success: function(data){
                let html ="";
                data.forEach(item => {
                    html+= `<div class="col-4 my-3">
                    <figure>${item.imagen}</figure>
                    <h4 class="text-center my-2"><a href="${item.link}">${item.titulo}</a></h4></div>`;
                });
                $('.lista-productos').html(html);
            },
            error: function(error){
                console.log(error);
            },
        });
    })
})(jQuery);