<template>
    <v-app>



            <v-container fluid>


                <v-card min-height="300px" :loading="isLoading">
                    <v-card-title >

                        <v-text-field @keypress.enter="goSearch"
                            v-model="searched"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details

                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                        :headers="headers"
                        :items="workers"

                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        hide-default-footer
                        class="elevation-1"
                        @page-count="pageCount = $event"

                    >
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

                        <v-pagination
                            :total-visible="10"
                            v-model="page"
                            :length="pageCount"
                        ></v-pagination>

                </v-card>

            </v-container>

    </v-app>
</template>

<script>
export default {
    data () {
        return {
            token:localStorage.getItem('token'),
            isLoading:'red',
            page: 1,
            pageCount: 0,
            itemsPerPage: 20,
            search: '',
            searched:'',
            headers: [
                {text: 'ID', value: 'id', },
                { text: 'ФИО', value: 'name' },
                { text: 'Должность', value: 'post' },
                { text: 'Дата устройства', value: 'device_date' },
                { text: 'Зарплата', value: 'salary' },
                { text: 'Начальник', value: 'parent_id' },
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            workers:[]

        }
    },


    created() {

        axios.defaults.headers.common['Authorization']=`Bearer ${this.token}`;
        this.getWorkers()
    },
    methods: {
        goSearch(){




        },

        getWorkers() {


            axios

                .get('/api/workers')
                .then(response => {
                    this.workers=response.data;



                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;

                })
                .finally(() => this.isLoading = false)

        },
    }
}
</script>

<style scoped>

</style>
