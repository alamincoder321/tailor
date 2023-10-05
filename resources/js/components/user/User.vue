<template>
    <div class="row ">
        <div class="col-md-12 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">নামঃ</label>
                                    <input type="text" name="name" v-model="user.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="username">ইউজার নামঃ</label>
                                    <input type="text" name="username" v-model="user.username" id="username"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="email">ইমেইলঃ</label>
                                    <input type="text" name="email" v-model="user.email" id="email"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2" v-if="user.roleName != 'Tailor'">
                                <div class="form-group">
                                    <label for="role_id">রোল</label>
                                    <div class="input-group">
                                        <select name="role_id" id="role_id" class="form-select shadow-none" v-model="user.role_id">
                                            <option value="">---রোল বাছাই করুন---</option>
                                            <option v-for="(item, index) in roles" :value="item.id" :key="index">{{ item.name }}</option>
                                        </select>
                                        <a href="/role" style="padding:3px 5px;" class="btn btn-danger"
                                            target="_blank"><i class="fa fa-plus text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="password">পাসওয়ার্ড</label>
                                    <input type="password" name="password" v-model="user.password" id="password"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-12 mb-2">
                            <div class="form-group ImageBackground">
                                <img :src="imageSrc" class="imageShow" />
                                <label for="image">ছবি আপলোড</label>
                                <input type="file" id="image" class="form-control shadow-none" @change="imageUrl" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 text-end">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2" @click="clearData">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3 bg-content" v-if="users.length > 0">
            <vue-good-table :columns="columns" :rows="users" :fixed-header="false" :pagination-options="{
                enabled: true,
                perPage: 10,
            }" :search-options="{ enabled: true }" :line-numbers="true" styleClass="vgt-table condensed"
                max-height="550px">
                <template #table-row="props">
                    <span class="d-flex justify-content-between" v-if="props.column.field == 'before'">
                        <a v-if="props.row.role.name != 'SuperAdmin'" :href="`/useraccess/${props.row.id}`" title="User Access">
                            <i class="fas fa-users text-warning"></i>
                        </a>
                        <a href="" v-if="props.row.role.name != 'SuperAdmin' || userId == 1" title="edit" @click.prevent="editData(props.row)">
                            <i class="fas fa-edit text-info"></i>
                        </a>
                        <a href="" v-if="props.row.role.name != 'SuperAdmin'" title="delete" @click.prevent="deleteData(props.row.id)">
                            <i class="fas fa-trash text-danger"></i>
                        </a>
                    </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            props:['user_id'],
            columns: [
                { label: 'Name', field: 'name' },
                { label: 'User Name', field: 'username' },
                { label: 'Email', field: 'email' },
                { label: 'Role', field: 'roleName' },
                { label: "Action", field: "before" }
            ],
            user: {
                id: '',
                name: '',
                username: '',
                email: '',
                password: '',
                role_id: '',
                image: '',
                roleName: '',
            },
            users: [],
            roles: [],
            imageSrc: "/noImage.png",
            userId:"",
        }
    },
    created() {
        this.getUser();
        this.getRole();
        this.userId = this.$attrs.user_id
    },
    methods: {
        getRole() {
            axios.get('/get-role').then(res => {
                this.roles = res.data.filter(role => role.name != 'Tailor');
            })
        },
        getUser() {
            axios.get('/get-user').then(res => {
                this.users = res.data.map(user => {
                    user.roleName = user.role.name;
                    return user;
                });
            })
        },

        saveData(event) {
            if (this.user.name == '') {
                alert("user name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.user.id);
            if(this.user.roleName == 'Tailor'){
                formdata.append('role_id', this.user.role_id);
            }
            formdata.append('image', this.user.image);
            let url;
            if (this.user.id == '') {
                url = '/user'
            } else {
                url = '/update-user'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
                }
                this.getUser();
            })
        },

        editData(item) {
            this.user = {
                id: item.id,
                name: item.name,
                username: item.username,
                email: item.email,
                role_id: item.role_id,
                image: item.image,
                roleName: item.role.name
            };
            this.imageSrc = item.image == null ? '/noImage.png':'/'+item.image;
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-user', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getUser();
                })
            }
        },

        clearData() {
            this.user = {
                id: '',
                name: '',
                username: '',
                email: '',
                password: '',
                role_id: '',
                image: '',
            };

            this.imageSrc = '/noImage.png';
        },
        
        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.user.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },
    },
}
</script>
