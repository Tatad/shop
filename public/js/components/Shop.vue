<template>
    <div>
        <button class="btn btn-success btn-xs" @click.prevent="add('ADD')"><i class="fa fa-plus"></i>Add Shop</button>

        <div id="vue-tables-options" class="container">
          <h2>VueTables 2 Options API</h2>
          <v-client-table v-if="shops" :data="shops" :columns="['name' ,'contact', 'street','subdivision','city','district','open_time','close_time','website' ,'edit','delete']" :options="options">
                
              <template slot="services" scope="props">
                <div>
                    <td class="customerIDCell" v-if="props.row.service"><ul><li v-for="service in props.row.service">{{service.services.service_name}}</li></ul></td>
                </div>
              </template>

              <template slot="edit" scope="props">
                <div>
                    <button type="button" :id="props.row.id" class="btn btn-primary btn-xs" @click.prevent="edit(props.row._id, props.row.name, props.row.short_description,props.row.contact, props.row.street, props.row.subdivision, props.row.city, props.row.district, props.row.open_time, props.row.close_time, props.row.website, props.row.service,'EDIT')" data-function="Edit">Edit
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
        </div>

        <!-- ADD / EDIT MODAL -->
        <div class="modal fade" id="shopModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-newspaper-o"></i> <span>Dynamic Title Here</span></h4>
                    </div>
                    <form id="contactForm" v-on:submit.prevent="save(input.action)" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="form-group col-md-4">
                                <label for="shopName" class="control-label">Name:</label>
                                <input type="text" class="form-control " ref="shopName" v-model="input.name" id="shopName" name="shopName" placeholder="Enter Shop Name"  required>
                                <input type="hidden" v-model="input.id">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="shopContactNumber" class="control-label">Contact Number:</label>
                                <input type="text" class="form-control " ref="shopContactNumber" v-model="input.contact" id="shopContactNumber" name="shopContactNumber" placeholder="Enter Contact Number">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="website" class="control-label">Website:</label>
                                <input type="text" class="form-control " ref="website" v-model="input.website" id="website" name="website" placeholder="Enter Website">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="shortDescription" class="control-label">Short Description:</label>
                                <input type="text" class="form-control " ref="shortDescription" v-model="input.short_description" id="shortDescription" name="shortDescription" placeholder="Enter Shop Short Description"  required>
                                <input type="hidden" v-model="input.id">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="street" class="control-label">Street:</label>
                                <input type="text" class="form-control " ref="street" v-model="input.street" id="street" name="street" placeholder="Enter Street">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subdivision" class="control-label">Subdivision:</label>
                                <input type="text" class="form-control " ref="subdivision" v-model="input.subdivision" id="subdivision" name="subdivision" placeholder="Enter Subdivision">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="city" class="control-label">City:</label>
                                <input type="text" class="form-control " ref="city" v-model="input.city" id="city" name="city" placeholder="Enter City">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="district" class="control-label">District:</label>
                                <input type="text" class="form-control " ref="district" v-model="input.district" id="district" name="district" placeholder="Enter District">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="open_time" class="control-label">Open Time:</label>
                                <input type="text" class="form-control " ref="open_time" v-model="input.open_time" id="open_time" name="open_time" placeholder="Enter Open Time">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="close_time" class="control-label">Close Time:</label>
                                <input type="text" class="form-control " ref="close_time" v-model="input.close_time" id="close_time" name="close_time" placeholder="Enter Close Time">
                            </div>
                            <legend v-if="services">Services</legend>
                            <div class="col-md-12" v-if="services">
                                <div class="form-group" v-for="service in services">
                                    <label for="inputEmail3" class="col-sm-10 control-label">{{service.service_name}}</label>
                                    <div class="col-sm-2">
                                       <!--  {{input.service}} -->
                                      <input :class="toggle" type="checkbox" data-toggle="toggle" :id="service._id" v-model="input.service" :value="service._id" >
                                    </div>
                                </div>
                            </div>
                     
                            <!-- <input type="checkbox" checked data-toggle="toggle"> -->

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
                        <h4 class="modal-title"><i class="fa fa-spanner"></i> <span>Delete Shop</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">   
                            <h4>Are you sure you want to delete this shop?</h4>
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
        
    });
    Vue.component('services', {
        props:['data'],
        template:'<div><div class="col-md-6"><h4>Services</h4><ul><li v-if="data.service" v-for="service in data.service">{{service.services.service_name}}</li></ul></div> <div class="col-md-6"><h4 v-if="data.short_description">Short Description</h4><p>{{data.short_description}}</p></div></div>',
    });

    export default {
        data() {
            return {
                el: "#vue-tables-options",
                columns: ['name','short_description','contact','street','subdivision','city','district','open_time','close_time','website','edit'],
                options: {
                    sortable: ['contact','name','short_description'],
                    perPage: 5,
                    uniqueKey: "_id",
                    childRow: 'services',
                    childRowKey:"_id",
                },
                shops: null,
                services: null,
                input: {'id':'', 'name': '', 'short_description': '' ,'contact': '', 'street': '', 'subdivision': '', 'city': '', 'district': '', 'open_time': '', 'close_time': '', 'website': '', 'service': []},
                enabled: false,
                value: '',
                toggle: 'toggle',
                checked: true
            };
        },
        mounted() {
            this.getData();
            this.getServices();
        },
        methods: {
            getData: function(){
                axios.get('/shop/lists').then(response => {
                    this.shops = response.data;

                    this.$nextTick(function() {
                       $('#t_users').DataTable({
                          // DataTable options here...
                       });
                       
                    });
                });
            },
            getServices: function(){
                axios.get('/services').then(response => {
                    this.services = response.data;
                    this.$nextTick(function() {
                        $('.toggle').bootstrapToggle();
                    });
                });
            },
            add(action){
                var vm = this;
                this.input = {'id':'','name': '', 'short_description': '', 'contact': '', 'street': '', 'subdivision': '', 'city': '', 'district': '', 'open_time': '', 'close_time': '', 'website': '', 'service': []};
                this.input.action = 'ADD'; 
                $('#shopModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Add Shop");
                });
                $(":checkbox").prop('checked', false).parent().removeClass('active');
                $('#shopModal').modal('show');
                this.$nextTick(function() {
                    $('.toggle').bootstrapToggle('destroy');
                    $('.toggle').bootstrapToggle();
                });
            },
            edit:function(id, name, shortDescription,contact, street, subdivision, city, district, open_time, close_time, website,service,action){
                var vm = this;
                this.input.id = id;
                this.input.name = name;
                this.input.short_description = shortDescription;
                this.input.contact = contact;
                this.input.street = street;
                this.input.subdivision = subdivision;
                this.input.city = city;
                this.input.district = district;
                this.input.open_time = open_time;
                this.input.close_time = close_time;
                this.input.website = website;
                this.input.action = action;
                this.input.service = [];

                var retval = [];
                $.each( service, function( key, value ) {
                    vm.$nextTick(function() {
                        $('.toggle').bootstrapToggle('destroy');
                        $('.toggle').bootstrapToggle();
                    });
                    retval.push(value.services._id);

                });

                this.input.service = retval;
                $('#shopModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Edit Shop");
                });
                $('#shopModal').modal('show');
            },
            deleteModal: function(id){
                this.input.id = id;
                $('#deleteModal').modal('show');
            },
            deleterecord: function(id){
                axios.delete('/shop/destroy/'+id).then((response) => {
                    var dT = $('#t_users').DataTable();
                    dT.destroy();
                    this.getData();
                    $('#deleteModal').modal('hide');
                    toastr.success('Shop Record Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                });
            },
            save: function(action){
                
                var input = this.input;
                //Add Shop
                if(this.input.action == "ADD"){
                    var serviceArray = [];
                    $("input:checkbox[class=toggle]:checked").each(function(){
                        serviceArray.push($(this).val());
                    });

                    this.input.service = serviceArray;
                    this.input.id = '';
                    axios.post('/shop/create',input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#shopModal').modal('hide');
                        toastr.success('Shop Information Added Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
                //Edit Shop
                if(this.input.action == "EDIT"){
                    var serviceArray = [];
                    $("input:checkbox[class=toggle]:checked").each(function(){
                        serviceArray.push($(this).val());
                    });

                    this.input.service = serviceArray;

                    axios.post('/shop/update/'+input.id,input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#shopModal').modal('hide');
                        toastr.success('Shop Information Edited Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
            },
        }
    }
</script>
