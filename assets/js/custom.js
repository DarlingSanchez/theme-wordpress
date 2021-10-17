//console.log('Prueba');
(function($){
    //FUNCION AJAX QUE NOS PERMITE CONECTAR A NUESTRA FUNCION PHP QUE NOS DEVUELVE LOS PRODUCTOS FILTRADOS
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
    
    //FUNCION AJAX QUE NOS PERMITE CONSUMIR NUSTRA API PARA MOSTRAR LAS NOVEDADES
    $(document).ready(function(){
        $.ajax({
            url: dsg.apiurl+"novedades/3" ,
            method: "GET",            
            beforeSend: function(){
                $("#resultado-novedades").html("Cargando...");
            },
            success: function(data){
                let html ="";
                data.forEach(item => {
                    html+= `<div class="col-4 my-3">
                    <figure>${item.imagen}</figure>
                    <h4 class="text-center my-2"><a href="${item.link}">${item.titulo}</a></h4></div>`;
                });
                $('#resultado-novedades').html(html);
            },
            error: function(error){
                console.log(error);
            },
        });
    })
})(jQuery);