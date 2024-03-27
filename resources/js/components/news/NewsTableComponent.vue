<template>
    <div>
        <el-card class="box-card">
            <el-table 
                :data="tableData" 
                :stripe = "true"
                :border = "true"
                style="width: 100%; 
                /* height: calc(100vh - 200px);  */
                /* overflow: auto; */
                "
                >
                <el-table-column 
                    v-for="(column) in tableColumns"
                    :key="column.label"
                    :label="column.label"
                    :prop="column.prop"
                    :column-key="column.prop"
                    :min-width="column.minWidth"
                    :sortable="column.sortable"
                    :align="column.align"
                    :header-align="column.headerAlign"
                    :fixed="column.fixed || null"
                    :formatter="column.formatter || null"
                >
                </el-table-column>
                <el-table-column label="" align="center" header-align="center">
                    <template slot-scope="{ row }">
                        <el-button type="danger" icon="el-icon-delete" circle @click="openDeleteConfirmModal(row)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="display: flex; justify-content: center; padding-top: 10px;">
                <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="pagination.currentPage"
                    :page-sizes="[10, 20, 30, 50]"
                    :page-size="pagination.pageSize"
                    :total="pagination.total"
                    layout="total, sizes, prev, pager, next, jumper"
                ></el-pagination>
            </div>
        </el-card>
        <el-dialog
            title="Confirm deletion"
            :visible.sync="showDeleteConfirmModal"
            width="30%"
            @close="closeDeleteConfirmModal"
            >
            <span>Do you really want to delete this item?</span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeDeleteConfirmModal">Cancel</el-button>
                <el-button type="danger" @click="deleteItem">Delete</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: 'news-table-component',
        mounted() {
        },
        data() {
            return {
                showDeleteConfirmModal: false,
                selectedRow: null,
                tableData: [],
                pagination: {
                    currentPage: 1,
                    pageSize: 10,
                    total: 0
                },
                tableColumns: [
                    {
                        label: 'Title',
                        prop: 'title',
                        minWidth: 200,
                        sortable: true,
                        align: 'center',
                        headerAlign: 'center',
                    },
                    {
                        label: 'Link',
                        prop: 'link',
                        minWidth: 200,
                        sortable: true,
                        align: 'center',
                        headerAlign: 'center',
                        formatter: (row, column, cellValue) => {
                            return this.$createElement('a', {
                                attrs: {
                                    href: cellValue,
                                    target: '_blank'
                                }
                            }, cellValue);
                        }
                    },
                    {
                        label: 'Points',
                        prop: 'points',
                        minWidth: 50,
                        sortable: true,
                        align: 'center',
                        headerAlign: 'center',
                    },
                    {
                        label: "Date Created",
                        prop: 'date_created',
                        minWidth: 100,
                        sortable: true,
                        align: 'center',
                        headerAlign: 'center',
                        formatter: (row, column, cellValue) => {
                            let [date, time] = cellValue.split(' ');
                            let [year, month, day] = date.split('-');
                            return `${day}.${month}.${year} ${time}`;
                        }
                    },
                ],
            }
        },
        async mounted() {
            this.fetchData();
        },
        methods: {
            openDeleteConfirmModal(row) {
                this.selectedRow = row;
                this.showDeleteConfirmModal = true;
            },
            closeDeleteConfirmModal() {
                this.showDeleteConfirmModal = false;
            },
            deleteItem() {
                this.handleDelete(this.selectedRow);
                this.closeDeleteConfirmModal();
            },
            async fetchData() {
                try {
                    const response = await axios.post('/get-newsData', {
                        page: this.pagination.currentPage,
                        per_page: this.pagination.pageSize
                    });
                    this.tableData = response.data.data;
                    this.pagination.total = response.data.total;
                } catch (error) {
                    console.error(error);
                }
            },
            handleSizeChange(val) {
                this.pagination.pageSize = val;
                this.fetchData();
            },
            handleCurrentChange(val) {
                this.pagination.currentPage = val;
                this.fetchData();
            },
            async handleDelete(row) {
                const loader = this.$showLoader();
                try {
                    const response = await axios.delete(`/delete-news/${row.id}`);
                    this.fetchData();
                    Vue.prototype.$notify({
                        title: "Delete News",
                        message: response.data.message,
                        type: response.data.status ? "success" : "error"
                    });
                } catch (error) {
                    console.error(error);
                } finally {
                    loader.close();
                }
            }
        }
    };
</script>