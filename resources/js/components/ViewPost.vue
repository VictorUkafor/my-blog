<template>
    <div class="p-4">
        <div class="pt-3">
            <div class="form-group">
                <label for="exampleFormControlInput1"><b>Post Title</b></label>
                <p>{{post.title}}</p>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput2"><b>Post Description</b></label>
                <p>{{post.description}}</p>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput3"><b>Post Content</b></label>
                <p>{{post.content}}</p>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ["post"],
    }
</script>
