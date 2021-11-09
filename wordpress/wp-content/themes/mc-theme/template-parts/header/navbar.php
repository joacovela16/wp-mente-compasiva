<div class="fixed top-0 left-0 w-full z-index-10 shadow-md text-gray-700">
    <div class="flex flex-col bg-white">
        <div class="flex flex-row p-3 font-bold items-center ">
            <div class="flex-grow-0 cursor-pointer flex flex-row space-x-2">
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
                <span>MENU</span>
            </div>
            <div class="flex-1"></div>
            <div class="flex-grow-0 flex flex-row items-center border-b-gray-300 border-b-width-2 bg-white px-2 bg-white">
                <div class="flex-grow-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="10" cy="10" r="7"></circle>
                        <line x1="21" y1="21" x2="15" y2="15"></line>
                    </svg>
                </div>
                <input type="text" class="h-13 w-96 focus:outline-none" placeholder="SEARCH...">
                <div class="cursor-pointer hover:underline" @click="toggleFilters()">
                    <span>SHOW FILTERS</span>
                </div>
            </div>
            <div class="flex-1"></div>
            <div class="flex-grow-0  cursor-pointer">
                LOGIN/REGISTER
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex-1"></div>
            <dropdown label="COUNTRY" v-if="openFilter">
                <dropdown-item>Account settings</dropdown-item>
                <dropdown-item>Support</dropdown-item>
                <dropdown-item>License</dropdown-item>
            </dropdown>
            <!--{#if showFilters}
			<MultiDropdown>
				<DropdownItem>Account settings</DropdownItem>
				<DropdownItem>Support</DropdownItem>
				<DropdownItem>License</DropdownItem>
			</MultiDropdown>
			{/if}-->
            <div class="flex-1"></div>
        </div>
    </div>
</div>
