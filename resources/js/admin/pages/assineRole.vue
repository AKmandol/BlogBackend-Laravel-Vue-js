<template>
  <div>
    <div class="content">
      <div class="container-fluid mt-3">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="
            _1adminOverveiw_table_recent
            _box_shadow
            _border_radious
            _mar_b30
            _p20
          "
        >
          <p class="_title14">

            <span>Role Assigned For: </span>
            <Select @on-change="changeAssign" color="black" style="width:350px" class="custom ivu-ml" v-model="data.id" prefix="md-person" placeholder="Select Admin Type" >
                <Option :value="r.id" v-for="(r,i) in roles" :key="i" >{{r.roleName}}</Option>
            </Select>

            <Button v-if="isWritePermitted" type="primary" class="ivu-ml" :loading="isLoading" :disabled="isLoading" @click="assingRoles"> <Icon type="md-add" /> {{ isLoading ? "Role Adding..." : "Assign Role" }}</Button>
              
              <a style="float:right">
                <Icon @click="reload" color="green" size="27" type="ios-refresh-circle-outline" />
              </a>

          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>Role For</th>
                <th>Read Permission</th>
                <th>Write Permission</th>
                <th>Update Permission</th>
                <th>Delete Permission</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(r,t) in resources" :key="t">
                <td style="color:black">{{r.resourceName}}</td>
                <td> <Checkbox v-model="r.read"></Checkbox> </td>
                <td> <Checkbox v-model="r.write"></Checkbox> </td>
                <td> <Checkbox v-model="r.update"></Checkbox> </td>
                <td> <Checkbox v-model="r.delete"></Checkbox> </td>
              </tr>
            </table>
          </div>
        </div>
        <Page :total="100" />
      </div>

      <!-- Modal start -->

      

      <!-- Modal End -->

    </div>
  </div>
</template>

<script>


export default {
  data() {
    return {
      data: {
        id: null,
      },

      isLoading: false,

      roles: [],

      resourcesPermissionDefault: [
        {resourceName: 'Home', icon:'ios-home', read:false, write:false, update:false, delete:false, name:'/'},
        {resourceName: 'Tags', icon:'ios-pricetags', read:false, write:false, update:false, delete:false, name:'/tags'},
        {resourceName: 'Category', icon:'logo-instagram', read:false, write:false, update:false, delete:false, name:'/category'},
        {resourceName: 'Blog Post', icon:'md-clipboard', read:false, write:false, update:false, delete:false, name:'/blogs'},
        {resourceName: 'Create Blog', icon:'md-repeat', read:false, write:false, update:false, delete:false, name:'/createBlog'},
        {resourceName: 'Admin User', icon:'ios-contact', read:false, write:false, update:false, delete:false, name:'/adminuser'},
        {resourceName: 'Role', icon:'ios-cog', read:false, write:false, update:false, delete:false, name:'/role'},
        {resourceName: 'Assigned Role', icon:'md-aperture', read:false, write:false, update:false, delete:false, name:'/assineRole'},
      ],
    

      resources: [
        {resourceName: 'Home', icon:'ios-home', read:false, write:false, update:false, delete:false, name:'/'},
        {resourceName: 'Tags', icon:'ios-pricetags', read:false, write:false, update:false, delete:false, name:'/tags'},
        {resourceName: 'Category', icon:'logo-instagram', read:false, write:false, update:false, delete:false, name:'/category'},
        {resourceName: 'Blog Post', icon:'md-clipboard', read:false, write:false, update:false, delete:false, name:'/blogs'},
        {resourceName: 'Create Blog', icon:'md-repeat', read:false, write:false, update:false, delete:false, name:'/createBlog'},
        {resourceName: 'Admin User', icon:'ios-contact', read:false, write:false, update:false, delete:false, name:'/adminuser'},
        {resourceName: 'Role', icon:'ios-cog', read:false, write:false, update:false, delete:false, name:'/role'},
        {resourceName: 'Assigned Role', icon:'md-aperture', read:false, write:false, update:false, delete:false, name:'/assineRole'},
      ],

    };
  },

  methods: {

     reload(){
      window.location.reload();
      //this.$forceUpdate();
    },

    async assingRoles(){
        //console.log(this.resources);
        let data = JSON.stringify(this.resources);
        const res = await this.callApi('post', 'app/assing_role', {'permission' : data, id: this.data.id });

        if(res.status == 200){
            this.success("Role has been assign successfully");

            let index = this.roles.findIndex(role => role.id == this.data.id);
            this.roles[index].permission = data;
            //window.location.reload();
        } else{
            this.errorD();
        }
    },

    changeAssign(){
        //console.log(this.data.id);
        let index = this.roles.findIndex(role => role.id == this.data.id);
        let permission = this.roles[index].permission;

        if(!permission){
              this.resources = this.resourcesPermissionDefault;
        }else {
            this.resources = JSON.parse(permission);
        }
    }



  },

  async created() {
    const res = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.roles = res.data;
      if (res.data.length){
        this.data.id = res.data[0].id;
        if(res.data[0].permission){
           this.resources = JSON.parse(res.data[0].permission); 
        }
      }
    } else {
      this.errorD();
    }
  },

  
};
</script>

