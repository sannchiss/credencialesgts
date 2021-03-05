@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')

@include('admin.add.empresa.loadFileForm')
@include('admin.add.empresa.loadTable')


@section('scripts')

<script type="text/javascript">

$(document).ready(function(){

    Dropzone.options.myDropzone = {
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFilezise: 50,
            maxFiles: 2,
            
            init: function() {
                var submitBtn = document.querySelector("#submit");
                myDropzone = this;
                
                submitBtn.addEventListener("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });
                this.on("addedfile", function(file) {
                    alert("file uploaded");
                });
                
                this.on("complete", function(file) {
                    myDropzone.removeFile(file);
                });

                this.on("success", 
                    myDropzone.processQueue.bind(myDropzone)
                    console.log("Cargado");
                );
            }
        };





        let url = "{{ route('upload.loading') }}";
        //Datos al datatable
        $('#cuentas-table').DataTable({		
            processing: true,
            serverSide: true,
            ajax: url,
            type:'GET', 
            pageLength: 50, 
                        
                        columns: [
                        {data: 'id', name:'cuentas.id'},	                            //  id
                        {data: 'cuenta_txa', name: 'cuentas.cuenta_txa'},	            //  Cuenta TXA
                        {data: 'cuenta_gts', name: 'cuentas.cuenta_gts'},		        //  Cuenta GTS
                        {data: 'empresa',    name: 'cuentas.empresa'},			        //  Empresa
                        {data: 'action',     name: 'action', searchable: true, orderable: true} 
                        ]					
              
              });


});

</script>

@endsection

@endsection