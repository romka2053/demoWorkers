<template>
    <v-app>



        <v-container fluid>


            <v-card min-height="300px" >
                <v-card-title >

                    <v-text-field @keypress.enter="getWorkers"
                                  v-model="search"
                                  append-icon="mdi-magnify"
                                  label="Search"
                                  single-line
                                  hide-details

                    ></v-text-field>
                </v-card-title>
                <v-data-table
                    :footer-props ="footerProps"
                    :headers="headers"
                    :items="workers"
                    :options.sync="options"
                    :server-items-length="totalWorker"
                    :loading="isLoading"

                    class="elevation-1"


                >
                    <template v-slot:item.avatar="{ item }" >
                        <img v-if="item.urlImage" :src="'/images/'+item.urlImage"style="width: 100px;float:left;padding:10px;" />
                        <img v-else src="/images/not_avatar.jpg"style="width: 100px;float:left;padding:10px;" />
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteItem(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>

                <v-dialog v-model="dialogTree" max-width="700px">
                    <v-card>

                        <v-card-actions>


                            <v-container>
                                <v-card>

                                    <v-card-title>Выберите нового начальника для подчиненных</v-card-title>

                                    <v-card-text>

                                        <v-treeview
                                            activatable
                                            :items="workersTree" :load-children="loadTree"

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

                <v-dialog v-model="dialogdelete" max-width="700px">
                    <v-card>

                        <v-card-actions>


                            <v-container>
                                <v-card>

                                    <v-card-title>Удалить сотрудника {{deleteworker.name}} ?</v-card-title>

                                    <v-card-text>
                                        Начальником для подчиненных станет {{parentworker.name}}

                                    </v-card-text>
                                    <v-btn @click="DeleteItemTrue">Удалить</v-btn>
                                    <v-btn @click="CancelDelete">Отмена</v-btn>
                                </v-card>
                            </v-container>


                            <v-spacer></v-spacer>

                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>


            </v-card>

        </v-container>

    </v-app>
</template>

<script>
export default {
    data () {
        return {
            dialogdelete:'',
            deleteworker:'',
            parentworker:'',
            footerProps: {'items-per-page-options': [15, 30, 50, 100]},
            token:localStorage.getItem('token'),
            page:[5,10, 15],
            dialogTree:false,
            totalWorker:0,
            isLoading:false,
            options:{},
            search: '',
            sort:'',
            searched:'',
            headers: [
                {text: 'ID', value: 'id', },
                {text: 'Аватарка', value: 'avatar', sortable: false,},
                { text: 'ФИО', value: 'name' },
                { text: 'Должность', value: 'post' },
                { text: 'Дата устройства', value: 'device_date' },
                { text: 'Зарплата', value: 'salary' },
                { text: 'Начальник', value: 'parent_id' },
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            workers:[],
            workersTree:[],
            getUrlImage:null,

        }
    },

    watch: {
        options: {
            handler () {
                this.getWorkers()

            },
            deep: true,
        },
    },
    created() {

        axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;
        this.getWorkers()
    },
    methods: {


        getWorkers() {
            this.isLoading=true

            if (this.options.sortBy.length === 1 ) {
                if(this.options.sortBy[0]){
                    if(this.options.sortDesc[0]==true)
                    {

                        this.sort='-'+this.options.sortBy[0]

                    }else
                    {

                        this.sort=this.options.sortBy[0]

                    }
                }}
            else
            {
                this.sort=null
            }



            axios

                .get('/api/workers?page='+this.options.page,{params:{s:this.search,h:this.sort,p:this.options.itemsPerPage}})
                .then(response => {

                    this.workers=response.data.data;


                    this.totalWorker=response.data.total



                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;

                })
                .finally(() => this.isLoading = false)

        },
        deleteItem(item){
            this.deleteworker=item
            this.getWorkersTree();
            this.dialogTree=true



        },
        getWorkersTree(){

            axios
                .get('/api/treedel/',{params:{delete_id:this.deleteworker.id}})
                .then(response =>{

                    this.workersTree=response.data.data
                    for(var i=0;i<this.workersTree.length;i++)
                    {
                        if(this.workersTree[i].children!='')
                        {
                            this.workersTree[i].children=[];

                        }else {

                            delete this.workersTree[i].children

                        }

                    }

                })
                .finally(()=>{
                    this.isLoading=false


                })

        },
        loadTree(item)
        {
            return  axios
                .get('/api/treedel/',{params:{id:item.id,delete_id: this.deleteworker.id}})
                .then(response=> {
                        let tmpItem=response.data.data.map(childItem=>{

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
        goParent(item){
            this.dialogTree=false
            this.dialogdelete=true
            this.parentworker=item

        },

        DeleteItemTrue(){
            axios
                .put('/api/workers/parent',{
                    deleteid:this.deleteworker.id,
                    parentid:this.parentworker.id
                })
                .then(response=>{
                    axios
                        .delete(`/api/workers/${this.deleteworker.id}`)
                        .then(response=>{
                            this.dialogdelete=false
                            this.getWorkers()})
                        .catch(error=>console.log(error))

                })

        },
        CancelDelete(){

            this.dialogdelete=false
            this.dialogTree=true
            this.parentworker={}

        },
        editItem(item){

            if(item.urlImage)
            {
               this.getUrlImage='/images/'+item.urlImage
            }else
            {
                this.getUrlImage=null
            }
            this.$router.push({name:'Edit',params:{id:item.id,name:item.name,post:item.post,device_date:item.device_date,
                salary:item.salary,parent_id:item.parent_id,urlImage:this.getUrlImage
                }})


        }

    }
}
</script>

<style scoped>

</style>
