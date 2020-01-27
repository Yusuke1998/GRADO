@extends('template')
@section('content')
<div class="offset-3 col-md-6">
	<form action="{{ url('/load') }}" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<input class="form-control" type="file" name="img" placeholder="Ingresa una Imagen">
		</div>
		<div class="form-group">
			<input class="btn btn-success btn-block" type="submit" name="submit" value="Procesar">
		</div>
	</form>
</div>
<div class="col-12">
	<div class="card">
		<div class="card-header">
			<h4 class="text-center">Lista de imagenes cargadas</h4>
		</div>
		<div class="text-center card-body row mt-2">
			@foreach($images as $image)
				<div class="col-3 mb-3">
					<a href="{{ url('/show',$image->id) }}">
						<img 
						width="150" 
						height="150" 
						src="{{url()}}/imgOrigi/{{ $image->name }}" 
						alt="{{ $image->name }}">
					</a>
					<a class="btn btn-sm btn-danger" href="{{ url('delete',$image->id) }}" title="Eliminar imagen">Borrar</a>
				</div>
			@endforeach
		</div>
	</div>
</div>
@stop