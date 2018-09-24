<template>
    <li style="margin-left:6px;">
        <a class="redirect" :href="subModule.url">
          <i v-if="subModule.childrens.length" class="fa fa-sitemap"></i>
          <i v-if="!subModule.childrens.length" class="glyphicon glyphicon-new-window"></i> 
          {{ subModule.name }}
          <span class="fa arrow" v-if="subModule.childrens.length"> </span>
        </a>

        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-list"></span> 
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a @click.prevent="showCreate(subModule.id, subModule.name)">Crear</a></li>
            <li><a @click.prevent="showUpdate(subModule.id, subModule.parent_id)">Editar</a></li>
            <li><a @click.prevent="$emit('deleteMenu',{index:index,id:subModule.id,indexParent:indexParent})">Eliminar</a></li>
          </ul>
        </div>
        

        <form-general class="form-general" 
                      v-if="moduleActive == subModule.id && moduleActiveType !== ''"
                      :type="moduleActiveType" 
                      :nameTitle="nameTitle"
                      :newId="id"
                      :newParentId="parent_id"
                      :mod="subModule"
                      :index="index">
        </form-general>

        <ul class="nav" v-if="subModule.childrens.length">
            <sub-module
              is="sub-module"
              v-for="(subMod,index) in subModule.childrens"
              :subModule="subMod"
              :key="subMod.id"
              :index="index"
              @deleteMenu="deleteMenu">
            </sub-module>
        </ul>
    </li>
</template>
<script>

import FormGeneral from './FormGeneral.vue'

export default {
  name:'SubModule',
  components:{
    FormGeneral
  },
  data:()=>{
    return{
      nameTitle:'',
      moduleActive:'',
      moduleActiveType:'',
      id:'',
      parent_id:'',
      inProcess:false
    }
  },
  props: {
    subModule: Object,
    index:Number,
    indexParent:Number
  },
  created(){
    this.$bus.$on('closeForm', ()=>{
      this.moduleActive = ''
      this.moduleActiveType = ''
      this.id = ''
      this.parent_id = ''
    })
  },
  methods:{
    showCreate(id, name)
    {
      this.id = 0
      this.parent_id = id
      this.moduleActive = id
      this.nameTitle = name
      this.moduleActiveType = "create"
    },
    showUpdate(id,parent_id)
    {
      this.id = id
      this.parent_id = parent_id
      this.moduleActive = id
      this.moduleActiveType = "update"
    },
    deleteMenu(row){

      if(!this.inProcess)
      {
        this.inProcess = true
        this.$toasterE.info("Espere por favor, mientras verificamos la eliminación",{mark:1,html:true})
        axios.delete(baseUrl+'/module/delete/'+row.id)
        .then( (res) => {
            if(res.data.submit)
            {
              this.subModule.childrens.splice(row.index,1)
              this.moduleActive = row.id
              this.$toasterE.success(res.data.msn,{mark:1,timeout:4500, html:true})
            }
            else
            {
              this.$toasterE.warning(res.data.msn,{mark:1,timeout:6500, html:true})
            }
            this.inProcess = false
        }).catch( (err) => {
          this.$toasterE.error(err,{mark:1,timeout:6500})
          this.inProcess = false
        })
      }
    }
  }
}
</script>
