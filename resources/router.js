import Vue from 'vue'
import Router from 'vue-router'
import HomePage from "./pages/HomePage.vue";
import ImagePage from "./pages/ImagePage.vue";
import ImageModPage from "./pages/ImageModPage.vue";
import ImagesPage from "./pages/ImagesPage.vue";
import NotFound from './pages/NotFound.vue'

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
			path: '/imagen/:id',
			name: 'setImage',
			component: ImagePage
		},
		{
			path: '/imagen/modificada/:id',
			name: 'setImageMod',
			component: ImageModPage
		},
		{
			path: '/imagenes',
			name: 'images',
			component: ImagesPage
		},
		{ path: '*', name: 'notFound', component: NotFound }
	]
});
