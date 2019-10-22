<?php
/** @var AjaxView $this */

use App\View\AjaxView;
use Cake\Routing\Router;

/** @var string $businessId */
?>
<script>
    const BUSINESS_ID = '<?= $businessId ?>';
    const STARRED_URL_TOGGLE = '<?= Router::url([
        'controller' => 'Leads',
        'action'     => 'toggleStarred'
    ]) ?>/' + BUSINESS_ID;
    const NOTE_URL_SAVE = '<?= Router::url(['controller' => 'Notes', 'action' => 'saveBusinessNote']) ?>';
    const NOTE_URL_GET = '<?= Router::url(['controller' => 'Notes', 'action' => 'getBusinessNotes']) ?>/' + BUSINESS_ID;
    const TODO_URL_SAVE = '<?= Router::url(['controller' => 'Todos', 'action' => 'saveBusinessTask']) ?>';
    const TODO_URL_UPDATE = '<?= Router::url(['controller' => 'Todos', 'action' => 'saveBusinessUpdate']) ?>';
    const TODO_URL_GET = '<?= Router::url(['controller' => 'Todos', 'action' => 'getBusinessTodos']) ?>/' + BUSINESS_ID;
    const TODO_URL_DELETE = '<?= Router::url([
        'controller' => 'Todos',
        'action'     => 'deleteBusinessTodo'
    ]) ?>/' + BUSINESS_ID;
</script>


<style>
    dl {
        width: 100%;
        overflow: hidden;
        padding: 0;
        margin: 0
    }

    dt {
        float: left;
        width: 35%;
        /* adjust the width; make sure the total of both is 100% */
        /*background: #cc0;*/
        padding: 0;
        margin: 0
    }

    dd {
        float: left;
        width: 65%;
        /* adjust the width; make sure the total of both is 100% */
        /*background: #dd0*/
        padding: 0;
        margin: 0
    }
</style>

<section class="content" id="pageApp">
    <?= $this->Form->create() ?>
    <?= $this->Form->end() ?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="gold fa"
                   v-bind:class="{ 'fa-star': business_details.starred, 'fa-star-o': !business_details.starred }"
                   v-on:click="toggleStarred"
                ></i>
                <span :contenteditable="data_editable">{{business_details.company_name}}</span>
            </h3>
        </div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-4">
                    <label>Company Name</label>
                    <div v-html="business_details.company_name" :contenteditable="data_editable"></div>
                </div>

                <div class="col-md-4">
                    <label>Company Type</label>
                    <dl>
                        <dt>NAICS:</dt>
                        <dd v-html="business_details.naics_description" :contenteditable="data_editable"></dd>

                        <dt>SIC:</dt>
                        <dd v-html="business_details.sic_description" :contenteditable="data_editable"></dd>
                    </dl>
                </div>

                <div class="col-md-4">
                    <label>Address</label>
                    <dl>
                        <dt>Street:</dt>
                        <dd v-html="business_details.address" :contenteditable="data_editable"></dd>

                        <dt>City:</dt>
                        <dd v-html="business_details.city" :contenteditable="data_editable"></dd>

                        <dt>Postal Code:</dt>
                        <dd v-html="business_details.postal_code" :contenteditable="data_editable"></dd>

                        <dt>State:</dt>
                        <dd v-html="business_details.state" :contenteditable="data_editable"></dd>
                    </dl>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label>Contacts Person</label>
                    <dl>
                        <dt>Name:</dt>
                        <dd v-html="ifNull(business_details.contact_name)" :contenteditable="data_editable"></dd>

                        <dt>Title:</dt>
                        <dd v-html="ifNull(business_details.title)"
                            :contenteditable="data_editable"></dd>

                        <dt>Name 2:</dt>
                        <dd v-html="ifNull(business_details.contact_name_secondary)"
                            :contenteditable="data_editable"></dd>

                        <dt>Title 2:</dt>
                        <dd v-html="ifNull(business_details.contact_title_secondary)"
                            :contenteditable="data_editable"></dd>

                    </dl>
                </div>

                <div class="col-md-4">
                    <label>Contacts</label>
                    <dl>
                        <dt>Email:</dt>
                        <dd>{{business_details.email | if_null}}</dd>

                        <dt>Email 2:</dt>
                        <dd>{{business_details.email2 | if_null}}</dd>

                        <dt>Telephone:</dt>
                        <dd>{{business_details.telephone | if_null}}</dd>

                        <dt>Fax:</dt>
                        <dd>{{business_details.fax | if_null}}</dd>

                        <dt>Toll Free:</dt>
                        <dd>{{business_details.toll_free | if_null}}</dd>

                        <dt>Website</dt>
                        <dd>{{business_details.website | if_null}}</dd>
                    </dl>
                </div>

                <div class="col-md-4">
                    <label>Info</label>
                    <dl>
                        <dt>Employee Size:</dt>
                        <dd>{{business_details.employee_size_raw | if_null}}</dd>

                        <dt>Sales Volume:</dt>
                        <dd>{{business_details.sales_volume | if_null}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col-md-6">
            <div class="box box-info direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Notes</h3>

                    <div class="box-tools pull-right">
                        <span data-toggle="tooltip" :title="notes.length  + ' note(s)'" class="badge bg-yellow">{{notes.length}}</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts"
                                data-widget="chat-pane-toggle">
                            <i class="fa fa-comments"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body" style="">
                    <div class="direct-chat-messages">
                        <div
                            class="direct-chat-msg"
                            v-for="note in notes"
                            v-bind:class="{ 'right': note.user_id === now_user_id }"
                        >
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">{{ note | note_user_name}}</span>
                                <span class="direct-chat-timestamp pull-right">{{note.created | note_date}}</span>
                            </div>

                            <img src="/admin_l_t_e/img/user1-128x128.jpg" class="direct-chat-img"
                                 alt="message user image">
                            <div class="direct-chat-text" v-html="nltobr(note.note_text)">
                            </div>
                        </div>
                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user1-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Count Dracula
                              <small class="contacts-list-date pull-right">2/28/2015</small>
                            </span>
                                        <span class="contacts-list-msg">How have you been? I was...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user7-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Sarah Doe
                              <small class="contacts-list-date pull-right">2/23/2015</small>
                            </span>
                                        <span class="contacts-list-msg">I will be waiting for...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user3-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Nadia Jolie
                              <small class="contacts-list-date pull-right">2/20/2015</small>
                            </span>
                                        <span class="contacts-list-msg">I'll call you back at...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user5-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Nora S. Vans
                              <small class="contacts-list-date pull-right">2/10/2015</small>
                            </span>
                                        <span class="contacts-list-msg">Where is your new...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user6-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              John K.
                              <small class="contacts-list-date pull-right">1/27/2015</small>
                            </span>
                                        <span class="contacts-list-msg">Can I take a look at...</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                            <li>
                                <a href="#">
                                    <img src="/admin_l_t_e/img/user8-128x128.jpg" class="contacts-list-img"
                                         alt="User Image">
                                    <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Kenneth M.
                              <small class="contacts-list-date pull-right">1/4/2015</small>
                            </span>
                                        <span class="contacts-list-msg">Never mind I found...</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-footer" style="">
                    <form action="#" method="post" _lpchecked="1">
                        <div class="input-group">
                            <?= $this->Form->control(
                                'edit_comment',
                                [
                                    'type'        => 'textarea',
                                    'v-model'     => 'note_ui.note_text',
                                    'label'       => false,
                                    'placeholder' => 'Enter your comment here'
                                ]
                            ) ?>

                            <span class="input-group-btn">
                                <button
                                    type="button"
                                    class="btn btn-success btn-flat"
                                    v-on:click="noteSave">
                                    Save
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>

                    <h3 class="box-title">To Do List</h3>

                    <div class="box-tools pull-right">
                        <!--ul class="pagination pagination-sm inline">
                            <li><a href="#">&laquo;</a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                    <ul class="todo-list">
                        <li v-for="todo in todos" v-bind:class="{'done': todo.completed}">
                            <input type="checkbox" v-model="todo.completed" v-on:change="todoUpdate(todo)">
                            <span class="text">{{todo.description}}</span>
                            <div class="tools">
                                <i class="fa fa-edit"></i>
                                <i class="fa fa-trash-o" v-on:click="todoDelete(todo)"></i>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="box-footer">
                    <div class="input-group">
                        <input class="form-control" placeholder="Type new task here ..." v-model="todo_ui.description">

                        <div class="input-group-btn">
                            <button type="button" class="btn btn-success" v-on:click="todoSave">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


<script>
    var app3 = new Vue({
        el: '#pageApp',
        data: {
            data_editable: false,
            business_details: <?= json_encode($business) ?>,
            todos: [],
            todo_ui: {
                description: ''
            },
            notes: [],
            note_ui: {
                editing: null,
                note_text: ''
            },
            now_user_id: USER_ID,
            now_user_name: USER_NAME,
        },
        methods: {
            setNoteEditing: function (val = null) {
                this.note_ui.editing = val;
                if (val === null) {
                    this.note_ui.note_text = '';
                }
            },
            noteSave: function () {
                var that = this;
                axios.defaults.headers.post['Accept'] = 'application/json';
                axios.post(NOTE_URL_SAVE, {
                    '_csrfToken': this.getCsrfToken(),
                    id: null,
                    business_id: BUSINESS_ID,
                    note_text: this.note_ui.note_text
                })
                    .then(function (response) {
                        that.geBusinessNotes();
                    })
                    .catch(function (error) {
                        showError('Something went wrong while attempting to save the note!');
                        console.log(error);
                    });
                this.setNoteEditing(null);
            },
            geBusinessNotes: function () {
                var that = this;
                axios.post(NOTE_URL_GET)
                    .then(function (response) {
                        that.notes = response.data.data;
                    })
                    .catch(function (error) {
                        showError('Something went wrong while attempting get the notes for this business!');
                        console.log(error);
                    });
            },
            todoSave: function () {
                var that = this;
                axios.defaults.headers.post['Accept'] = 'application/json';
                axios.post(TODO_URL_SAVE, {
                    '_csrfToken': this.getCsrfToken(),
                    id: null,
                    business_id: BUSINESS_ID,
                    description: this.todo_ui.description
                })
                    .then(function (response) {
                        that.geBusinessTodos();
                        that.todo_ui.description = '';
                    })
                    .catch(function (error) {
                        showError('Something went wrong while attempting to save the to-do item!');
                        console.log(error);
                    });
            },
            geBusinessTodos: function () {
                var that = this;
                axios.post(TODO_URL_GET)
                    .then(function (response) {
                        that.todos = response.data.data;
                    })
                    .catch(function (error) {
                        showError('Something went wrong while attempting to get the list of to-dos!');
                        console.log(error);
                    });
            },
            todoUpdate: function (todo) {
                var that = this;
                // axios.defaults.headers.post['Accept'] = 'application/json';

            },
            todoDelete: function (todo) {
                var that = this;
                Swal.fire({
                    title: 'Are you sure?',
                    html: "Are you sure you want to remove this todo?<br><em>" + todo.description + "</em>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.value) {
                        axios.post(TODO_URL_DELETE, todo)
                            .then(function (response) {
                                that.geBusinessTodos();
                            })
                            .catch(function (error) {
                                showError('Something went wrong while trying to remove the to do item!');
                                console.log(error);
                            });
                    }
                })
            },
            toggleStarred: function (todo) {
                var that = this;
                Swal.fire({
                    title: 'Are you sure?',
                    html: "Are you sure you want to star this business?",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Toggle Starred!'
                }).then((result) => {
                    var oldStatus = that.business_details.starred ? true : false;
                    that.business_details.starred = !oldStatus;
                    if (result.value) {
                        axios.post(STARRED_URL_TOGGLE, {starred: that.business_details.starred})
                            .then(function (response) {
                                console.log(response);
                            })
                            .catch(function (error) {
                                showError('Something went wrong and the Starred status was not updated!');
                                console.log(error);
                                that.business_details.starred = oldStatus;
                            });
                    }
                })
            },
            ifNull: function (value, defaultValue = '--') {
                if (value === null) {
                    return defaultValue;
                } else {
                    return value;
                }
            },
            nltobr: function (value) {
                return value.replace(/\n/g, "<br />")
            },
            getCsrfToken: function () {
                return $('input[name="_csrfToken"]').val();
            }
        },
        mounted: function () {
            this.geBusinessNotes();
            this.geBusinessTodos();
            moment().format();
        },
        filters: {
            if_null: function (value, defaultValue = '--') {
                if (value === null || value === undefined) {
                    return defaultValue;
                } else {
                    return value;
                }
            },
            note_date: function (value) {
                if (value === null || value === undefined) {
                    return value;
                } else {
                    return moment(value).fromNow();
                }
            },
            note_user_name: function (value) {
                if (value.user_id === USER_ID) {
                    return USER_NAME;
                }
                return 'Other user';
            }
        }
    })
</script>
