<template>
    <li>
        <a class="redirect" :href="subModule.url">
          <i v-if="isParent" class="fa fa-sitemap"></i>
          <i v-if="!isParent" class="glyphicon glyphicon-new-window"></i> 
          {{ subModule.name }}
          <span class="fa arrow" v-if="isParent"> </span>
        </a>

        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-list"></span>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="#">Crear</a></li>
            <li><a href="#">Eliminar</a></li>
          </ul>
        </div>
        
        <ul class="nav" v-if="isParent">
          <sub-module
            v-for="subModule in subModule.childrens"
            :subModule="subModule"
            :key="subModule.id">
          </sub-module>
        </ul>
    </li>
</template>
<script>
export default {
  name:'SubModule',
  props: {
    subModule: Object
  },
  computed:{
    isParent() {
      return this.subModule.childrens && this.subModule.childrens.length
    }
  },
  mounted(){
    console.log( this.subModule )
  }
}
</script>
