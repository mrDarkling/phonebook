<template>
    <div class="panel panel-headline">
        <!-- NAVBAR -->
        <row>
            <col span="24">
            <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 shadow-sm sticky-top  navbar-light"  style="background-color: #e3f2fd; ">
                <h5 class="my-0 mr-md-auto font-weight-normal text-dark">Телефонный справочник</h5>
                <nav class="my-2 my-md-0 mr-md-3 navbar-fixed-top">
                    <router-link to="/"><a class="p-2 text-dark">Главная</a></router-link>
                </nav>
                <div>
                    <Input v-model="searchQuery"
                           placeholder="Введите номер"
                           size="large"
                           :clearable="true"
                           :disabled="isLoading"
                           :search="true"
                           @input="getSearchResult()">
                    </Input>
                </div>
            </div>
        </row>
            <!-- END NAVBAR -->

        <row>
            <col span="8">
            <div class="panel-heading">
                <h3 class="panel-title">Добавление номера</h3>
                <Button class="float-left mt-1 mb-1" type="success"  @click="createItem">Добавить новый номер</Button>
            </div>

        </row>

        <row>
            <col span="24">
            <div class="panel-body">
                <div class="mt-3">
                    <Table transfer border :columns="tableColumns" :data="tableList">
                        <template slot-scope="{ row, index }" slot="actions">
                            <a>
                            <router-link :to="{ name: 'EditPhone', params: { phone_id: row.id}}">

                                    <Button title="Редактирование номера"
                                            type="primary"
                                            shape="circle"
                                            icon="md-create" style="margin-right: 5px"></Button>

                            </router-link>
                            </a>
                            <Button title="Удаление номера"
                                    type="error"
                                    shape="circle"
                                    icon="md-trash" @click="deleteItem(row.id)"></Button>
                        </template>
                    </Table>

                    <Page :total="tableTotal"
                          :current="currentPage"
                          :page-size="perPage"
                          @on-change="changePage"
                          v-if="totalPages > 1"
                          class="mt-3 mb-1 text-center" />
                </div>
            </div>
        </row>
        <!-- Форма -->
        <Modal v-model="isModalShown" :closable="true" :footer-hide="true" title="Добавление нового номера" width="60%">
            <create-phone-form v-if="isModalShown" :phone_id="phone_id" @save="getItems()" />
        </Modal>
    </div>
</template>

<script>
    import utils from "./utils.js"
    import axios from 'axios'
    import CreatePhoneForm from './forms/CreatePhoneForm'
    import EditPhone from './EditPhone'

    export default {
        components: {CreatePhoneForm, EditPhone},
        data: function() {
            return {
                search: '',
                isLoading: false,
                phone_id: null,
                isModalShown: false,
                searchQuery: '',

                // Описание заголовка таблицы
                tableColumns: [
                    {title: 'ID', key: 'id'},
                    {title: 'Номер телефона', key: 'number', sortable: true},
                    {title: 'Имя', key: 'name', sortable: true},
                    {title: 'Действия', key: 'actions', slot: 'actions'},
                ],

                tableData: [],         // данные от бэкенда
                tableList: [],         // данные для текущей страницы

                // пейджинация
                currentPage: 1,
                perPage: 5,
            }
        },

        methods: {
            // получение списка номеров
            getItems() {
                this.isLoading = true;
                axios.get("api/phone-book", {
                    headers: window.api,
                }).then((res) => {
                    this.tableData = res.data.data;
                    this.changePage(1);
                    this.isLoading = false;
                    this.isModalShown = false;
                }, (err) => {
                    console.log(err);
                    this.$snotify.error("Ошибка получения данных!");
                    this.isModalShown = false;
                });
            },

            // добавление нового номера
            createItem() {
                this.phone_id = null;
                this.isModalShown = true;
            },

            // редактирование номера
            editItem(id) {
                this.phone_id = id;
                this.redirectTo('editPhone');
            },

            // удаление номера
            deleteItem(phone_id) {
                console.log(phone_id);
                this.$Modal.confirm({
                    title: 'Подтверждение!',
                    loading: false,
                    content : `Вы действительно хотите удалить телефонный номер #${phone_id} ?`,
                    onOk: () => {
                        this.isLoading = true;

                        axios({
                            method: 'delete',
                            url:  "/api/phone-book/" + phone_id,
                            headers: window.api
                        }).then((res) => {
                            this.$snotify.success('Запись удалена!');
                            this.isLoading = false;
                            this.getItems();
                        }, (err) => {
                            console.log(err);
                            this.$Message.error(`Ошибка отправки данных!`);
                            this.isLoading = false;
                        });
                    }
                });
            },

            // Получение списка страниц
            getPageList(p, size) {
                return this.filteredTableData.slice((p - 1) * size, p * size);
            },

            // Переключение страницы
            changePage(p) {
                console.log(this.totalPages);
                this.currentPage = p;
                this.tableList = this.getPageList(p, this.perPage);
            },

            // перенаправление на страницы при помощи импорта
            redirectTo(to) {
                utils.redirect(to)
            },

            getSearchResult() {
                if (this.searchQuery) {
                    this.changePage(1);
                }
            },
        },

        computed: {
            filteredTableData: function () {
                return this.tableData.filter((object) => {
                    if (this.searchQuery) {
                        return object.number.includes(this.searchQuery);
                    }
                    return true;
                });
            },

            totalPages: function () {
                return this.tableData.length > 0 ? Math.ceil(this.tableTotal / this.perPage) : 1;
            },

            tableTotal: function () {
                return this.filteredTableData.length;
            }
        },

        mounted() {
            this.getItems();
        },
    }

</script>
