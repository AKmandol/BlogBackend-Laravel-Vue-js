import axios from "axios";
import { Button } from 'view-ui-plus';
import { mapGetters } from "vuex";

export default{
    components: {
        Button,
      },
    data(){
        return{
           
        };
        
    },

    methods: {
       async callApi(method, url,dataObj){
            try{
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e){
                console.log(e)
               return e.response
            }
        
       },

      handleError(res, file) {
        this.$Notice.warning({
          title: "The file format is incorrect",
          desc: `${
            file.errors.file.length
              ? file.errors.file[0]
              : "Something went wrong!"
          }`,
        });
      },
      handleFormatError(file) {
        this.$Notice.warning({
          title: "The file format is incorrect",
          desc:
            "File format of " +
            file.name +
            " is incorrect, please select jpg or png.",
        });
      },
      handleMaxSize(file) {
        this.$Notice.warning({
          title: "Exceeding file size limit",
          desc: "File  " + file.name + " is too large, no more than 2M.",
        });
      },


            info (desc,title="Sorry") {
                this.$Notice.info({
                    title: title,
                    desc: desc
                });
            },
            success (desc,title="Great!") {
                this.$Notice.success({
                    title: title,
                    desc: desc
                });
            },
            warning (desc,title="Sorry") {
                this.$Notice.warning({
                    title: title,
                    desc: desc
                });
            },
            error (desc,title="Sorry") {
                this.$Notice.error({
                    title: title,
                    desc: desc
                });
            },
            errorD (desc="Something Went Worng! Try Again",title="Sorry") {
                this.$Notice.error({
                    title: title,
                    desc: desc
                });
            },

            checkPermission(key){
                //console.log(this.$route.name)
                if(!this.userPermission){
                    return true;
                }

                let isPermited = false;

                for(let d of this.userPermission){
                    //console.log(d[key])
                    //console.log(this.$route.name);
                    if(this.$route.name == d.name){
                        if(d[key]){
                            isPermited = true;
                            break;
                        }else {
                            break;
                        }
                    }
                }
                
                return isPermited;
                //console.log(this.$route.name);
            }
        
        },

        computed: {

            ...mapGetters({
                'userPermission' : 'getUserPermission'
            }),

            isReadPermitted(){
                //console.log('permission is',this.userPermission);
                return this.checkPermission('read');
            },
            isWritePermitted(){
                return this.checkPermission('write');
            },
            isUpdatePermitted(){
                return this.checkPermission('update');
            },
            isDeletePermitted(){
                return this.checkPermission('delete');
            },

        },




    };
    




