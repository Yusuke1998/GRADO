<template>
	<div class="my-12">
		<v-container>
			<v-layout>
				<v-flex xs12>
					<h4 class="text-center">Todas las Imagenes</h4>
				</v-flex>
			</v-layout>

			<v-layout>
				<v-flex xs12>
					<v-container>
						<v-row 
							align-content-xs="center" 
							no-gutters>
					      <v-col
					        v-for="n in 12" :key="n"
					        cols="12"
					        sm="4">
					        <v-card
					        	class="pa-2 ma-2"
					        	outlined
					        	tile>
					        	<v-card-title>
					        		<p>Imagen {{n}}</p>
					        	</v-card-title>
					        	<v-card-text>
					         		<v-img></v-img>
					         	</v-card-text>
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
	mounted()
	{

	},

	data()
	{

		return {

		}
	},

	methods:{

	}
}

</script>