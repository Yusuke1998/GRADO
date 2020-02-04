import Vue from 'vue'
import Router from 'vue-router'
import HomePage from "./pages/HomePage.vue";
import ImagesPage from "./pages/ImagesPage.vue";

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'home',
			component: HomePage
		},
		{
			path: '/imagenes',
			name: 'images',
			component: ImagesPage
		}
	]
});
