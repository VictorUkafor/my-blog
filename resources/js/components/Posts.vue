<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="alert alert-danger" role="alert" v-if="error">
                {{error}}
            </div>

            <div class="alert alert-success" role="alert" v-if="message">
                {{message}}
            </div>

            <div class="card">
                <div class="card-header" 
                style="display:flex;justify-content:space-between;align-items:center">
                    <div>Posts</div>
                    <button v-if="user_id" type="button" class="btn btn-success" style="color:#fff"
                    data-toggle="modal" data-target="#createModal">
                    Create Post
                    </button>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Content</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(post, index) in posts.data" :key="post.id"
                            >
                            <th scope="row">{{(index+1) + ((page - 1)*posts.per_page)}}</th>
                            <td>{{post.title}}</td>
                            <td>{{post.description}}</td>
                            <td>{{((post.content.slice(0, 20))+'...')}}</td>
                            <td>
                                <a type="button" class="btn btn-primary" 
                                :href="`/posts/${post.uuid}`">View</a>

                                <button class="btn btn-success" v-if="user_id == post.user_id"
                                data-toggle="modal" data-target="#editModal"
                                @click="editPost(index)">Update</button>

                                <button class="btn btn-danger" v-if="user_id == post.user_id"
                                @click="deletePost(post.uuid)">Delete</button>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    
        <nav aria-label="Page navigation example" class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" v-if="posts.prev_page_url" 
                    @click="paginate(posts.current_page - 1)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" v-if="posts.next_page_url"
                    @click="paginate(posts.current_page + 1)" aria-label="Next" >
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>


    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" 
    aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <create-post @sendMessage="sendMessage($event)" 
                @sendError="sendError($event)" />
            </div>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" 
    aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <edit-post 
                :post="selectedPost"
                @sendMessage="sendMessage($event)" 
                @sendError="sendError($event)" />
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" 
    aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <view-post :post="selectedPost" />
            </div>
        </div>
    </div>


</div>
</template>

<script>

    import axios from 'axios';
    import ViewPost from './ViewPost.vue';
    import CreatePost from './CreatePost.vue';
    import EditPost from './EditPost.vue';

    export default {
        components: {
            ViewPost,
            CreatePost,
            EditPost
        },
        data(){
            return {
                posts: {},
                page: 1,
                loading: false,
                selectedPost: {},
                message: "",
                error: "",
                user_id: 0
            }
        },
        props: ['userId'],
        methods: {
            sendMessage(message){
                this.message = message;
                this.fetchPosts();

                const self = this;
                setTimeout(function(){ 
                    self.message = '';
                }, 3000);
            },
            sendError(error){
                this.error = error;

                const self = this;
                setTimeout(function(){ 
                    self.error = '';
                }, 3000);
            },
            viewPost(index){
                this.selectedPost = this.posts.data[index];
            },
            editPost(index){
                const post = this.posts.data[index];
                this.selectedPost = post;
            },
            fetchPosts(){
                axios.get(`/api/posts?page=${this.page}`)
                .then((response) => {
                    this.posts = response.data.posts;
                    console.log('this.camopaa', response.data.posts);
                }).catch((err) =>{
                    console.log('error....', err.response.data)
                });
            },
            paginate(page){
                this.page = page;
                this.fetchPosts();
                axios.get(`/api/posts?page=${this.page}`);
            },
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
            },
            deletePost(uuid){               
                this.loading = true;
                      
                axios.delete(`/api/posts/${uuid}`)
                .then((response) => {
                    this.loading = false;
                    const message = response.data.message;
                    this.sendMessage(message)
                }).catch((err) => {
                    this.loading = false;
                    const error = err.response.data.message;
                    this.sendError(error)
                });
            }
        
        },
        mounted() {
            this.user_id = this.$attrs.userid

            this.fetchPosts();
        }
    }
</script>

