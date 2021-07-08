<template>
<div>
    <v-app>
                <v-card>
                    <v-toolbar
                        color="primary"
                        dark
                        flat
                    >
                        <v-toolbar-title>Добавление сотрудника</v-toolbar-title>


                    </v-toolbar>

                    <v-row>
                        <v-col cols="3">
                                <div v-if="formData.uploadFileData">
                                    <img :src="formData.uploadFileData" class="preview-image">
                                    <v-btn @click="uploadImage" >Загрузить</v-btn>
                                </div>
                                <div v-else>
                                    <img src="/images/not_avatar.jpg" class="preview-image">

                                </div>

                        </v-col>
                        <v-col cols="6">
                            <v-card-text >
                                <v-form >
                                    <v-file-input

                                        @change="onFieldChange"
                                        accept="image/png, image/jpeg, image/bmp"
                                        placeholder="Pick an avatar"
                                        prepend-icon="mdi-camera"
                                        label="Avatar"
                                    ></v-file-input>

                                    <v-text-field v-model="worker.name"
                                                  label="ФИО"
                                                  prepend-icon="mdi-account"
                                                  type="text"

                                    ></v-text-field>
                                    <v-text-field v-model="worker.post"
                                                  label="Должность"
                                                  prepend-icon="mdi-account"
                                                  type="text"

                                    ></v-text-field>

                                    <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="worker.device_date"
                                                label="Birthday date"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="worker.device_date"
                                            :active-picker.sync="activePicker"
                                            :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                                            min="1950-01-01"
                                            @change="save"
                                        ></v-date-picker>
                                    </v-menu>


                                    <v-text-field v-model="worker.salary"
                                                  label="Зарплата"
                                                  prepend-icon="mdi-account"
                                                  type="text"

                                    ></v-text-field>
                                    <v-text-field
                                        @click="openDialogTree"
                                        readonly
                                        v-model="worker.parent_id"
                                                  label="Начальник"
                                                  prepend-icon="mdi-account"
                                                  type="text"

                                    ></v-text-field>


                                </v-form>
                            </v-card-text>
                            </v-col>
                    </v-row>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text

                        >
                          Отмена
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="createWorker"
                        >
                            Сохранить
                        </v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>


        <v-dialog v-model="dialogTree" max-width="700px">
            <v-card>

                <v-card-actions>


                    <v-container>
                        <v-card>

                            <v-card-title>Выберите сотрудника</v-card-title>
                            <v-btn  @click="goParent(null)">Нет начальника</v-btn>
                            <v-card-text>

                                <v-treeview
                                    activatable
                                    :items="workers" :load-children="loadTree"

                                >
                                    <template slot="prepend" slot-scope="{item,open}">
                                        <v-col ><v-icon @click="goParent(item)" color="green"> mdi-account-check-outline</v-icon> {{item.post}}</v-col>


                                    </template>
                                </v-treeview>
                            </v-card-text>
                        </v-card>
                    </v-container>


                    <v-spacer></v-spacer>

                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-app>

</div>
</template>

<script>
export default {




    data(){
        return{
            token:localStorage.getItem('token'),
            dialogTree: false,
            selected:[],
            worker:{
                id:'',
                name:'',
                post:'',
                device_date:'',
                salary:'',
                parent_id:'',
            },
            workers: [],
            menu: false,
            activePicker: null,
            formData:{
                uploadFileData:null,
                file:null
            },
            errors: {},
            parentworker:{},


        }


    },
    created() {

        axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;

    },
    watch: {
        menu (val) {
            val && setTimeout(() => (this.activePicker = 'YEAR'))
        },
    },
    methods:{
        goParent(item){

            if(item) {

                this.parentworker = item
                this.worker.parent_id = this.parentworker.post + ' ' + this.parentworker.name

            }else
            {
                this.parentworker={}
                this.worker.parent_id =''
            }
            this.dialogTree = false
        },
        loadTree(item)
        {
            return  axios
                .get('/api/tree/',{params:{id:item.id}})
                .then(response=> {
                        let tmpItem=response.data.map(childItem=>{
                            if(childItem.children!='')
                            {
                                childItem.children=[]

                            }else
                            {

                                delete childItem.children
                            }
                            return childItem;

                        })
                        item.children.push(...tmpItem)

                    }
                )



        },
        openDialogTree(){
        this.getWorkers();
        this.dialogTree=true


        },

        getWorkers(){

            axios
                .get('/api/tree/')
                .then(response =>{
                    this.workers=response.data
                    for(var i=0;i<response.data.length;i++)
                    {
                        if(this.workers[i].children!='')
                        {
                            this.workers[i].children=[];

                        }else
                        {

                            delete this.workers[i].children
                        }

                    }

                })
                .finally(()=>{
                    this.isLoading=false


                })

        },
        save (date) {
            this.$refs.menu.save(date)
        },
        createWorker(){

            let data=new FormData()
            if(this.formData.file) {
                data.append('fupload', this.formData.file)
            }
            data.append('name',this.worker.name)
            data.append('post',this.worker.post)
            data.append('device_date',this.worker.device_date)
            data.append('salary',this.worker.salary)

            if(this.parentworker.id) {
                data.append('parent_id',this.parentworker.id)
            }

            axios

                .post('/api/workers',data)
                .then(response=>{
                    this.worker.name=''
                    this.worker.post=''
                    this.worker.device_date=''
                    this.worker.salary=''
                    this.worker.parent_id=''

                    this.$router.push({name:'Workers'})
                })
            .catch(err=>{   this.errors=err.response.data.errors
            console.log(this.errors)
            })

        },
        onFieldChange(event){
            if(event)
            {

                let file=event
                this.formData.file=file
                let reader =new FileReader()
                reader.onload=e=>{
                    this.formData.uploadFileData=e.target.result
                }
                reader.readAsDataURL(file)
            }


        },
        uploadImage(){

        }


    }
}
</script>

<style scoped>
.input-field-file{
    display: none;

}
.preview-image{
    float: left;
    margin: 10px;
    width:250px;
    padding: 15px;
    border: 1px solid #999;
    border-radius: 5px;
}
</style>
