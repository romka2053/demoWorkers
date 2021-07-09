<template>
    <v-app>

            <v-container>
                <v-card>

                    <v-card-title>Древо сотрудников</v-card-title>
                    <v-card-text>
                        <v-treeview
                            activatable
                            :items="workers" :load-children="loadTree">
                            <template slot="prepend" slot-scope="{item,open}">
                                <v-col > {{item.post}}</v-col>


                            </template>
                        </v-treeview>
                    </v-card-text>
                </v-card>
            </v-container>


    </v-app>

</template>
<script>
export default {
    data() {
        return {
            workers: [],
        }
    },
    created() {

        axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;
        this.getWorkers()
    },
    methods:{

        loadTree(item)
        {
            return  axios
                .get('/api/tree/',{params:{id:item.id}})
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
        getWorkers(){

            axios
                .get('/api/tree/')
                .then(response =>{
                    this.workers=response.data.data
                    for(var i=0;i<response.data.data.length;i++)
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
        open(){


        }
    }


}



</script>
