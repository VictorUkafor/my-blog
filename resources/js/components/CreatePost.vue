<template>
    <div class="p-4">
        <div class="form-group">
            <label for="exampleFormControlInput1">Post Title</label>
            <input type="text" class="form-control"
            placeholder="Post Title" v-model.trim="create.postTitle">

            <div class="mt-2 alert alert-danger" role="alert" 
            v-if="create.errors.postTitle">
                {{create.errors.postTitle}}
            </div>

        </div>    
        <div class="form-group">
            <label for="exampleFormControlInput2">Post Description</label>
            <input type="text" class="form-control"
            placeholder="Post Title" 
            v-model.trim="create.postDescription"/>

            <div class="mt-2 alert alert-danger" role="alert" 
            v-if="create.errors.postDescription">
                {{create.errors.postDescription}}
            </div>
        </div>  
        <div class="form-group">
            <label for="exampleFormControlInput3">Post Content</label>
            <textarea class="form-control" placeholder="Post Content" 
            v-model.trim="create.postContent"></textarea>

            <div class="mt-2 alert alert-danger" role="alert" 
            v-if="create.errors.postContent">
                {{create.errors.postContent}}
            </div>
        </div>     

        <div class="d-flex justify-content-end pt-2">
            <button class="mr-2 btn btn-secondary" 
            data-dismiss="modal" ref="removeBtn2">Cancel</button>

            <button type="button" @click="createPost"
            class="btn btn-primary">{{loading ? 'Loading...' : 'Create'}}</button>
        </div>                     
    </div>
</template>

<script>
   import axios from 'axios';

    export default {
        data(){
            return {
                loading: false,
                create: {
                    postTitle: "",
                    postDescription: "",
                    postContent: "",
                    errors: {
                        postTitle: "",
                        postDescription: "",
                        postContent: "",                    
                    }
                },
            }
        },
        methods: {
            createPost(){
               
                const { 
                    postTitle, 
                    postDescription,
                    postContent
                } = this.create;


                if(!postTitle){
                    this.create.errors.postTitle = 'Post title is required';
                    return
                }else{
                    this.create.errors.postTitle = '';
                }

                if(!postContent){
                    this.create.errors.postContent = 'Post content is required';
                    return
                }else{
                    this.create.errors.postContent = '';
                }


                this.loading = true;

                const body = {
                    title: postTitle,
                    description: postDescription,
                    content: postContent
                }
                      
                axios.post('/api/posts', body)
                .then((response) => {
                    this.loading = false;
                    this.$emit("sendMessage", "Post added successfully");
                    this.$refs.removeBtn2.click();
                }).catch((err) => {
                    this.loading = false;
                    this.$emit("sendError", "Post could be created");
                    this.$refs.removeBtn2.click();
                });
            }
        },
    }
</script>
