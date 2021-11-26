<script>
    import {getContext} from "svelte";
    import Dropdown from "./Dropdown.svelte";
    import DropdownItem from "./DropdownItem.svelte";
    import Animate from "./Animate.svelte";
    import moment from "moment";
    import {querystring, replace} from "svelte-spa-router";
    import {showSlideBar} from "./lib";

    const {filterOptions} = getContext("mc-context");
    const countries = filterOptions.countries || [];
    const multimedia = filterOptions.multimedia || [];
    const reading = filterOptions.reading || [];
    const countriesSelection = [], multimediaSelection = [], readingSelection = [];

    let dateAfter, dateBefore;
    let author;
    let freeSearch;
    let openFilters = false;

    querystring.subscribe(() => openFilters = false);

    function itemUpdater(data, index) {
        data[index] = !data[index];
        updateQuery()
    }

    function updateQuery() {

        const countriesData = getItems(countriesSelection, countries);
        const multimediaData = getItems(multimediaSelection, multimedia);
        const readingData = getItems(readingSelection, reading);
        const tag = [...countriesData, ...multimediaData, ...readingData].join(",");

        const authorFn = normalizeString(author);
        const from = normalizeString(dateAfter && moment(dateAfter, "YYYY-MM-DD").format("DD/MM/YYYY"));
        const to = normalizeString(dateBefore && moment(dateBefore, "YYYY-MM-DD").format("DD/MM/YYYY"));
        const freeSearchNorm = normalizeString(freeSearch);
        const query = [
            ["s", freeSearchNorm],
            ["author", authorFn],
            ["tag", tag],
            ["after", from],
            ["before", to]
        ]
            .filter(([x, v]) => v !== undefined && v.trim().length > 0)
            .map(([k, v]) => `${k}=${v}`)
            .join("&");

        replace(`/search?${query}`)
    }

    function normalizeString(str) {
        return str === undefined || str === null ? str : str.trim().length === 0 ? undefined : str;
    }

    function getItems(selections, dataSource) {
        const lg = selections.length;
        const result = [];

        for (let i = 0; i < lg; i++) {
            const element = selections[i];
            if (element) {
                result.push(dataSource[i]);
            }
        }

        return result;
    }
</script>
<div class="flex flex-row md:(flex-col) flex-grow-0 w-full md:w-auto">
    <div class="flex flex-row items-center border-b-gray-300 border-b-width-2 bg-white px-2 bg-white w-full">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             class="flex-grow-0"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
             stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <circle cx="10" cy="10" r="7"></circle>
            <line x1="21" y1="21" x2="15" y2="15"></line>
        </svg>
        <input
                type="text"
                class="h-13 sm:w-full md:w-96 focus:outline-none"
                placeholder="SEARCH..."
                bind:value={freeSearch}
                on:change={()=>updateQuery()}>
        <!--<div class="cursor-pointer hover:underline font-bold" on:click={()=>showSlideBar.set(true)}>
            <span>ADVANCED</span>
        </div>-->
    </div>
    {#if openFilters}
        <Animate>
            <div class="flex flex-row">
                <div class="flex-1"></div>
                <Dropdown label="Author">
                    <div class="px-4 py-2">
                        <input
                                type="text"
                                class="appearance-none rounded w-full ring-gray-200 ring-1 focus:(ring-blue-500 shadow)"
                                bind:value={author}
                                on:change={()=>updateQuery()}
                        >
                    </div>
                </Dropdown>
                <Dropdown label="Country">
                    {#each countries as country, index}
                        <DropdownItem
                                on:click={()=> {countriesSelection[index]=!countriesSelection[index]; updateQuery()} }>
                            <input type="checkbox" bind:checked={countriesSelection[index]}/>
                            {country}
                        </DropdownItem>
                    {/each}
                </Dropdown>
                <Dropdown label="Multimedia">
                    {#each multimedia as item, index}
                        <DropdownItem
                                on:click={()=> {multimediaSelection[index]=!multimediaSelection[index]; updateQuery()} }>
                            <input type="checkbox" bind:checked={multimediaSelection[index]}/>
                            {item}
                        </DropdownItem>
                    {/each}
                </Dropdown>
                <Dropdown label="Reading">
                    {#each reading as item, index}
                        <DropdownItem
                                on:click={()=> {readingSelection[index]=!readingSelection[index]; updateQuery()} }>
                            <input type="checkbox" bind:checked={readingSelection[index]}/>
                            {item}
                        </DropdownItem>
                    {/each}
                </Dropdown>
                <Dropdown label="Publish date">
                    <div class="px-4 py-2">
                        <div class="flex flex-row text-right">
                            <div class="font-bold min-w-15">After:</div>
                            <input type="date" bind:value={dateAfter} on:change={()=>updateQuery()}>
                        </div>

                        <div class="flex flex-row text-right">
                            <div class="font-bold min-w-15">Before:</div>
                            <input type="date" bind:value={dateBefore} on:change={()=>updateQuery()}>
                        </div>
                    </div>
                </Dropdown>
                <div class="flex-1"></div>
            </div>
        </Animate>
    {/if}
</div>