<template>
    
    <div class="content">
      <div class="container">
          <div
          class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-2">
          <div class="login_header">
            <h4>Login to Dashboard</h4>
          </div>

        <Input
          v-model="data.email"
          class="my-3"
          type="text"
          prefix="md-mail"
          placeholder="User Email"
        />
        <Input
          v-model="data.password"
          class="my-3"
          type="password"
          prefix="md-finger-print"
          placeholder="Password"
        />

        <div class="login-footer">
            <Button @click="login" :disabled="isLogging" :loading="isLogging" type="primary" >{{isLogging ? 'Loging ...' : 'Login'}}</Button>
        </div>
 
          
         </div>
      </div>
    </div>

</template>

<script>
export default {
    data(){
        return {
            data : {
                email: '',
                password: ''
            },
            isLogging: false,
        }
    },

    methods : {
    
     async login(){
        if (this.data.email.trim() == "")
            return this.info("Email can not be empty");
        if (this.data.password.trim() == "")
            return this.info("Password can not be empty");
        if (this.data.password.length  < 6 )
            return this.info("Password must be 6 characters");
        this.isLogging = true;    

            const res = await this.callApi("post", "app/admin_login", this.data)
            if(res.status === 200){
                this.success(res.data.msg);
                window.location = '/';
            }else {
                if(res.status === 401){
                    this.error(res.data.msg);
                }else if (res.status === 422) {
                    for(let i in res.data.errors){
                    this.error(res.data.errors[i][0])
                    }
                }
                else {
                    this.errorD();
                }
            }

         this.isLogging = false;

        }

    }
}
</script>

<style lang="css" scoped>
.content {
    background-color: #000000;
    background: url("../bg/bg.jpg");
    background-repeat: no-repeat;
    background-position: center cover;
    min-height: 100vh;
    margin-left: 0;
    
}
._1adminOverveiw_table_recent {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 1px solid rgba(2, 2, 2, 0.76);
    padding: 10px;
    border-radius: 5px;
}
.login_header {
    text-align : center;
    font-weight : 400;
    margin-top: 8px;
    color:black;
}
.login-footer {
    text-align : center;
}

</style>