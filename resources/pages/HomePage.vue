<template>
	<div>
		<!-- carga de imagen -->
		<v-layout>
			<v-flex xs12>
				<v-card 
					class="mx-auto mt-5"
					:loading="false"
					:shaped="true">
					<v-card-text>
						<v-file-input
							:dense="true"
    						accept="image/*"
							color="secondary accent-4"
						    counter
						    :multiple="false"
						    label="Carga de Imagen"
						    placeholder="Seleccion una imagen valida"
						    prepend-icon="mdi-paperclip"
						    outlined
						    @change="onFilePicked"
							:loading="false">
						</v-file-input>
					</v-card-text>
					<v-card-actions>
	                	<v-btn :small="true" class="info pull-right" @click="storeFile()">Guardar</v-btn>
	            	</v-card-actions>
				</v-card>
			</v-flex>
		</v-layout>

		<!-- imagenes -->
		<v-layout>
			<v-flex xs12>
				<images-all></images-all>
			</v-flex>
		</v-layout>
	</div>
</template>

<script>

export default {
	name:'home',

	mounted()
	{

	},

	data()
	{

		return {
			file:null
		}
	},

	methods:{
		makeRequest(method, url, data)
		{
		  return new Promise(function (resolve, reject) {
		    var xhr = new XMLHttpRequest();
		    xhr.open(method, url);
		    xhr.onload = function () {
		      if (this.status >= 200 && this.status < 300) {
		        resolve(xhr.response);
		      } else {
		        reject({
		          status: this.status,
		          statusText: xhr.statusText
		        });
		      }
		    };
		    xhr.onerror = function () {
		      reject({
		        status: this.status,
		        statusText: xhr.statusText
		      });
		    };
		    xhr.send(data);
		  });
		},
		storeFile()
		{
			if (this.file!==null)
			{
				let url = '/load-image'
				let formData = new FormData();
				formData.append('img', this.file);

				this.makeRequest('POST', url, formData)
				.then(data=>{
					swal(
						"Exelente!",
						"Imagen cargada con Exito!",
						"success"
					);
					utils.reload();
				})
				.catch(err=>{
					swal(
						"Error!",
						"Ha ocurrido un problema!",
						"warning"
					);
				});
			}else{
				swal(
						"Imagen obligatoria!",
						"Debes seleccionar una Imagen!",
						"warning"
					);
			}
		},

		onFilePicked(e)
		{
			this.file = e[0];
		}
	}
}

</script>