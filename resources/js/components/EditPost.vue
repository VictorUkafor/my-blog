<template>
    <div class="p-4">
        <div class="form-group">
            <label for="exampleFormControlInput1">Post Title</label>
            <input type="text" class="form-control" 
            placeholder="Post Title" v-model="edit.postTitle">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput2">Post Description</label>
            <input type="text" class="form-control" 
            placeholder="Post Description" v-model="edit.postDescription">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput3">Post Content</label>
            <textarea class="form-control" 
            placeholder="Post Description" 
            v-model="edit.postContent"></textarea>
        </div>


        <div class="d-flex justify-content-end pt-2">
            <button type="button" class="mr-2 btn btn-secondary" 
            ref="removeBtn" data-dismiss="modal">Cancel</button>

            <button type="button" class="btn btn-primary" 
            @click="updatePost()">{{loading ? 'Loading...' : 'Update'}}</button>
        </div> 
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data(){
            return {
                loading: false,
                uuid: '',
                edit: {
                    postTitle: "",
                    postDescription: "",
                    postContent: "",
                    errors: {
                        dailyBudget: "",
                        totalBudget: "",
                    }
                }
            }
        },
        props: ["post"],
        watch: { 
            '$props':{
            handler: function (val, oldVal ) { 
                this.fetchPost();
            },
            deep: true
            }
        },
        methods: {
            updatePost(){
                this.loading = true;

                const body = {
                    title: this.edit.postTitle,
                    description: this.edit.postDescription,
                    content: this.edit.postContent,
                };

                axios.put(`/api/posts/${this.uuid}`, body)
                .then((response) => {
                    this.loading = false;
                    this.$emit("sendMessage", "Post updated successfully");
                    this.$refs.removeBtn.click();
                }).catch((err) =>{
                    this.loading = false;
                    this.$emit("sendError", "Post could not be updated");
                    this.$refs.removeBtn.click();
                });
            },
            fetchPost(){
                console.log('uuid', this.post);
                this.uuid = this.post.uuid;
                this.edit.postTitle = this.post.title;
                this.edit.postDescription = this.post.description;
                this.edit.postContent = this.post.content;
            }
        }
    }
</script>
