Vue.component('delete', {
props:['data'],
template:`<a class='delete' @click='erase'></a>`,
methods:{
erase() {
let id = this.data.id; // delete the item
}
}
});