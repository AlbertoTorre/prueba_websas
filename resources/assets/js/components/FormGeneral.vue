<template>
	<div>
		<form  @submit.prevent="send" ref="send">
		
			<h4> {{  (type=='create')?'Crear nuevo':'Editar'  }}  menu <b> {{ nameTitle }} </b> </h4>

			<input type="hidden" v-if="dataForm.id" name='id' v-model='dataForm.id' v-validate="'required|numeric'">
			
			<input type="hidden" name='parent_id' v-model='dataForm.parent_id' v-validate="'required|numeric'">

		    <div :class="{'col-xs-10 col-md-10 col-lg-10 form-group':true, 'has-error':errors.has('name') }">
		      <label class="control-label" for="name">Nombre</label>
		      <input v-model="dataForm.name" name="name" type="text" class="form-control" v-validate="'required'">
		      <div class="message-validate has-error" v-show="errors.has('name')"> {{ errors.first('name') }} </div>
		    </div>

		    <div :class="{'col-xs-2 col-md-2 col-lg-2 form-group':true, 'has-error':errors.has('active') }">
		      <label class="control-label" for="active"> Activo </label>
		      <input v-model="dataForm.active" name="active" type="checkbox" class="form-control" checked="">
		    </div>

		    <div :class="{'col-xs-9 col-md-9 col-lg-9 form-group':true, 'has-error':errors.has('url') }">
		      <label class="control-label" for="url">Url</label>
		      <input v-model="dataForm.url" name="url" type="text" class="form-control" v-validate="'url|required'">
		      <div class="message-validate has-error" v-show="errors.has('url')"> {{ errors.first('url') }} </div>
		    </div>

		    <div :class="{'col-xs-3 col-md-3 col-lg-3 form-group':true, 'has-error':errors.has('order') }">
		      <label class="control-label" for="order">Orden</label>
		      <input v-model="dataForm.order" name="order" type="text" class="form-control" v-validate="'required'">
		      <div class="message-validate has-error" v-show="errors.has('order')"> {{ errors.first('order') }} </div>
		    </div>

		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align:right;">
		      <button type='submit' class="btn btn-primary">Guardar</button>
		      <button type='button' class="btn btn-default" @click.prevent="closeForm">Cancelar</button>
		    </div>

		</form>
	</div>
</template>

<script>
export default {
	name:'FormGeneral',
	data:()=>{
		return {	
			dataForm:{
		        id:'',
		        parent_id:'',
		        name:'',
		        url:'',
		        active:'',
		        order:0,
		        childrens:[]
			}
		}
	},
	props:{
		type:'',
		nameTitle:'',
        newId:'',
        newParentId:'',
        mod:'',
        index:''
	},
	created(){
		if( this.type == 'update' )
		{
		    this.dataForm.id = this.mod.id 
		    this.dataForm.parent_id = this.mod.parent_id 
		    this.dataForm.name = this.mod.name
		    this.dataForm.url = this.mod.url
		    this.dataForm.order = this.mod.order
		}
	},
	methods:{
		closeForm(){
			this.$bus.$emit('closeForm')
		},
	    send()
	    {
	    	this.dataForm.id = this.newId 
	    	this.dataForm.parent_id = this.newParentId 

			this.$validator.validateAll().then( (result) => {
				if (result) 
				{
				  if(!this.inProcess)
				  {
				    this.inProcess = true
				    let formData = new FormData(this.$refs.send)
				    if( this.newId > 0 ){
				    	formData.append('id', this.newId )
				    }
				    formData.append('parent_id', this.newParentId )
				    axios.post(baseUrl+'/module/'+this.type, formData )
				    .then( (res) => {
						if(res.data.submit)
						{
							if( this.type == 'create' ){
								this.dataForm.id = res.data.id
								let data = JSON.stringify( this.dataForm )
								this.create(data)
							}else if(this.type == 'update'){
								let data = JSON.stringify( this.dataForm )
								this.update(data)
							}

					        this.dataForm.id='',
					        this.dataForm.parent_id='',
					        this.dataForm.name='',
					        this.dataForm.url='#',
					        this.dataForm.active='',
					        this.dataForm.order=0
							this.$validator.reset()

							this.$toasterE.success(res.data.msn,{mark:1,timeout:1500})
							this.closeForm()
						}
						else
						{
							this.$toasterE.error(res.data.msn,{mark:1,timeout:6500, html:true})
						}
						this.inProcess = false
				    }).catch( (err) => {
						this.$toasterE.error(err,{mark:1,timeout:6500})
						this.inProcess = false
				    })
				  }
				}
			})
	    },
	    create(data){
			if( this.newParentId )
			{
				this.mod.childrens.push( JSON.parse(data) )
			}
			else
			{
				this.$bus.$emit('createParent', JSON.parse(data) )
			}
	    },
	    update(data){
			if( this.newParentId )
			{
				let row = JSON.parse( data )
				this.mod['name'] = row.name
		        this.mod['order'] = row.order
			}
			else
			{
				this.$bus.$emit('updateParent', { index:this.index, data:JSON.parse(data) } )
			}
	    }
	}
}
</script>