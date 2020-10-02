<template>
    <div>
        <Spin fix v-if="isLoading">
            <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
            <div>Пожалуйста, подождите...</div>
        </Spin>

        <Form ref="form" :model="form" :rules="formRules">
            <FormItem prop="number" label="Номер">
                <Input :disabled="isLoading" type="text" v-model="form.number" placeholder="Введите номер"></Input>
            </FormItem>
            <FormItem prop="name" label="Имя">
                <Input :disabled="isLoading" type="text" v-model="form.name" placeholder="Введите имя"></Input>
            </FormItem>

            <FormItem>
                <Button type="primary" @click="saveFormData()">Сохранить</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
    export default {
        name: "CreatePhoneForm",
        props:  ['phone_id' ],

        data: function () {
            return {
                isLoading: false,
                phoneBook: [],

                // Форма
                form: {
                    number: '',
                    name: '',
                },
                formRules: {
                    number: [
                        { required: true, message: 'Пожалуйста, заполните поле "Номер"', trigger: 'blur' },
                        { type: 'string', min: 2, message: 'Длина поля "Номер" не может быть менее 2 символов', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: 'Пожалуйста, заполните поле "Имя"', trigger: 'blur' },
                        { type: 'string', min: 2, message: 'Длина поля "Имя" не может быть менее 2 символов', trigger: 'blur' }
                    ],
                },
            }
        },

        methods: {
            cleanAddFieldForm() {
                this.form = {
                    number: '',
                    name: '',
                };
            },

            getItem() {
                this.isLoading = true;

                axios({
                    method: 'get',
                    url: '/api/phone-book' + this.phone_id,
                    headers: window.api
                }).then((res) => {
                    this.isLoading = false;
                    this.form = res.data.data;
                }, (err) => {
                    console.log(err);
                    this.isLoading = false;
                });
            },

            saveFormData() {
                let fd = new FormData();

                // сконвертировать JS-объект в FormData объект
                for (let key in this.form) {
                    if (this.form.hasOwnProperty(key)) {
                        if (this.form[key] !== undefined && this.form[key] !== null) {
                            fd.append(key, this.form[key]);
                        } else {
                            fd.append(key, "");
                        }
                    }
                }

                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios({
                            method: 'post',
                            url:'/api/phone-book',
                            data: fd,
                            headers: window.api
                        }).then((res) => {
                            this.cleanAddFieldForm();
                            this.$snotify.success('Запись сохранена!');
                            this.$emit('save');
                        }, (err) => {
                            console.log(err.response);
                            if (err.response && err.response.data.errors) {
                                for (let key in err.response.data.errors) {
                                    this.$Message.error(err.response.data.errors[key].join(', '));
                                }
                            } else {
                                this.$Message.error(err.response.data.message || `Ошибка отправки данных!`);
                            }
                        });
                    } else {
                        this.$Message.error('Ошибка валидации формы!');
                        return;
                    }
                })
            },
        },

        mounted() {
            if (this.phone_id) {
                this.getItem();
            }
        }
    }
</script>

<style scoped>

</style>
