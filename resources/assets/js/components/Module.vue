<template>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                   Menu multinivel 
                   <button class="btn btn-success" style="float: right; margin-top: -7px;"> 
                      Crear padres 
                   </button>
                </li>

                <li v-for="module in modules" v-if="modules.length" :key="module.id">
                    <a class="redirect" :href="module.url">
                      <i class="fa fa-sitemap fa-fw"> </i> {{module.name}}
                      <span class="fa arrow" v-if="module.childrens"></span>
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

                    <ul class="nav" v-if="module.childrens">
                      <sub-module
                        v-for="mod in module.childrens"
                        :subModule="mod"
                        :key="mod.id">
                      </sub-module>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

import SubModule from './SubModule.vue'

export default {
  components: {
    SubModule
  },
  props:{
    modules:Array,
  }
}
</script>