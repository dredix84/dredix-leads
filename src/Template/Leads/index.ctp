<?php
/** @var AjaxView $this */

/** @var boolean $starred */

use App\View\AjaxView;
use Cake\Routing\Router;

// Used to determine if search should be only for starred businesses
$starred = isset($starred) ? $starred : false;

?>
<script>
    const STARRED_URL_TOGGLE = '<?= Router::url(['controller' => 'Leads', 'action' => 'toggleStarred']) ?>/';
</script>
<style>
    td {
        max-width: 80px;
    }
</style>

<!-- Main content -->
<section class="content" id="pageApp">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Search</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <?= $this->Form->create(null, ['v-on:submit.prevent' => 'getBusinesses']) ?>

                <div class='col-md-3'>
                    <?= $this->Form->label('City') ?>
                    <v-select :options="cities" v-model="search.city"></v-select>
                </div>
                <div class='col-md-3'>
                    <?= $this->Form->control('company_name', ['v-model' => 'search.company_name']); ?>
                </div>

                <?= $this->Form->submit('Search', [":disables" => 'is_busy']) ?>
                <?= $this->Form->end() ?>
            </div>

        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Businesses</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">

            <div class="table-responsive no-padding">
                <table class="table table-hover" v-if="search_results !== null">
                    <thead>
                    <tr>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Web</th>
                        <th>Address</th>
                        <th>Info</th>
                    </tr>
                    </thead>
                    <tbody v-for="business in search_results">
                    <tr>
                        <td>
                            <i class="gold fa"
                               v-bind:class="{ 'fa-star': business.starred, 'fa-star-o': !business.starred }"
                               v-on:click="toggleStarred(business)"
                            ></i>
                            <a :href="'/leads/business/' + business._id['$oid']" class="bold">
                                {{business.company_name}}
                            </a><br/>
                            <google-search text="Google Search" :search-text="business.company_name"></google-search>
                        </td>
                        <td>
                            Naics: {{business.naics_description}}<br>
                            SIC: {{business.sic_description}}
                        </td>
                        <td>{{business.contact_name | titleCase}}</td>
                        <td>
                            <div v-if="business.telephone"><i class="fa fa-phone"></i>: {{business.telephone}}</div>
                            <div v-if="business.fax"><i class="fa fa-fax"></i>: {{business.fax}}</div>
                            <div v-if="business.toll_free"><i class="fa fa-blender-phone"></i>: {{business.toll_free}}
                            </div>
                        </td>
                        <td>
                            <div v-if="business.email">
                                <i class="fa fa-at"></i>: <a-email :text="business.email" :email="business.email"></a-email>
                            </div>
                            <div v-if="business.email2"><i class="fa fa-at"></i> 2:
                                {{business.email2}}
                            </div>
                            <div v-if="business.website">
                                <i class="fa fa-chrome"></i>:
                                <a-link
                                    :href="business.website"
                                    :title="business.website"
                                    text="Open Website">
                                </a-link>

                            </div>
                        </td>
                        <td>
                            {{business | address}}<br />
                            <google-map text='Map' :address="business | address"></google-map>
                        </td>
                        <td>
                            <div v-if="business.sales_volume">
                                <i class="fa fa-chart-bar"></i>: {{business.sales_volume}}
                            </div>
                            <div v-if="business.employee_size_raw"><i class="fa fa-users"></i>:
                                {{business.employee_size_raw}}
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div v-else>
                    <div class="callout callout-info">
                        <h4>Hang Tight!</h4>
                        <p>There is no data to show yet.</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>

</section>


<script>
    Vue.component('v-select', VueSelect.VueSelect);

    var app = new Vue({
        el: '#pageApp',
        data: {
            cities: <?= json_encode(array_values($cities)) ?>,
            search: {
                city: null,
                company_name: null,
                <?= $starred ? 'starred: true' : '' ?>
            },
            search_results: null,
            is_busy: false
        },
        methods: {
            getBusinesses: function () {
                var that = this;
                axios
                    .get(
                        '<?= Router::url(['action' => 'getBusinesses']) ?>',
                        {
                            headers: {
                                "Accept": "application/json"
                            },
                            params: this.search
                        }
                    )
                    .then(response => {
                        console.log(response);
                        that.search_results = response.data.data;
                    })
                    .catch(error => {
                        showError('Something went wrong while trying to get the businesses. Refresh th page and try again!');
                        console.log(error);
                    })
            },
            toggleStarred: function (business) {
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
                    var oldStatus = business.starred ? true : false;
                    business.starred = !oldStatus;
                    if (result.value) {
                        axios.post(
                            STARRED_URL_TOGGLE + business._id['$oid'],
                            {starred: business.starred}
                        )
                            .then(function (response) {
                                that.getBusinesses()
                            })
                            .catch(function (error) {
                                showError('Something went wrong and the Starred status was not updated!');
                                console.log(error);
                                business.starred = oldStatus;
                            });
                    }
                })
            }
        },
        filters: {
            address: function (business) {
                let addressParts = [business.address, business.city, business.state, business.postal_code];
                let filtered = addressParts.filter(function (el) {
                    return el != null;
                });
                return filtered.join(', ');
            },
            mapAddress: function (value) {
                return "https://www.google.com/maps/place/" + encodeURI(value);
            },
            googleSearchUrl: function (value) {
                return "https://www.google.com/search?q=" + encodeURI(value);
            },
            titleCase: function (str) {
                if (str) {
                    return str.toLowerCase().split(' ').map(function (word) {
                        return (word.charAt(0).toUpperCase() + word.slice(1));
                    }).join(' ');
                } else {
                    return '';
                }

            }
        }
    })
</script>

