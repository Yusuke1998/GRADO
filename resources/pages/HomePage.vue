<template>
	<div>
		<!-- carga -->
		<v-layout>
			<v-flex xs12>
				<v-card 
					class="mx-auto mt-5"
					:loading="false"
					:shaped="true">
					<v-card-text>
						<v-file-input
							:dense="true"
							color="secondary accent-4"
						    counter
						    label="Carga de Imagen"
						    multiple
						    placeholder="Seleccion una imagen valida"
						    prepend-icon="mdi-paperclip"
						    outlined
							:loading="true">
						</v-file-input>
					</v-card-text>
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

		}
	},

	methods:{

	}
}

</script>