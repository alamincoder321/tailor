<template>
    <div class="row ">
        <div class="col-md-6 offset-md-3 bg-content">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">ক্যাটাগরি নামঃ</label>
                            <input type="text" name="name" v-model="category.name" id="name"
                                class="form-control shadow-none" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 mt-3 offset-md-3 bg-content">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in categories" :key="index">
                        <td v-html="index + 1"></td>
                        <td v-html="item.name"></td>
                        <td>Action</td>
                    </tr>
                </tbody>
            </table>
            <!-- <vue-good-table :columns="columns" :rows="categories" /> -->
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            columns: [
                { label: 'Name', field: 'name' },
                { label: "Action", field: "before" }
            ],
            category: {
                id: '',
                name: '',
            },
            categories: [],
        }
    },
    created() {
        this.getCategory();
    },
    methods: {
        getCategory() {
            axios.get('/get-category').then(res => {
                this.categories = res.data;
            })
        },

        saveData(event) {
            if (this.category.name == '') {
                alert("Category name required");
                return;
            }
            let formdata = new FormData(event.target);
            axios.post('/category', formdata).then(res => {
                if (res.data.status) {
                    alert(res.data.msg);
                } else {
                    console.log(res.data.msg);
                }
            })
        }
    },
}
</script>
