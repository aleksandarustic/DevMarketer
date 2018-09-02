<style scoped>

    .slug-widget {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .wrapper {
        margin-left: 8px;
    }

    .slug {
        background-color: yellow;
        padding:3px
    }
    .input {
        width:auto;
    }
    .url-wrapper {
        display:flex;
        align-items:center;
        height:28px
    }
</style>

<template>
   <div class="slug-widget">
       <div class="icon-wrapper">
           <i class="fa fa-link"></i>
       </div>
        <div class="url-wrapper wrapper">
            <span class="root-url">{{url}}</span><!--
            --><span class="subdirectory-url">/{{subdirectory}}/</span><!--
            --><span class="slug" v-show="slug && !isEditing" >{{slug}}</span><!--
            --><span class="slug-eidt" v-show="isEditing"><input type="text" name="slug" v-model="customSlug" class="input"></span>
        </div>
        <div class="button-wrapper wrapper">
            <button class="save-slug-button button is-small" v-show="!isEditing" @click.prevent="editSlug">Edit</button>
            <button class="save-slug-button button is-small" v-show="isEditing" @click.prevent="saveSlug">Save</button>
            <button class="save-slug-button button is-small" v-show="isEditing" @click.prevent="resetEditing">Reset</button>
        </div>

   </div>
</template>

<script>
    export default {
        props:{
           url: {
              type: String,
              required: true,
           },
           subdirectory: {
              type:String,
               required:true
           },
           title: {
               type:String,
               required:true
           }
        },
        data:function (){
          return {
              slug: this.convertTitle(),
              isEditing:false,
              customSlug:'',
              wasEdited:false
          }
        },
        methods: {
            convertTitle: function() {
                return Slug(this.title)
            },
            editSlug: function() {
                this.customSlug = this.slug;
                this.isEditing = true;
            },
            resetEditing:function(){
                this.slug = this.convertTitle();
                this.wasEdited = false;
                this.isEditing = false;
            },
            saveSlug: function(){
                if(this.customSlug !== this.slug){
                    this.wasEdited = true;
                }
                this.slug = Slug(this.customSlug);
                this.isEditing = false;
                this.wasEdited = true;
            }
        },
        watch: {
            title: _.debounce(function(){
                if(!this.wasEdited){
                    this.slug = this.convertTitle()
                  }
                },250),
            slug: function (val) {
                this.$emit.event('slug-changed', this.slug)
            }

        }
    }
</script>
