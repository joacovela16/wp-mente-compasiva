<script>
    import Filter from "./Filter.svelte";
    import {showSlideBar} from "./lib";

    let openFilters = false;
</script>
<div class="fixed w-full z-index-20 shadow-lg shadow-dark-500 bg-white">
    <div class="flex flex-col md:(flex-row) p-3 items-start md:items-center">
        <div class="flex-grow-0 cursor-pointer flex flex-row space-x-2" on:click={()=>showSlideBar.set(true)}>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </span>
            <span class="font-bold">MENU</span>
        </div>
        <div class="md:flex-1 sm:flex-grow-0"></div>
        <Filter/>
        <div class="md:flex-1 sm:flex-grow-0"></div>
        <div class="flex-grow-0 cursor-pointer font-bold">
            LOGIN/REGISTER
        </div>
    </div>


</div>
