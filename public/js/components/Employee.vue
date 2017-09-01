<template>
    <div>
        <button class="btn btn-success btn-xs" @click.prevent="add('ADD')"><i class="fa fa-plus"></i>Add Employee</button>

        <div id="vue-tables-options" class="container">
          <h2>VueTables 2 Options API</h2>
          <v-client-table v-if="employees" :data="employees" :columns="['first_name' ,'middle_name', 'last_name','birth_date','experience_years','shop' ,'edit','delete']" :options="options">

              <template slot="edit" scope="props">
                <div>
                    <button type="button" :id="props.row._id" class="btn btn-primary btn-xs" @click.prevent="edit(props.row._id, props.row.first_name, props.row.middle_name, props.row.last_name, props.row.birth_date, props.row.experience_years, props.row.shop_id, props.row.service,'EDIT')" data-function="Edit">Edit
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
        <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-newspaper-o"></i> <span>Dynamic Title Here</span></h4>
                    </div>
                    <form id="contactForm" v-on:submit.prevent="save(input.action)" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="form-group col-md-4">
                                <label for="firstName" class="control-label">First Name:</label>
                                <input type="text" class="form-control " ref="firstName" v-model="input.first_name" id="firstName" name="firstName" placeholder="Enter Frist Name"  required>
                                <input type="hidden" v-model="input.id">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="middleName" class="control-label">Middle name:</label>
                                <input type="text" class="form-control " ref="middleName" v-model="input.middle_name" id="middleName" name="middleName" placeholder="Enter Middle name">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="lastName" class="control-label">Last Name:</label>
                                <input type="text" class="form-control " ref="lastName" v-model="input.last_name" id="lastName" name="lastName" placeholder="Enter Last Name">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="birthDate" class="control-label">Birthday:</label>
                                <input type="text" class="form-control " ref="birthDate" v-model="input.birth_date" id="birthDate" name="birthDate" placeholder="Enter Birthday">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="experienceYears" class="control-label">Years of Experience:</label>
                                <input type="text" class="form-control " ref="experienceYears" v-model="input.experience_years" id="experienceYears" name="experienceYears" placeholder="Enter Years of Experience">
                            </div>

                            <div class="form-group col-md-12" v-if="shops != ''">
                                <select name="shops" id="shops" class="form-control" v-model="input.shop_id" >
                                    <option disabled value="">Please select one</option>
                                    <option v-for="shop in shops" :value="shop._id">{{shop.name}}</option>
                                </select>
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
                        <h4 class="modal-title"><i class="fa fa-spanner"></i> <span>Delete Employee</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">   
                            <h4>Are you sure you want to delete this Employee?</h4>
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
    Vue.component('employees', {
        props:['data'],
        template:'<div><div class="col-md-12"><h4>Services</h4><ul><li v-if="data.service" v-for="service in data.service">{{service.services.service_name}}</li></ul></div> </div>',
    });

    export default {
        data() {
            return {
                employees: null,
                services: null,
                shops: null,
                input: {'id':'', 'first_name': '', 'middle_name': '', 'last_name': '', 'birth_date': '', 'experience_years': '', 'shop_id': '', 'service': []},
                options: {
                    sortable: ['contact','name','short_description'],
                    perPage: 5,
                    uniqueKey: "_id",
                    childRow: 'employees',
                },
                toggle: 'toggle',
            };
        },
        mounted() {
            this.getData();
            this.getShops();
            this.getServices();
        },
        methods: {
            getData: function(){
                axios.get('/employee/lists').then(response => {
                    this.employees = response.data;
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
            getShops: function(){
                axios.get('/employee/shops').then(response => {
                    this.shops = response.data;
                    console.log(response.data);
                });
            },
            add(action){
                this.input = {'id':'','first_name': '', 'middle_name': '', 'last_name': '', 'birth_date': '', 'experience_years': '', 'shop_id': '', 'service': []};
                this.input.action = 'ADD'; 
                $('#employeeModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Add Employee");
                });
                $('#employeeModal').modal('show');
                this.$nextTick(function() {
                    $('.toggle').bootstrapToggle('destroy');
                    $('.toggle').bootstrapToggle();
                });
            },
            edit:function(id, firstName, middleName, lastName, birthDate, experienceYears, shopId, service,action){
                var vm = this;
                this.input.id = id;
                this.input.first_name = firstName;
                this.input.middle_name = middleName;
                this.input.last_name = lastName;
                this.input.birth_date = birthDate;
                this.input.experience_years = experienceYears;
                this.input.shop_id = shopId;
                this.input.service = [];
                this.input.action = action;
                console.log(service);
                var retval = [];
                $.each( service, function( key, value ) {
                    vm.$nextTick(function() {
                        $('.toggle').bootstrapToggle('destroy');
                        $('.toggle').bootstrapToggle();
                    });
                    retval.push(value.services._id);

                });

                this.input.service = retval;

                $('#employeeModal').on('show.bs.modal', function (event) {
                    var modal = $(this);
                    modal.find('.modal-title span').text("Edit Employee");
                });
                $('#employeeModal').modal('show');
            },
            deleteModal: function(id){
                console.log(id);
                this.input.id = id;
                $('#deleteModal').modal('show');
            },
            deleterecord: function(id){
                axios.delete('/employee/destroy/'+id).then((response) => {
                    var dT = $('#t_users').DataTable();
                    dT.destroy();
                    this.getData();
                    $('#deleteModal').modal('hide');
                    toastr.success('Employee Record Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                });
            },
            save: function(action){
                
                var input = this.input;
                console.log(input);
                if(this.input.action == "ADD"){
                    var serviceArray = [];
                    $("input:checkbox[class=toggle]:checked").each(function(){
                        serviceArray.push($(this).val());
                    });

                    this.input.service = serviceArray;

                    this.input.id = '';
                    axios.post('/employee/create',input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#employeeModal').modal('hide');
                        toastr.success('Employee Information Added Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
                //Edit News
                if(this.input.action == "EDIT"){
                    var serviceArray = [];
                    $("input:checkbox[class=toggle]:checked").each(function(){
                        serviceArray.push($(this).val());
                    });

                    this.input.service = serviceArray;

                    axios.post('/employee/update/'+input.id,input).then((response) => {
                        var dT = $('#t_users').DataTable();
                        dT.destroy();
                        this.getData();
                        $('#employeeModal').modal('hide');
                        toastr.success('Employee Information Edited Successfully.', 'Success Alert', {timeOut: 5000});
                    });
                }
            },
        }
    }
</script>
