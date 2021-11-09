const DropdownItem = {
    name: 'dropdown-item',
    inject: ['test'],
    template: `<a href="#!"  class="hover:bg-gray-100 block px-4 py-2 text-sm" @click="test($event)"><slot></slot></a>`
};
const Dropdow = {
    name: "dropdown",
    props: {label: {type: String, default: 'hello!'}},
    provide() {
        return {test: this.test};
    },
    data() {
        return {open: false};
    },
    methods: {
        test(event) {
            this.open = false;
        },
        handleClickOutside(e) {
            if (this.$refs.rootEl && !this.$refs.rootEl.contains(e.target)) {
                this.open = false;
            }
        }
    },
    mounted() {
        console.log("created")
        window.addEventListener("click", this.handleClickOutside);
    },
    unmounted() {
        console.log("destroyed")
        window.removeEventListener("click", this.handleClickOutside);
    },

    template: `
<div class="relative inline-block text-left" ref="rootEl">
<div
        class="flex flex-row
            justify-center
            w-full rounded
            shadow-sm
            px-4 py-2
            bg-white
            font-medium
            hover:bg-gray-50
            focus:outline-none
            cursor-pointer
            focus:ring-2 focus:ring-blue-500" @click="open=!open">
    <div>{{label}}</div>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
         stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <polyline points="6 9 12 15 18 9"/>
    </svg>
</div>

<div v-if="open">
   <div class="origin-top-right absolute right-0 mt-2 w-56 rounded shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1">
            <slot></slot>
        </div>
    </div>  
</div>

</div>`
};