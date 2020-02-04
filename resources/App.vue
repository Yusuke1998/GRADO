<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
    >
      <v-list dense>
        <v-list-item link :to="{ name: 'home' }">
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Inicio</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link :to="{ name: 'images' }">
          <v-list-item-action>
            <v-icon>mdi-eye</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Imagenes</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>PicPixReader</v-toolbar-title>
    </v-app-bar>

    <v-content>
		<v-container>
			<transition name="fade">
				<router-view/>
			</transition>
		</v-container>
	</v-content>

    <v-footer app>
      <span>&copy; yusuke1998 - 2020</span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: null,
    }),
    created () {
      this.$vuetify.theme.dark = false
    }
  }
</script>