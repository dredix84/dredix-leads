Vue.component('google-search', {
    props: ['text', 'searchText'],
    data: function () {
        return {}
    },
    methods: {
        getSearchUrl: function (searchString) {
            return "https://www.google.com/search?q=" + encodeURI(searchString);
        }
    },
    template: '<a :href="getSearchUrl(searchText)" target="_blank">{{text}} <i class="fa fa-link"></i></a>'
});

Vue.component('a-link', {
    props: ['text', 'title', 'href'],
    data: function () {
        return {}
    },
    template: '<a :href="href" target="_blank" :title="title">{{text}} <i class="fa fa-link"></i></a>'
});

Vue.component('a-email', {
    props: ['text', 'title', 'email'],
    data: function () {
        return {}
    },
    template: '<a :href="\'mailto:\' + email" target="_blank" :title="title">{{text}}</a>'
});

Vue.component('google-map', {
    props: ['text', 'address', 'title'],
    data: function () {
        return {}
    },
    methods: {
        getUrl: function (value) {
            return "https://www.google.com/maps/place/" + encodeURI(value);
        }
    },
    template: '<a :href="getUrl(address)" target="_blank">{{text}} <i class="fa fa-map"></i></a>'
});

/**
 * Used to create an input for inline edit
 *
 * v-model: Used to do a 2 way bind Example: v-model="business_details.email2"
 * null-text: Used to set the text to display if no data was in value. Example: null-text="--"
 * :saved-action: Used to set a function to call if the user clicks save. Example: :saved-action="inlineEditSave"
 * :data: Set the data that should be returned to the function set with :saved-action Example :data="{key: business_id, field: 'email2'}" would return {key: business_id, field: 'email2', value: 'value here'}
 * placeholder: Set the placeholder placeholder="Email address here"
 */
Vue.component('v-input', {
    props: ['text', 'nullText', 'value', 'name', 'placeholder', 'type', 'savedAction', 'cancelAction', 'data', 'required'],
    data: function () {
        return {
            editing: false,
            original_value: this.value,
            control_id: this.getUUID()
        }
    },
    methods: {
        getUUID: function () {
            return Math.random().toString(36).substring(2) + Date.now().toString(36);
        },
        getCData: function () {
            if (!this.data) {
                this.data = {}
            }
            return this.data
        },
        cancelEdit: function () {
            this.value = this.original_value;
            this.editing = false;
            if (this.cancelAction) {
                let data = this.getCData();
                data.value = this.value;
                this.cancelAction(data);
            }
            this.$emit('input', this.value);
        },
        saveValue: function () {
            var inpObj = document.getElementById(this.control_id);
            if (!inpObj.checkValidity()) {  //Checking if input is valid
                inpObj.reportValidity();
            } else {
                this.editing = false;
                if (this.savedAction) {
                    let data = this.getCData();
                    data.value = this.value;
                    this.savedAction(data);
                }
            }
        },
        getText: function (value, defaultValue) {
            if (defaultValue === undefined) {
                defaultValue = '--TEST--';
            }

            if (value === null || value === undefined || value === "") {
                return defaultValue;
            } else {
                return value;
            }
        }
    },
    computed: {
        placeholderValue: function () {
            return this.getText(this.placeholder, 'No placeholder')
        },
        typeValue: function () {
            return this.getText(this.type, 'text')
        }
    },
    template: `
<span class="v-input" v-if="!editing">
    {{ getText(value, nullText) }} 
    <div class="tools">
        <i class="fa fa-edit" v-on:click="editing = !editing"></i>
    </div>
</span>
<div class="v-input v-input-text " v-else>
    <div 
        class="input-group" 
        @keyup.esc="cancelEdit"
        @keyup.enter="saveValue"
    >
        <input 
            :id="control_id"
            class="form-control"
            :type="typeValue"
            :required="required"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
            :placeholder="placeholderValue"
        > 
        <div class="input-group-btn">
            <button type="button" class="btn btn-success" title="Save" v-on:click="saveValue"><i class="fa fa-check-circle"></i></button>
            <button type="button"  class="btn btn-warning" title="Cancel" v-on:click="cancelEdit"><i class="fa fa-window-close"></i></button>
        </div>
    </div>
</div>
`
});



