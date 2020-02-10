<template>
	<div>
		<template v-if="imagen!==null">
			<v-layout>
				<v-flex xs12>
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
				                width="100%"
				                height="100%">
				              </v-img>
			              </center>
			            </v-card-text>
		                <v-card-actions>
		                	<v-btn :small="true" @click="$router.go(-1)">Volver</v-btn>
		            	</v-card-actions>
		            </v-card>
				</v-flex>
			</v-layout>
		</template>
	</div>
</template>

<script>

export default {
	name:'setImageMod',

	created()
	{
		this.findID(this.$route.params.id)
	},

	data()
	{
		return {
			imagen:null,
			imagenMod:null
		}
	},

	methods:{

		async findID(id)
		{
			let response = await axios.get('/find-image-id/'+id);
			if (response.data!==null) {
				this.imagen = response.data;
				this.imagenMod = '/imgModif/'+response.data.name;
			}
		}
	}
}

</script>