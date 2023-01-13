import Home from './components/Home.vue'
import ProfilePage from './components/ProfilePage.vue'
import Users from './components/Users.vue'
import NotFoundPage from './components/NotFoundPage.vue'

export default {
  mode: 'history',
  routes: [
    {
      path: '/home',
      component: Home,
    },
    {
      path: '/profile',
      component: ProfilePage,
    },
    {
      path: '/users',
      component: Users,
    },
    {
      path: '/:pathMatch(.*)*',
      component: NotFoundPage,
    },
  ]
}