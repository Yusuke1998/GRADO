@extends('template')
@section('content')
<div class="col-12">
	<a href="{{ url('/') }}" class="btn btn-sm btn-primary float-right mb-3" title="Volver">Volver</a>
</div>
<div class="col-6">
	<div class="card">
		<div class="card-header text-center">Original</div>
		<div class="card-body text-center">
			<img width="300" height="300" src="{{url()}}/imgOrigi/{{ $img->name }}" alt="">
		</div>
		<div class="card-footer">
			<a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'spaceline']) }}" title="Separar Linea">Separar Linea</a>
			<a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'clustery']) }}" title="Segmentar imagen">Segmentar</a>
			{{-- <a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'grayscale']) }}" title="Escala de Grises">Gris</a> --}}
			{{-- <a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'squelet']) }}" title="Esqueleto">Esqueleto</a> --}}
			{{-- <a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'backgroundBlack']) }}" title="Fondo Negro">Fondo Negro</a> --}}
			{{-- <a class="btn btn-sm btn-info" href="{{ url('modify',[$img->id,'backgroundWhite']) }}" title="Fondo Blanco">Fondo Blanco</a> --}}
		</div>
	</div>
</div>
<div class="col-6">
	<div class="card">
		<div class="card-header text-center">Modificado</div>
		<div class="card-body text-center">
			<img width="300" height="300" src="{{url()}}/imgModif/{{ $img->name }}" alt="">
		</div>
		<div class="card-footer">
			<a class="btn btn-sm btn-warning" href="{{ url('modify',[$img->id,'reset']) }}" title="Eliminar imagen">Reiniciar</a>
			<a class="btn btn-sm btn-danger" href="{{ url('modify',[$img->id,'delete']) }}" title="Eliminar imagen">Borrar</a>
		</div>
	</div>
</div>
@stop