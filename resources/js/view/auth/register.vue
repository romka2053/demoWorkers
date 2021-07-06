<template>
    <div id="app">
        <v-app id="inspire">
            <v-app id="inspire">
                <v-main>
                    <v-container
                        class="fill-height"
                        fluid
                    >
                        <v-row
                            align="center"
                            justify="center"
                        >
                            <v-col
                                cols="12"
                                sm="8"
                                md="4"
                            >
                                <v-card class="elevation-12" :loading="isLoading">
                                    <v-toolbar
                                        color="primary"
                                        dark
                                        flat
                                    >
                                        <v-toolbar-title>Регистрация</v-toolbar-title>


                                    </v-toolbar>
                                    <v-card-text>
                                        <v-form ref="form">
                                            <v-text-field v-model="user.name"
                                                label="Имя"
                                                prepend-icon="mdi-account"
                                                type="text"
                                                :error-messages="errors.name"
                                            ></v-text-field>
                                            <v-text-field
                                                v-model="user.email"
                                                label="Email"
                                                prepend-icon="mdi-at"
                                                type="text"
                                                :error-messages="errors.email"
                                            ></v-text-field>

                                            <v-text-field
                                                v-model="user.password"
                                                label="Пароль"
                                                prepend-icon="mdi-lock"
                                                type="password"
                                                :error-messages="errors.password"
                                            ></v-text-field>

                                            <v-text-field
                                                v-model="user.password_confirmation"
                                                label="Подтверждение пароля"

                                                prepend-icon="mdi-lock-reset"
                                                type="password"
                                                :error-messages="errors.password_confirmation"
                                            ></v-text-field>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions >
                                        <router-link :to="{name:'Login'}" class="ml-3">У вас уже есть акаунт?</router-link>
                                        <v-spacer></v-spacer>
                                        <v-btn  @click="Registration" color="primary">Регистрация</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-main>

                <v-snackbar v-model="snackbar" top color="primary" timeout="5000"  >
                  Вы успешно зарегистрировались

                    <template v-slot:action="{ attrs }">
                        <v-btn

                            color="white"
                            text
                            v-bind="attrs"
                            @click="goLogin"
                        >
                            Войти
                        </v-btn>
                    </template>
                </v-snackbar>

            </v-app>
        </v-app>
    </div>
</template>

<script>

export default{
    data(){
        return{
            isLoading:false,
            snackbar:false,
            user:{
                name:'',
                email:'',
                password:'',
                password_confirmation:'',
                device_name:'browser'


            },
            errors: {},
            workers:[],
            worker:{
                id:'',
                name:'',
                post:'',
                device_date:'',
                salary:'',
                parent_id:''
            },


        }

    },

    methods:{


        goLogin()
        {
            this.snackbar=false
            this.$router.push('/login')

        },
        Registration(){
            this.isLoading="red"
            axios
                .post('api/register',{
                    name:this.user.name,
                    email:this.user.email,
                    password:this.user.password,
                    password_confirmation:this.user.password_confirmation,


                })
                .then(response=> {

                   this.snackbar=true;
                  this.$refs.form.reset()
                    this.errors={}
                } )


                .catch(err => {
                    this.errors=err.response.data.errors

                })
            .finally(()=>{this.isLoading=false})


        }

    },



}
</script>

<style scoped>

</style>
