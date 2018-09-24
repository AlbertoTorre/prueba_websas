<template>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                   Menu multinivel 
                    <button class="btn btn-success show-create-parents"
                           @click="showCreate(0, 'padre')"> 
                      Crear padres 
                    </button>
                    <form-general class="form-general" 
                                  v-if="moduleActive === 0"
                                  :type="moduleActiveType" 
                                  :nameTitle="nameTitle"
                                  :newId="id"
                                  :newParentId="parent_id">
                    </form-general>
                </li>


                <li v-for="(module, indexParent) in modules" v-if="modules" :key="module.id">
                    <a class="redirect" :href="module.url">
                      <i class="fa fa-sitemap fa-fw"> </i> {{module.name}}
                      <span class="fa arrow" v-if="module.childrens.length"></span>
                    </a>

                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="glyphicon glyphicon-list"></span> 
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a @click.prevent="showCreate(module.id, module.name)">Crear</a></li>
                        <li><a @click.prevent="showUpdate(module.id, module.parent_id)">Editar</a></li>
                        <li><a @click.prevent="deleteMenuParent(indexParent,module)">Eliminar</a></li>
                      </ul>
                    </div>

                    <form-general class="form-general" 
                                  v-if="moduleActive == module.id && moduleActiveType !== ''"
                                  :type="moduleActiveType" 
                                  :nameTitle="nameTitle"
                                  :newId="id"
                                  :newParentId="parent_id"
                                  :mod="module"
                                  :index="indexParent">
                    </form-general>

                    <ul class="nav" v-if="module.childrens.length">
                      <sub-module
                        v-for="(mod, index) in module.childrens"
                        :subModule="mod"
                        :key="mod.id"
                        :index='index'
                        :indexParent='indexParent'
                        @deleteMenu="deleteMenu">
                      </sub-module>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

import SubModule from './SubModule.vue'
import FormGeneral from './FormGeneral.vue'

export default {
  components: {
    SubModule,
    FormGeneral
  },
  data:()=>{
    return {
      nameTitle:'',
      moduleActive:'',
      moduleActiveType:'',
      id:'',
      parent_id:'',
      inProcess:false
    }
  },
  props:{
    modules:Array,
  },
  created(){
    this.$bus.$on('closeForm', ()=>{
      this.moduleActive = ''
      this.moduleActiveType = ''
      this.id = ''
      this.parent_id = ''
    })

    this.$bus.$on('createParent', ( row )=>{
        this.modules.push( row );
    })

    this.$bus.$on('updateParent', ( row )=>{
        this.modules[row.index]['name'] = row.data['name'];
        this.modules[row.index]['order'] = row.data['order'];
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
    deleteMenuParent(index, module)
    {
      if(!this.inProcess)
      {
        this.inProcess = true
        this.$toasterE.info("Espere por favor, mientras verificamos la eliminación",{mark:1,html:true})
        axios.delete(baseUrl+'/module/delete/'+module.id)
        .then( (res) => {
            if(res.data.submit)
            {
              this.modules.splice(index, 1)
              this.moduleActive = module.id
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
    },
    deleteMenu(row)
    {
      if(!this.inProcess)
      {
        this.inProcess = true
        this.$toasterE.info("Espere por favor, mientras verificamos la eliminación",{mark:1,html:true})
        axios.delete(baseUrl+'/module/delete/'+row.id)
        .then( (res) => {
            if(res.data.submit)
            {
              this.modules[row.indexParent]['childrens'].splice(row.index, 1)
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