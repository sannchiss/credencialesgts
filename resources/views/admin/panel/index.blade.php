@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')


@include('admin.panel.list')
@include('admin.panel.modal.addAcount')
@include('admin.panel.modal.listCompany')
@include('admin.panel.modal.showCredencial')


 @endsection        


 @section('scripts')
<script src="{{ asset('js/jspanel.js') }}"></script>

<script type="text/javascript">

$(document).ready(function(){

     
        let url = "{{ route('processing.usuarios') }}?";
        //Datos al datatable
        var tableDocuments =  $('#usuarios-table').DataTable({		
            processing: true,
            serverSide: true,
            ajax: url,
            type:'GET', 
            pageLength: 50, 
                        
                        columns: [
                        {data: 'id', name:'usuarios.id'},	                //  id
                        {data: 'name', name: 'usuarios.name'},		        //  nombre usuario
                        {data: 'email', name: 'usuarios.email'},	       //  email
                        {data: 'password', name: 'usuarios.password'},	    //  contraseña
                        {data: 'modality', name: 'usuarios.modality'},
                        {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion
                        ]					
                    
                    });
                   
           $('#listaCompany').change(function(){ 
            let params = {
             'empresa' : $(this).val()
                          };
             //console.log(params.empresa);          
                let url = "{{ route('query.consultaEmpresa') }}?" + $.param(params);
                          tableDocuments.ajax.url( url ).load();
                      });




});








</script>

 @endsection