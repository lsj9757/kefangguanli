const routers = [
{
    path: '/register',
    component: (resolve) => require(['./views/register.vue'], resolve)
},
{
    path: '/',
    component: (resolve) => require(['./views/login.vue'], resolve)
},
{
	path: '/index',
    component: (resolve) => require(['./views/index.vue'], resolve)
},
{
	name: 'control',
	path: '/control',
	component: (resolve) => require(['./views/control.vue'], resolve)
}];
export default routers;