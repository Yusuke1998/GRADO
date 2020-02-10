<template>
	<div>
		<template v-if="imagen!==null">
			<v-layout>
				<v-flex xs6>
					<v-card>
						<v-card-title>
			                <v-container fill-height fluid>
			                  <v-layout fill-height>
			                    <v-flex xs12 flexbox>
			                    	<center>
			                      		<span class="headline" v-text="'Imagen Original'"></span>
			                      	</center>
			                    </v-flex>
			                  </v-layout>
			                </v-container>
						</v-card-title>
						<v-card-text>
			              <center>
				              <v-img
				              	:float-none="true"
				                :src="imagenOrg"
				                width="90%"
				                height="90%">
				              </v-img>
			              </center>
			            </v-card-text>
		                <v-card-actions>
		                	<v-btn :small="true" @click.once="applyAction('spaceline')">Separar</v-btn>
		                	<v-btn :small="true" @click.once="applyAction('clustery')">Segmentar</v-btn>
		                	<v-btn :small="true" @click.once="applyAction('grayscale')">Gris</v-btn>
		                	<v-btn :small="true" @click.once="applyAction('backgroundBlack')">Negro</v-btn>
		                	<v-btn :small="true" @click.once="applyAction('squelet')">Esqueletizar</v-btn>
		            	</v-card-actions>
		            </v-card>
				</v-flex>
				<v-flex xs6 v-if="exist">
					<v-card>
						<v-card-title>
			                <v-container fill-height fluid>
			                  <v-layout fill-height>
			                    <v-flex xs12 flexbox>
			                    	<center>
			                      		<span class="headline" v-text="'Imagen Modificada'"></span>
			                      	</center>
			                    </v-flex>
			                  </v-layout>
			                </v-container>
						</v-card-title>
						<v-card-text>
			              <center>
				              <v-img
				              	:float-none="true"
				                :src="imagenMod"
				                @error="this.exist=false;"
				                width="90%"
				                height="90%">
				              </v-img>
			              </center>
		                </v-card-text>
		                <v-card-actions v-if="exist">
		                	<v-btn :small="true" @click.once="applyAction('reset')">Reiniciar</v-btn>
		                	<v-btn :small="true" @click.once="applyAction('delete')">Borrar</v-btn>
		                	<v-btn :small="true" link :to="{ path: '/imagen/modificada/'+imagen.id }">Ver</v-btn>
		            	</v-card-actions>
		            </v-card>
				</v-flex>
			</v-layout>
		</template>
	</div>
</template>

<script>

export default {
	name:'setImage',

	created()
	{
		this.findID(this.$route.params.id)
	},

	data()
	{
		return {
			done:false,
			exist:true,
			imagen:null,
			imagenMod:null,
			imagenOrg:null,
			imgDefault:'/imgDefault/default.png'
		}
	},

	methods:{

		async findID(id)
		{
			let response = await axios.get('/find-image-id/'+id);
			if (response.data!==null) {
				this.imagen = response.data;
				this.imagenOrg = '/imgOrigi/'+response.data.name;
				this.imagenMod = '/imgModif/'+response.data.name;
			}
		},

		applyAction(action)
		{
			let id = this.imagen.id;
			let url = '/action-image/'+id+'/'+action;
			this.$root.loading('Procesando', 'Se estan aplicando una accion', 500000);
	        setTimeout(() => {
				axios.get(url)
				.then(rsp=>{
					if (rsp.data.status == 'success')
					{
						swal.close()
						swal(
							"Exelente!",
							"La accion se aplico correctamente!",
							"success"
						);
					}else{
						swal.close()
						swal(
							"Error!",
							"Ha ocurrido un problema!",
							"warning"
						);
					}
					utils.reload();
				})
				.catch(err=>{
					swal.close()
					swal(
						"Error!",
						"Ha ocurrido un problema!",
						"warning"
					);
				});;
			},500);
		}
	}
}

</script>