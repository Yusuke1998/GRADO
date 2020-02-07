<template>
	<div class="my-12">
		<v-container>
			<v-layout v-if="images.length > 0">
				<v-flex xs2 offset-xs10>
	            	<v-select class="text-xs-right" label="Cambiar" v-bind:items="types" v-model="type" overflow></v-select>
	          </v-flex>
			</v-layout>
			<v-layout>
				<v-flex xs12>
					<v-container>
						<v-row
							no-gutters>
					      <v-col v-if="images.length == 0"
					      	cols="12">
					        <v-card
					        	class="pa-2 ma-2"
					        	outlined
					        	tile>
					        	<v-card-title>
					        		<p>No hay Imagenes</p>
					        	</v-card-title>
					        </v-card>
					      </v-col>
					      <v-col
					      	v-else
					        v-for="(image,idx) in images"
					        :key="idx"
					        cols="12"
					        sm="4">
					        <v-card
					        	link :to="{ path: '/imagen/'+image.id }"
					        	class="pa-2 ma-2"
					        	outlined
					        	tile>
					        	<v-card-title>
					        		<center>
					        			<p>Imagen {{image.name}}</p>
					        		</center>
					        	</v-card-title>
					        	<v-card-text>
					        		<center>
						         		<v-img 
						         			:src="type+image.name"
						         			width="50%"
				                			height="50%">
				                		</v-img>
					        		</center>
					         	</v-card-text>
					         	<v-card-actions>
		                			<v-btn @click="destroyImage(image.id)">Eliminar</v-btn>
					         	</v-card-actions>
					        </v-card>
					      </v-col>
					    </v-row>
					</v-container>
				</v-flex>
			</v-layout>
		</v-container>
	</div>
</template>

<script>

export default {
	created()
	{
		this.allImages();
	},

	data()
	{

		return {
			images:[],
			types:[
	          { text: 'Originales', value: '/imgOrigi/' },
	          { text: 'Modificadas', value: '/imgModif/' },
	        ],
			type:'/imgOrigi/'
		}
	},

	methods:{

		async allImages() {
			let response = await axios.get('/all-images');
			this.images = response.data;
		},

		destroyImage(id)
		{
			let url = '/delete-image/'+id;
			swal({
				title: "Seguro de eliminar?",
				text: "Una vez borrada, no la puedes recuperar!",
				icon: "warning",
				buttons: true,
				dangerMode: true
			}).then(yes => {
				if (yes) {
					axios.get(url)
					.then(rsp=>{
						this.$router.push({name:'home'})
						utils.reload();
					});
				}
			});
		}
		
	}
}

</script>