@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>

            <div class="col-md-12 examples">
            <h1>Search</h1>
            <!-- <vue-typeahead v-model="value"
                           prefetch="http://127.0.0.1:8000/shop/search"
                           :default-suggestion="false"
                           display-key='search'
                           :suggestion-template="myTemplate"
                           classes="form-control"
                           v-on:selected="done">
                           
            </vue-typeahead> -->
            <typeahead></typeahead>
        </div>
        </div>
    </div>
</div>
@endsection
