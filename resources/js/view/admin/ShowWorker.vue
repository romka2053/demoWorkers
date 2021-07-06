<template>
    <div>
        <v-text-field
            @keypress.enter="getWorker"
            v-model="findId"
                      label="Введите id сотрудника"
                      prepend-icon="mdi-account"
                      type="number"

        ></v-text-field>
        <div v-if="!loading">
                <v-card-title >{{worker.name}}</v-card-title>

                <v-card


                    class="mx-auto my-12"

                    min-height="400"
                >
                                <img :src="'/images/'+form.imageUrl" class="preview-image">
                    <v-card-text>


                        <h3 >
                          Должность: {{worker.post}}
                        </h3>

                        <h3>
                          Дата устройства: {{worker.device_date}}
                        </h3>
                        <h3>
                            Зарплата: {{worker.salary}} BYN
                        </h3>
                        <h3>
                            Начальник: {{worker.parent_id}}
                        </h3>
                    </v-card-text>


                    </v-card>
        </div>
        <div v-else>


            <h1 v-if="!errored">Выберите сотрудника</h1>
            <h1 v-else>Сотрудник с таким id не найден</h1>
        </div>

    </div>
</template>

<script>
export default {
data(){
    return{
        token:localStorage.getItem('token'),
        findId:'',
        loading:true,
        errored:false,
        form:{
     imageUrl:null,
            },
 worker:{},
    }
},

    created() {
        axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;


    },
    methods:{
        getWorker() {
            if (this.findId) {
                axios
                    .get(`/api/workers/${this.findId}`)
                    .then(response => {

                        this.loading = true
                        if (!response.data.urlImage) {

                            this.form.imageUrl = "not_avatar.jpg"
                        } else {

                            this.form.imageUrl = response.data.urlImage
                        }


                        if (!response.data) {
                            this.errored = true;
                            this.worker = {}
                            this.loading = true
                        } else {
                            this.worker = response.data

                            this.errored = false;
                            this.loading = false
                        }
                    })
                    .catch(err => this.loading = true)

            }else
            {this.errored = false;
                this.worker = {}
                this.loading = true

            }

        }
    }
}
</script>

<style scoped>
.preview-image{
    float: left;
    margin:10px;
    width:250px;
    padding: 15px;
    border: 1px solid #999;
    border-radius: 5px;
}
</style>
