<template>
    <div>
        <button class="btn btn-success btn-xs" @click.prevent="add('ADD')"><i class="fa fa-plus"></i>Add Post</button>

        <v-client-table v-if="posts" :data="posts" :columns="['post_title' ,'short_description', 'post_content','created_at','edit','delete']" :options="options">
                
            <template slot="post_content" scope="props">
                <div>
                    <td v-html="props.row.post_content">{{props.row.post_content}}</td>   
                </div>
            </template>

            <template slot="edit" scope="props">
                <div>
                    <button type="button" :id="props.row._id" class="btn btn-primary btn-xs" @click.prevent="edit(props.row._id, props.row.post_title, props.row.short_description, props.row.post_content, 'EDIT')" data-function="Edit">Edit
                        <i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                    </button>
                </div>
            </template>

            <template slot="delete" scope="props">
                <div>
                    <button type="button" :id="props.row.id" class="btn btn-danger btn-xs" @click.prevent="deleteModal(props.row._id)">
                        <i class="fa fa-trash-o" data-toggle="tooltip" data-placement="top" title="Delete"></i>Delete
                    </button>  
                </div>
            </template>
        </v-client-table>

        <!-- ADD / EDIT MODAL -->
        <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-newspaper-o"></i> <span>Dynamic Title Here</span></h4>
                    </div>
                    <form id="contactForm" v-on:submit.prevent="save(input.action)" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="postTitle" class="control-label">Post Title:</label>
                                <input type="text" class="form-control " ref="postTitle" v-model="input.post_title" id="postTitle" name="postTitle" placeholder="Enter Post Title"  required>
                                <input type="hidden" v-model="input.id">
                            </div>

                            <div class="form-group">
                                <label for="shortDescription" class="control-label">Short Description:</label>
                                <input type="text" class="form-control " ref="shortDescription" v-model="input.short_description" id="shortDescription" name="shortDescription" placeholder="Enter Short Description">
                            </div>

                            <div class="form-group">
                                <label for="lastName" class="control-label">Post Content:</label>
                                <textarea name="postContent" id="editPost" v-model="input.post_content" class="form-control" rows="8" cols="80"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnSubmit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END ADD / EDIT MODAL -->

        <!-- DELETE MODAL -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="netproModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">                
                        <h4 class="modal-title"><i class="fa fa-spanner"></i> <span>Delete Post</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">   
                            <h4>Are you sure you want to delete this Post?</h4>
                            <input type="hidden" v-model="input.id">
                        </div>    
                        <div class="alert alert-primary alert-dismissible" role="alert" id="netProMsg" style="margin-bottom: 0px; display:none;"></div>
                    </div>
                    <div class="modal-footer">                     
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnNetProClose">No</button>
                        <button type="submit" class="btn btn-success" id="btnNetProDeactivate" @click.prevent="deleterecord(input.id)">Yes</button>                  
                    </div>
                </div>
            </div>
        </div>
        <!-- END DELETE MODAL -->
    </div>
</template>

<script>
    import Vue from 'vue';
    import {ServerTable, ClientTable, Event} from 'vue-tables-2';
    Vue.use(ClientTable, {
        perPage: 5
    });

    export default {
        data() {
            return {
                posts: null,
                input: {'id':'', 'post_title': '', 'short_description': '', 'post_content': ''},
                options: {
                    sortable: ['post_title','post_content','created_at'],
                },
            };
        },
        mounted() {
            this.getData();

        },
        methods: {
            getData: function(){
                axios.get('/post/lists').then(response => {
                    this.posts = response.data;
                    this.$nextTick(function() {
                       $('#t_users').DataTable({
                          // DataTable options here...
                       });
                    });
                });
            },
            add(action){
                this.input = {'id':'','post_title': '', 'short_description': '', 'post_content': ''};
                this.input.action = 'ADD'; 
                $('#postModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Add Employee");
                });
                $('#postModal').modal('show');
            },
            edit:function(id, postTitle, shortDescription, postContent,action){
                this.input.id = id;
                this.input.post_title = postTitle;
                this.input.short_description = shortDescription;
                //this.input.post_content = postContent;
                tinymce.get('editPost').setContent(postContent);     
                tinyMCE.activeEditor.setContent(postContent);
                this.input.action = action;

                $('#postModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Edit Employee");
                });
                $('#postModal').modal('show');
            },
            deleteModal: function(id){
                console.log(id);
                this.input.id = id;
                $('#deleteModal').modal('show');
            },
            deleterecord: function(id){
                axios.delete('/post/destroy/'+id).then((response) => {
                    var dT = $('#t_users').DataTable();
                    dT.destroy();
                    this.getData();
                    $('#deleteModal').modal('hide');
                    toastr.success('Post Record Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                });
            },
            save: function(action){
                this.input.post_content = tinyMCE.activeEditor.getContent();
                var input = this.input;
                console.log(input);
                //Add News
                if(this.input.action == "ADD"){
                    this.input.id = '';
                    axios.post('/post/create',input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#postModal').modal('hide');
                        toastr.success('Post Information Added Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
                //Edit News
                if(this.input.action == "EDIT"){
                    axios.post('/post/update/'+input.id,input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#postModal').modal('hide');
                        toastr.success('Post Information Edited Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
            },
        }
    }
</script>
