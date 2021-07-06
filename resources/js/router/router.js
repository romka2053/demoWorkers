import Vue from "vue";
import VueRouter from "vue-router";
import Tree from  "./../view/tree/tree.vue";
import Workers from  "./../view/admin/workers.vue";
import Login from "./../view/auth/login.vue";
import Register from "./../view/auth/register.vue";
import IndexAdmin from "./../view/admin/index.admin.vue"
import Create from "./../view/admin/CreateEdit";
import Edit from "./../view/admin/CreateEdit";
import Loader from "./../view/loader.vue"
import ShowWorker from "./../view/admin/ShowWorker"

Vue.use(VueRouter);



const routes=[
    {path:"/",
     name:"Tree",
     component:Tree,

    },
    {
      path:"/admin",
      name:"IndexAdmin",
      component:  IndexAdmin,
        meta:{requiresAuth:true},
        children:[
            {   path:"workers",
                name:"Workers",
                meta:{requiresAuth:true},
                components:{
                    default:IndexAdmin,
                    MainView:Workers
                }
            },
            {
                path: "create",
                name: "Create",
                meta:{requiresAuth:true},
                components: {
                    default: IndexAdmin,
                    MainView: Create
                }
            },
            {
                path: "edit",
                name: "Edit",
                meta:{requiresAuth:true},
                components: {
                    default: IndexAdmin,
                    MainView: Edit
                }
            },
            {path: "/admin/tree",
                name: "AdminTree",
                meta:{requiresAuth:true},
                components: {
                    default: IndexAdmin,
                    MainView: Tree
                }


            }, {
                path: "/admin/show",
                name: "ShowWorker",
                meta: {requiresAuth: true},
                components: {
                    default: IndexAdmin,
                    MainView: ShowWorker
                }
            },

        ]


    },

    {path:"/login",
        name:"Login",
        component:Login,
        meta:{guest:true},
    },
    {path:"/register",
        name:"Register",
        component:Register,
        meta:{guest:true},
    },
    {path:"/loader",
        name:"Loader",
        component:Loader,

    },


]


function tokenIn()
{

    return localStorage.getItem('token');
}
const router =new VueRouter({

    mode:"history",
    routes

})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // этот путь требует авторизации, проверяем залогинен ли
        // пользователь, и если нет, перенаправляем на страницу логина
        if (!tokenIn()  ) {

            next({

                path: '/login',
                query: { redirect: to.fullPath }
            })
        } else {

            next()
        }
    } else if(to.matched.some(record => record.meta.guest)){
        if (tokenIn()) {
            next({
                path: '/admin',
                query: { redirect: to.fullPath }
            })
        }else {
            next()
        }

    }else
    {
        next() // всегда так или иначе нужно вызвать next()!

    }
})

export default router;

