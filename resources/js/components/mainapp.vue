<template>
<div>
    <div v-if="$store.state.user">
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu" >
        <div class="_1side_menu_logo">
          <!-- <h3 style="text-align:center;">Logo Image</h3> -->
          <img src="/images/logo.png" style="width: 108px;margin-left: 68px; height: 30px"/>
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content">
          <div class="_1side_menu_img_name">
            <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
            <Avatar v-if="this.user.role.roleName" size="large" style="color: #fff;background-color: #17233d">{{this.user.role.roleName}}</Avatar>
            <span class="_1side_menu_name">{{this.user.fullName}}</span>
          </div>
          <hr>
          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">

              <template v-for="(menuItem,i) in permission" :key="i" >

              <li v-show="permission.length && menuItem.read">
                
                <router-link :to="menuItem.name"> <Icon :type="menuItem.icon" /> {{menuItem.resourceName}} </router-link>
                
              </li>

              </template>

 
              <!-- <li><router-link to="/adminuser" ><Icon type="ios-contact" /> Admin User</router-link></li>
              <li><router-link to="/tags" ><Icon type="ios-pricetags" /> Tags</router-link></li>
              <li><router-link to="/category" ><Icon type="logo-instagram" /> Category</router-link></li>
              <li><router-link to="/role" ><Icon type="ios-cog" />Role</router-link></li>
              <li><router-link to="/assineRole" ><Icon type="md-aperture" />Assinged Role</router-link></li> -->



              <li><a href="/logout" ><Icon type="md-log-out" color="red" /> Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header">
        <div class="_2menu _box_shadow">
          <div class="_2menu_logo">
            <ul class="open_button">
              <li>
                <Icon type="md-menu" />
              </li>
              <li style="float:right;margin-right:15px"><a href="/logout" ><Icon type="md-log-out" color="red" /></a></li>
            </ul>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
    	<div><router-view/></div>
</div>
</template>

<script>
export default {

  props:['user','permission'],

  data(){
    return {
      isLoggedIn : false,
    }
  },

  methods: {
    reload(){
      window.location.reload();
    }
  },

  created(){
    //console.log(this.user.role.roleName);
    this.$store.commit('setUpdateUSER',this.user);
    this.$store.commit('setUserPERMISSION',this.permission); 
  }

}
</script>