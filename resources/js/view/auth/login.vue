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
                                        <v-toolbar-title>Вход</v-toolbar-title>

                                    </v-toolbar>
                                    <v-card-text>
                                        <v-form ref="form">
                                            <v-text-field
                                                v-model="user.email"
                                                label="Email"
                                                prepend-icon="mdi-at"
                                                type="text"
                                                :error-messages="errors.email"
                                            ></v-text-field>

                                            <v-text-field v-model="user.password"
                                                label="Пароль"
                                                prepend-icon="mdi-lock"
                                                type="password"
                                                 :error-messages="errors.password"
                                            ></v-text-field>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions>
                                        <router-link :to="{name:'Register'}" class="ml-3">Зарегистрироваться</router-link>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" @click="doLogin">Войти</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-main>

                <v-snackbar v-model="snackbar" top color="red" timeout="5000" >
                    Введите правлиный Email и Пароль

                </v-snackbar>

            </v-app>
        </v-app>
    </div>
</template>

<script>
export default {
    data() {
        return {
            snackbar:false,
            isLoading:false,
            user: {
                email: '',
                password: '',
                device_name: 'browser'


            },
            errors: {},
        }

    },
    methods:{
        doLogin(){
            this.isLoading='red'
            axios
                .post('/api/login',{
                    email:this.user.email,
                    password:this.user.password,
                    device_name:this.user.device_name



                })
                .then(response=>{
                    localStorage.setItem('token',response.data)
                    this.$refs.form.reset()
                    this.$refs.form.resetValidation();
                    this.$router.push({name:'IndexAdmin'})
                    this.errors={}
                })
                .catch(err => {
                    this.snackbar=true;
                    this.isLoading=false
                    this.$refs.form.reset()
                    this.errors=err.response.data.errors
                })
                .finally(()=>{this.isLoading=false})

        },


    },

}
</script>

<style scoped>

</style>
