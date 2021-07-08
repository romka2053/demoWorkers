<template>
<div>
    <div v-if="!isLoading">
    <div id="app" class="main-page">
        <v-app id="inspire">
            <v-app id="inspire">

                <div v-if="drawer">
                <v-navigation-drawer

                    app
                >
                    <v-list dense>

                        <v-list-item >
                            <v-list-item-action>
                                <v-avatar
                                    color="primary"
                                    size="50"
                                >{{logo}}</v-avatar>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title> {{user.email}}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link :to="{name:'Workers'}">
                            <v-list-item-action>
                                <v-icon>mdi-account-group</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title> Сотрудники</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item link :to="{name:'Create'}">
                            <v-list-item-action>
                                <v-icon>mdi-account-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Добавление сотрудника</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link :to="{name:'ShowWorker'}">
                            <v-list-item-action>
                                <v-icon>mdi-account</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Просмотр</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link :to="{name:'AdminTree'}">
                            <v-list-item-action>
                                <v-icon>mdi-file-tree-outline</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Дерево сотрудников</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link @click="logout">
                            <v-list-item-action>
                                <v-icon color="red">mdi-power</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title class="red--text">Выйти из аккаунта</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
                </div>
                <v-app-bar
                    app
                    color="indigo"
                    dark
                >
                    <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
                    <v-toolbar-title>Application</v-toolbar-title>
                </v-app-bar>

                <v-main>
                    <v-container
                        class="fill-height"
                        fluid
                    >

                        <v-row
                            align="center"
                            justify="center"
                        >
                            <v-col >
                               <router-view class="main-view" name="MainView"></router-view>
                            </v-col>
                        </v-row>


                    </v-container>
                </v-main>
                <v-footer
                    color="indigo"
                    app
                >
                    <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
                </v-footer>
            </v-app>
        </v-app>
    </div>
    </div>
    <div v-else>

        <LoaderPage></LoaderPage>
    </div>
</div>



</template>

<script>
import LoaderPage from "./../loader.vue"
export default {
components:{


    LoaderPage
},
    data(){
        return{
            token:localStorage.getItem('token'),
            isLoading:true,
           user:{},
            logo:'',
            drawer:true

        }
    },
created(){
    axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;
this.getUser();
},
    methods:{

        getUser()
        {
          axios
          .get('/api/user')
            .then(response=>{
            this.user=response.data
            this.logo=this.user.name[0]

        })
            .finally(()=>{
                    this.isLoading=false


            })
        },
        logout(){
            axios
            .post('/api/logout')
            .then(response=>{
                localStorage.removeItem('token');
                this.$router.push({name:'Tree'})

            })
            .catch(err=>{
                console.log(errors.response.data)

            })

        }



    }
}
</script>

<style scoped>

</style>
