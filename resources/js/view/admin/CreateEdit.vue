<template>
<div>
   <v-btn @click="onButtonClick">

       <v-icon>mdi-attachment</v-icon>
       Изменить фото
   </v-btn>
    <v-text-field
        v-model="formData.displayFileName"
        readonly
    ></v-text-field>
<input type="file" class="input-field-file" ref="fupload" @change="onFieldChange">
    <div v-if="readyToUpload">
    <img :src="formData.uploadFileData" class="preview-image">
        <v-btn @click="uploadImage" >Загрузить</v-btn>
    </div>
</div>
</template>

<script>
export default {
    computed:{

        readyToUpload(){
            return this.formData.displayFileName && this.formData.uploadFileData

        }
    },

    data(){
        return{
            formData:{
                displayFileName:null,
                uploadFileData:null,
                file:null
            }

        }


    },

    methods:{
        onFieldChange(event){
            if(event.target.files && event.target.files.length)
            {

                let file=event.target.files[0]
                this.formData.file=file
                this.formData.displayFileName=event.target.files[0].name+'('+this.calcSice(file.size)+'kb)'
                let reader =new FileReader()
                reader.onload=e=>{
                    this.formData.uploadFileData=e.target.result
                }
                reader.readAsDataURL(file)
            }


        },
        onButtonClick(){
this.$refs.fupload.click()

        },
        calcSice(size){
            return Math.round(size/1024)

        },
        uploadImage(){
            let data=new FormData()
            data.append('fupload',this.formData.file)
            data.append('id','2')

            axios
                .post('/api/upload_file',data)
            .then(response=>{
                console.log('respone.data')


            })
            .catch(err=>{console.log('err.response')})


        }


    }
}
</script>

<style scoped>
.input-field-file{
    display: none;

}
.preview-image{

    width:250px;
    padding: 15px;
    border: 1px solid #999;
    border-radius: 5px;
}
</style>
